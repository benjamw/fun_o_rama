<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class ForgotsController extends AppController {

	protected $site_name = 'Site Name';
	protected $email_from = array('forgot@example.com' => 'Forgot Password');

	public function index( ) {
		$step = 'form';

		if ( ! empty($this->request->params['token'])) {
			$forgot = $this->Forgot->findByToken($this->request->params['token']);

			if ( ! $forgot) {
				$this->redirect('/');
			}

			// make sure our request is not past it's expiration date
			if (strtotime($forgot['Forgot']['created']) <= strtotime('-1 days')) {
				$step = 'expired';
			}
			else {
				$user = $this->Forgot->User->findById($forgot['Forgot']['user_id']);

				if ( ! $user) {
					$this->redirect('/');
				}

				// reset the user's pass
				$password = $this->Forgot->User->reset_pass($user['User']['id']);

				// send the email
				$Email = new CakeEmail( );
				$Email->from($this->email_from);
				$Email->to($user['User']['email']);
				$Email->subject('New Reset Password');
				$Email->emailFormat('text');
				$Email->template('reset');
				$Email->viewVars(array(
					'site_name' => $this->site_name,
					'password' => $password,
				));

			/*/ debugging
				Configure::write('debug', 2);
				$Email->transport('Debug');
				debug($Email->send( ));
				die;
			//*/

				if ($Email->send( )) {
					$this->Forgot->delete($forgot['Forgot']['id']);

					// let the user know
					$step = 'reset';
				}
				else {
					$step = 'fail';
				}
			}
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$user = $this->Forgot->User->findByEmail($this->request->data['User']['email']);
			if ($user) {
				// create a new forgots entry
				$token = $this->Forgot->make($user['User']['id']);

				if ($token) {
					// send the email
					$Email = new CakeEmail( );
					$Email->from($this->email_from);
					$Email->to($user['User']['email']);
					$Email->subject('Password Reset Requested');
					$Email->emailFormat('text');
					$Email->template('sent');
					$Email->viewVars(array(
						'site_name' => $this->site_name,
						'link' => Router::url(array('controller' => 'forgots', 'action' => 'index', 'token' => $token), true),
					));

				/*/ debugging
					Configure::write('debug', 2);
					$Email->transport('Debug');
					debug($Email->send( ));
					die;
				//*/

					if ($Email->send( )) {
						// let the user know
						$step = 'sent';
					}
					else {
						$step = 'fail';
					}
				}
				else {
					$step = 'fail';
				}
			}
		}

		$this->set('step', $step);
	}

}

