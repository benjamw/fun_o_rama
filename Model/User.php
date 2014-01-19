<?php

App::uses('AppModel', 'Model');

class User extends AppModel {

	public $displayField = 'username';

	// here we can edit the fields used for auth
	public $auth_username = 'username';
	public $auth_password = 'password';

	public $validate = array(
		'first_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'last_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a username',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'That username is already in use',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid email address',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'That email address is already in use',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'current' => array(
			'current' => array(
				'rule' => array('validateCurrentPassword'),
				'message' => 'Please enter your current password',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				'on' => 'update',
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a password',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				'on' => 'create',
			),
		),
		'confirm' => array(
			'required' => array(
				'rule' => array('notempty'),
				'message' => 'Please verify your password',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				'on' => 'create',
			),
			'confirm' => array(
				'rule' => array('passConfirm'),
				'message' => 'The passwords entered do not match',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $actsAs = array(
		'Acl' => array('type' => 'requester'),
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

		$id_empty = empty($this->data[$this->alias]['id']);
		$pass_missing = empty($this->data[$this->alias][$this->auth_password]);
		$pass_empty = ($pass_missing || (0 === strcmp('', $this->data[$this->alias][$this->auth_password])));
		$confirm_empty = (0 === strcmp('', $confirm));

		// if we are not creating, and both values are empty, return true
		if ( ! $id_empty && $confirm_empty && ($pass_missing || $pass_empty)) {
			return true;
		}

		$passes_equal = (0 === strcmp($confirm, $this->data[$this->alias][$this->auth_password]));

		if ( ! $pass_empty && $passes_equal) {
			return true;
		}

		return false;
	}

	public function validateCurrentPassword($data) {
		list($field, $current) = each($data);

		$current_empty = (0 === strcmp('', $current));

		if ($current_empty) {
			$pass_missing = empty($this->data[$this->alias][$this->auth_password]);
			$pass_empty = ($pass_missing || (0 === strcmp('', $this->data[$this->alias][$this->auth_password])));

			return ($pass_missing || $pass_empty);
		}

		$current_pass_match = (0 === strcmp(Security::hash($current, 'blowfish', $this->field($this->auth_password)), $this->field($this->auth_password)));

		return $current_pass_match;
	}

	public function parentNode( ) {
		if ( ! $this->id && empty($this->data)) {
			return null;
		}

		$data = $this->data;
		if ( ! empty($this->id)) {
			$data = array_merge($this->read( ), $this->data);
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
		if (empty($this->id) && empty($this->data['User']['id']) && empty($this->data['Group']['id']) && empty($this->data['User']['group_id'])) {
			$this->data['User']['group_id'] = 2;
		}

		// hash the password if we have one, remove it if we don't
		if ( ! empty($this->data['User'][$this->auth_password])) {
			$this->data['User'][$this->auth_password] = Security::hash($this->data[$this->alias][$this->auth_password], 'blowfish');
		}
		else {
			unset($this->data['User'][$this->auth_password]);
		}

		if (isset($this->data['User']['email'])) {
			$this->data['User']['email'] = strtolower($this->data['User']['email']);
		}

		return parent::beforeSave($options);
	}

	public function afterSave($created, $options = array( )) {
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

		parent::afterSave($created, $options);
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

