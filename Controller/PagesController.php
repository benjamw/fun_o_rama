<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {

	public $use_model = false;
	public $allow_add_delete = false;
	public $allow_slug_edit = false;
	public $uses = array( ); // don't add 'Page' to this, edit use_model above

	public function __construct($request = null, $response = null) {
		if ($this->use_model) {
			// make sure 'Page' is first
			array_unshift($this->uses, 'Page');
		}

		parent::__construct($request, $response);
	}

	public function display( ) {
		$path = func_get_args( );

		$count = count($path);
		if ( ! $count) {
			return $this->redirect('/');
		}

		$page = $subpage = $title_for_layout = null;

		if ( ! empty($path[0])) {
			$page = $path[0];
		}

		if ( ! empty($path[1])) {
			$subpage = $path[1];
		}

		if ( ! empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}

		if ($this->use_model) {
			// check if we have an entry for this in the database
			$pages = $this->Page->grab($path);
			if ($pages) {
				$page = $pages[0];
				$this->set('pages', $pages);
			}
		}

		$this->set(compact('page', 'subpage', 'title_for_layout'));

		if ( ! $this->use_model || ! $pages) {
			try {
				$this->render(implode('/', $path));
			} catch (MissingViewException $e) {
				if (Configure::read('debug')) {
					throw $e;
				}
				throw new NotFoundException();
			}
		}
	}

	public function admin_index( ) {
		$this->_enable_cg( );
		parent::admin_index( );
	}

	public function admin_view($id = null) {
		$this->_enable_cg( );
		parent::admin_view($id);
	}

	public function admin_add( ) {
		$this->_enable_cg( );
		if ( ! $this->allow_add_delete) {
			return $this->redirect(array('controller' => 'pages', 'action' => 'index'));
		}
		parent::admin_add( );
	}

	public function admin_edit($id = null) {
		$this->_enable_cg( );
		parent::admin_edit($id);
	}

	public function admin_delete($id = null) {
		$this->_enable_cg( );
		if ( ! $this->allow_add_delete) {
			return $this->redirect(array('controller' => 'pages', 'action' => 'index'));
		}
		parent::admin_delete($id);
	}

	public function _enable_cg( ) {
		$cg_user_id = (1 == $this->user['User']['id']);
		$cg_username = isset($this->user['User']['username']) && ('codegreene' == $this->user['User']['username']);
		$cg_email = isset($this->user['User']['email']) && ('info@codegreene.com' == $this->user['User']['email']);
		if ($cg_user_id && ($cg_username || $cg_email)) {
			$this->allow_add_delete = true;
			$this->allow_slug_edit = true;
		}

		$this->set('allow_add_delete', $this->allow_add_delete);
		$this->set('allow_slug_edit', $this->allow_slug_edit);
	}

}

