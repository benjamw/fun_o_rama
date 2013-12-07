<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public $preset_data = array( );

	public $filter_skip_fields = array(
		'password',
		'ident',
		'token',
	);

	public function login( ) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Auth->login( )) {
				if ($this->use_remember_me) {
					$this->_setCookie($this->Auth->user('id'));
				}

				return $this->redirect($this->Auth->redirect( ));
			}
			else {
				$this->Session->setFlash($this->Auth->loginError, 'flash_error');
				$this->request->data[$this->Auth->userModel][$this->Auth->authenticate['all']['fields']['password']] = null;
			}
		}

		if ($this->Auth->loggedIn( ) || $this->Auth->login( )) {
			return $this->redirect($this->Auth->redirectUrl( ));
		}

		$this->_set_auth( );
	}

	public function admin_login( ) {
		$this->login( );
		$this->render('login');
	}

	public function logout( ) {
		if ($this->use_remember_me) {
			$this->_deleteCookie( );
		}
		session_destroy( );

		$this->Session->setFlash(__('You\'ve successfully logged out.'), 'flash_success');
		$this->redirect($this->Auth->logout( ));
	}

	public function admin_logout( ) {
		$this->logout( );
	}

	public function edit( ) {
		$this->User->id = $this->user['User']['id'];

		if ( ! $this->User->exists( )) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['User']['id'] = $this->user['User']['id'];
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Profile updated'), 'flash_success');
				$this->redirect(array('controller' => 'users', 'action' => 'edit'));
			}
			else {
				$user = $this->User->findById($this->user['User']['id']);
				$this->request->data['User'] = array_merge($user['User'], $this->request->data['User']);
				$this->Session->setFlash(__('There are errors in the form. Please try again.'), 'flash_error');
			}
		}
		else {
			$this->request->data = $this->User->read( );
		}
	}

	protected function _setSelects($active_only = false) {
		$this->set('groups', $this->User->Group->find('list', array(
			'conditions' => array(
				'Group.name !=' => 'Guest',
			),
			'order' => array(
				'Group.id' => 'asc',
			),
		)));

		parent::_setSelects($active_only);
	}

	protected function _set_auth( ) {
		$this->set('auth_fields', $this->Auth->authenticate[AuthComponent::ALL]['fields']);
		$this->set('auth_remember', $this->use_remember_me);
		$this->set('auth_forgot', $this->use_forgot_pass);
	}

	protected function _setCookie($id) {
		if ( ! $this->request->data('User.remember_me')) {
			return false;
		}

		$data = array(
			$this->User->auth_username => $this->request->data('User.'.$this->User->auth_username),
			$this->User->auth_password => $this->request->data('User.'.$this->User->auth_password),
		);

		$this->Cookie->write('User', $data, true, '+2 week');

		return true;
	}

	protected function _deleteCookie( ) {
		$this->Cookie->delete('User');
		return true;
	}

}

