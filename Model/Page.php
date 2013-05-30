<?php

App::uses('AppModel', 'Model');

class Page extends AppModel {

	public $displayField = 'title';
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must enter a page title',
			),
		),
		'slug' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);

	/*
	public $belongsTo = array(
		'ParentPage' => array(
			'model' => 'Page',
		),
	);

	public $hasMany = array(
		'ChildPage' => array(
			'model' => 'Page',
		),
	);
	*/

	public function grab($path) {
		$parent_id = 0;
		$pages = $contain = array( );

		if (is_string($path)) {
			$path = explode('/', $path);
		}

		foreach ($path as $slug) {
			$conditions = array( );
			if (isset($this->belongsTo['ParentPage'])) {
				$conditions = array(
					'Page.parent_id' => $parent_id,
				);

				$contain = array(
					'contain' => array(
						'ChildPage' => array(
							'conditions' => array(
								'ChildPage.active' => 1,
							),
						),
					),
				);
			}
			elseif ($parent_id) {
				return false;
			}

			$page = $this->find('first', array_merge($contain, array(
				'conditions' => array_merge($conditions, array(
					'Page.slug' => $slug,
					'Page.active' => 1,
				)),
			)));

			if ($page) {
				$parent_id = $page['Page']['id'];
				$pages[] = $page;
			}
			else {
				return false;
			}
		}

		$pages = array_reverse($pages);
		return $pages;
	}

	public function beforeValidate($options = array( )) {
		if (isset($this->data[$this->alias]['slug']) && empty($this->data[$this->alias]['slug'])) {
			$this->data[$this->alias]['slug'] = Inflector::slug(strtolower($this->data[$this->alias]['title']));
		}

		return parent::beforeValidate($options);
	}

}

