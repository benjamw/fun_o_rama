<?php

App::uses('AppModel', 'Model');

class Forgot extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);

	public function make($user_id = null) {
		if ( ! $user_id) {
			return false;
		}

		// delete all other forgot requests for this user
		$this->deleteAll(array(
			'Forgot.user_id' => $user_id,
		));

		// create a new entry
		$token = md5(uniqid(microtime( ), true));

		$data['Forgot'] = array(
			'user_id' => $user_id,
			'token' => $token,
		);

		$this->create( );
		if ($this->save($data)) {
			return $token;
		}

		return false;
	}

}

