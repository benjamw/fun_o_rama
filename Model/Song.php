<?php

App::uses('AppModel', 'Model');

class Song extends AppModel {

	public $displayField = 'title';
	public $playerLimit = 10;

	public $validate = array(
		'player_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'file' => array(
			'required' => array(
				'rule' => array('isValidExtension', array('mp3'), true),
				'message' => 'MP3s only',
				'allowEmpty' => false,
				'on' => 'create',
			),
			'extension' => array(
				'rule' => array('isValidExtension', array('mp3'), false),
				'message' => 'MP3s only',
				'allowEmpty' => true,
				'on' => 'update',
			),
		),
	);

	public $actsAs = array(
		'Upload.Upload' => array(
			'file' => array(
				'path' => '{ROOT}webroot{DS}files{DS}{model}{DS}',
				'thumbnails' => false,
				'extensions' => array('mp3'),
				'fields' => array(
					'dir' => 'file_dir',
				),
			),
		),
	);

	public $belongsTo = array(
		'Player',
	);

	public $hasMany = array(
		'Vote' => array(
			'dependent' => true,
		),
	);

	public function beforeValidate($options = array( )) {
		// rename the file so that it has no spaces
		if ( ! empty($_FILES['data']['name']['Song']['file'])) {
			$orig_name = $this->data['Song']['file']['name'];
			$name = strtolower($this->data['Song']['file']['name']);
			$ext = substr($name, strrpos($name, '.'));
			$name = substr($name, 0, strrpos($name, '.'));
			$name = preg_replace(array('%[^\sa-z0-9_-]+%', '%[\s_-]+%'), array('', '_'), $name);

			if (empty($name)) {
				$this->invalidate('file', 'There are no distinct characters in the filename.');
				return false;
			}

			$this->data['Song']['file']['name'] = $name.$ext;
			$_FILES['data']['name']['Song']['file'] = $name.$ext;
		}

		// if the title is blank, fill it with the file name
		if (array_key_exists('title', $this->data['Song']) && empty($this->data['Song']['title'])) {
			$this->data['Song']['title'] = $orig_name;
		}

		return parent::beforeValidate($options);
	}

	public function beforeSave($options = array( )) {
		// count this player's current songs and
		// limit them to a total of self::playerLimit songs
		$count = $this->find('count', array(
			'conditions' => array(
				'player_id' => $this->data['Song']['player_id'],
			),
		));

		if ($count >= $this->playerLimit) {
			$this->invalidate('file', 'You have reached your maximum allowed '.$this->playerLimit.' songs');
			return false;
		}

		return parent::beforeSave($options);
	}

}

