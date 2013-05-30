<?php

App::uses('AppModel', 'Model');

class Group extends AppModel {

	public $displayField = 'name';

	public $actsAs = array(
		'Acl' => array('requester'),
	);

	public $hasMany = array(
		'User',
	);

	public function parentNode( ) {
		if ( ! $this->id && empty($this->data)) {
			return null;
		}

		$data = $this->request->data;
		if ( ! empty($this->id)) {
			$data = array_merge($this->read( ), $this->request->data);
		}

		if ( ! $data['Group']['parent_id']) {
			return null;
		}
		else {
			return array('Group' => array('id' => $data['Group']['parent_id']));
		}
	}

}

