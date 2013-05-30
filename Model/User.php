<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller');

class User extends AppModel {

	public $displayField = 'username';

	// here we can edit the fields used for auth
	public $auth_username = 'username';
	public $auth_password = 'password';

	public $validate = array(
//		'group_id' => array('numeric'),
		'first_name' => array('notempty'),
		'last_name' => array('notempty'),
		'username' => array(
			'exists' => array(
				'rule' => 'notempty',
				'message' => 'Please enter a username',
				'required' => true,
				'allowEmpty' => false,
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'That username is already in use',
			),
		),
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Please enter a valid email address',
				'required' => true,
				'allowEmpty' => false,
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'That email address is already in use',
			),
		),
		'current' => array(
			'current' => array(
				'rule' => 'validateCurrentPassword',
				'message' => 'You must enter your current password',
				'required' => false,
				'on' => 'update',
			),
		),
		'password' => array(
			'pass' => array(
				'rule' => 'notempty',
				'message' => 'You must enter a password',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create',
			),
		),
		'confirm' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'You must verify your password',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create',
			),
			'confirm' => array(
				'rule' => 'passConfirm',
				'message' => 'The passwords entered do not match',
				'required' => false,
			),
		),
	);

	public $actsAs = array(
		'Acl' => array('requester'),
	);

	public $belongsTo = array(
		'Group',
	);

	public $hasOne = array(
		'Forgot' => array(
			'dependent' => true,
		),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->virtualFields['full_name'] = sprintf('CONCAT(%1$s.first_name, " ", %1$s.last_name)', $this->alias);
	}

	public function passConfirm($data) {
		list($field, $confirm) = each($data);

		$id_empty = empty($this->request->data[$this->alias]['id']);
		$pass_missing = empty($this->request->data[$this->alias][$this->auth_password]);
		$pass_empty = ($pass_missing || (0 === strcmp('', $this->data[$this->alias][$this->auth_password])));
		$confirm_empty = (0 === strcmp('', $confirm));

		// if we are not creating, and both values are empty, return true
		if ( ! $id_empty && $confirm_empty && ($pass_missing || $pass_empty)) {
			return true;
		}

		$passes_equal = (0 === strcmp($confirm, $this->request->data[$this->alias][$this->auth_password]));

		if ( ! $pass_empty && $passes_equal) {
			return true;
		}

		return false;
	}

	public function validateCurrentPassword($data) {
		list($field, $current) = each($data);

		$current_empty = (0 === strcmp('', $current));

		if ($current_empty) {
			$pass_missing = empty($this->request->data[$this->alias][$this->auth_password]);
			$pass_empty = ($pass_missing || (0 === strcmp('', $this->data[$this->alias][$this->auth_password])));

			return ($pass_missing || $pass_empty);
		}

		$current_pass_match = (0 === strcmp(AuthComponent::password($current), $this->field($this->auth_password)));

		return $current_pass_match;
	}

	public function parentNode( ) {
		if ( ! $this->id && empty($this->request->data)) {
			return null;
		}

		$data = $this->request->data;
		if ( ! empty($this->id)) {
			$data = array_merge($this->read( ), $this->request->data);
		}

		if (empty($data['User']['group_id'])) {
			return null;
		}
		else {
			return array('Group' => array('id' => $data['User']['group_id']));
		}
	}

	public function bindNode($user) {
		return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}

	public function beforeSave($options = array( )) {
		// make sure the user has a group, but only if we are creating the user fresh
		if (empty($this->id) && empty($this->request->data['User']['id']) && empty($this->request->data['Group']['id']) && empty($this->request->data['User']['group_id'])) {
			$this->request->data['User']['group_id'] = 2;
		}

		// hash the password if we have one, remove it if we don't
		if ( ! empty($this->data['User'][$this->auth_password])) {
			$this->data['User'][$this->auth_password] = AuthComponent::password($this->data['User'][$this->auth_password]);
		}
		else {
			unset($this->data['User'][$this->auth_password]);
		}

		return parent::beforeSave($options);
	}

	public function afterSave($created) {
		if ( ! $created) {
			$parent = $this->parentNode( );
			$parent = $this->node($parent);
			$node = $this->node( );
			$aro = $node[0];
			$aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
			$aro['Aro']['model'] = 'User';
			$aro['Aro']['foreign_key'] = $this->id;
			$this->Aro->save($aro);
		}

		parent::afterSave($created);
	}

	public function reset_pass($user_id) {
		$password = strtoupper(substr(md5(uniqid(microtime( ), true)), 0, 10));

		$data['User'] = array(
			'id' => $user_id,
			$this->auth_password => $password,
		);

		if ($this->save($data, false)) {
			return $password;
		}

		return false;
	}

	public function get_admin_ids($active = false) {
		return $this->get_group_ids(1, $active);
	}

	public function get_client_ids($active = false) {
		return $this->get_group_ids(2, $active);
	}

	public function get_group_ids($group_id, $active = false) {
		$conditions = array( );
		if ($active) {
			$conditions = array(
				'User.active' => 1,
			);
		}

		return $this->find('list', array(
			'fields' => array(
				'User.id',
			),
			'conditions' => array_merge($conditions, array(
				'User.group_id' => $group_id,
			)),
		));
	}

}

