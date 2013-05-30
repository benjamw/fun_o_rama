<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public function login( ) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Auth->login( )) {
				if ( ! isset($this->Auth->autoRedirect) || $this->Auth->autoRedirect) {
					$this->redirect($this->Auth->redirect( ));
				}
			}
			else {
				$this->Session->setFlash($this->Auth->loginError);
				$this->request->data[$this->Auth->userModel][$this->Auth->authenticate['all']['fields']['password']] = null;
			}
		}

		if ($this->use_remember_me) {
			//-- code inside this function will execute only when autoRedirect was set to false (i.e. in a beforeFilter).
			if ($this->Auth->user( )) {
				if ( ! empty($this->request->data)) {
					if ( ! empty($this->request->data['User']['remember_me'])) {
						$this->RememberMe->make( );
					}
					else { // we still need to lock the account
						$this->RememberMe->lock( );
					}

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

		$this->_set_auth( );
	}

	public function admin_login( ) {
		$this->login( );
		$this->render('login');
	}

	public function logout( ) {
		if ($this->use_remember_me) {
			$this->RememberMe->delete( );
		}

		$this->Session->setFlash(__('You\'ve successfully logged out.'));
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
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Profile updated'));
				$this->redirect(array('controller' => 'users', 'action' => 'edit'));
			}
			else {
				$this->Session->setFlash(__('There are errors in the form. Please try again.'));
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

}

