<?php
/*

taken from http://lecterror.com/articles/view/rememberme-component-for-cakephp
and modified to not store clear text passwords

USAGE:

APP/controllers/users_controller.php
----------------------------------------------
class UsersController extends AppController
{
	function login( ) {
		//-- code inside this function will execute only when autoRedirect was set to false (i.e. in a beforeFilter).
		if ($this->Auth->user( )) {
			if ( ! empty($this->request->data) && $this->request->data['User']['remember_me']) {
				$this->RememberMe->make( );
				unset($this->request->data['User']['remember_me']);
			}

			$this->redirect($this->Auth->redirect( ));
		}

		if (empty($this->request->data)) {
			if ($this->RememberMe->check( )) {
				$this->redirect($this->Auth->redirect( ));
			}
		}
	}

	function logout( ) {
		$this->RememberMe->delete( );
		$this->Session->setFlash('You\'ve successfully logged out.');
		$this->redirect($this->Auth->logout( ));
	}
}

----------------------------------------------
APP/app_controller.php
----------------------------------------------
class AppController extends Controller
{
	var $components = array('RememberMe');

	function beforeFilter()
	{
		// this bit is required else it all breaks
		$this->Auth->autoRedirect = false; // for the remember me component

		// component settings are customizable here,
		// much like the Auth component.. For example:
		// $this->RememberMe->period = '+2 months';

		// snip...
		$this->RememberMe->check();
		// snip..
	}
}

----------------------------------------------
*/

App::uses('Component', 'Controller');

class RememberMeComponent extends Component {

	// the following vars are customisable in the beforeFilter function
	public $period = '+2 months';
	public $cookie_name = 'NeverForget';
	public $lock_cookie_name = 'OnlyOne';
	public $ident_field = 'ident';
	public $token_field = 'token';
	public $use_lock = false;
	public $components = array('Auth', 'Cookie');
	public $controller = null;

	function __construct(ComponentCollection $collection, $settings = array( )) {
		parent::__construct($collection, $settings);
		$this->cookie_name = $this->cookie_name.APP_DIR;
		$this->lock_cookie_name = $this->lock_cookie_name.APP_DIR;
	}

	function initialize(Controller $controller) {
		parent::initialize($controller);
		$this->controller = $controller;
	}

	function beforeFilter( ) {
 		$this->cookie_name = $this->cookie_name.APP_DIR;
		$this->lock_cookie_name = $this->lock_cookie_name.APP_DIR;
	}

	function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {
		parent::beforeRedirect($controller, $url, $status, $exit);
	}

	function startup(Controller $controller) {
		$this->controller = $controller;
	}

	function make( ) {
		$data = array(
			$this->ident_field => $this->_create_token( ),
			$this->token_field => $this->_create_token( ),
		);

		$this->Cookie->name = $this->cookie_name;
		$this->Cookie->time = $this->period;
		$this->Cookie->key = base64url_encode(implode('::', $data));
		$this->Cookie->secure = true;

		$this->Auth->getModel( )->save(array($this->Auth->getModel( ) => array_merge(array('id' => $this->Auth->user('id')), $data)), false);
	}

	function check( ) {
		$cookie = $this->Cookie->read($this->cookie_name);

		if (empty($cookie)) {
			return false;
		}

		$data = explode('::', base64url_decode($cookie));

		$user = $this->Auth->getModel( )->find('first', array(
			'conditions' => array(
				$this->Auth->userModel.'.ident' => $data[0],
			),
		));

		if ( ! $user) {
			return false;
		}

		$token = $user[$this->Auth->userModel]['token'];

		if (0 === strcmp($token, $data[1])) {
			$this->Auth->login($user);
			return true;
		}

		return false;
	}

	function lock( ) {
		if ( ! $this->use_lock) {
			return true;
		}

		$data = array(
			$this->ident_field => $this->_create_token( ),
		);

		$this->Auth->getModel( )->save(array($this->Auth->userModel => array_merge(array('id' => $this->Auth->user('id')), $data)), false);

		$this->Cookie->write($this->lock_cookie_name, base64url_encode(implode('::', $data)), true, $this->period);
	}

	function check_lock( ) {
		if ( ! $this->use_lock) {
			return true;
		}

		$cookie = $this->Cookie->read($this->cookie_name);

		if (empty($cookie)) {
			$cookie = $this->Cookie->read($this->lock_cookie_name);
		}

		if (empty($cookie)) {
			return false;
		}

		$data = explode('::', base64url_decode($cookie));
		$auth = $this->Auth->user( );

		$user = $this->Auth->getModel( )->find('first', array(
			'conditions' => array(
				$this->Auth->userModel.'.id' => $auth['User']['id'],
			),
		));

		if ( ! $user) {
			return false;
		}

		$ident = $user[$this->Auth->userModel]['ident'];

		if (0 === strcmp($ident, $data[0])) {
			return true;
		}

		return false;
	}

	function delete( ) {
		$this->Cookie->delete($this->cookie_name);
		$this->Cookie->delete($this->lock_cookie_name);
	}

	function _create_token( ) {
		return md5(uniqid(microtime( ), true));
	}

}

