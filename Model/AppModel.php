<?php

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	public $recursive = -1;
	public $actsAs = array(
		'Containable',
		'ExtendAssociations',
	);


	// cake has a pretty major bug in which it duplicates entries
	// if the data is coming from a multi-linked HABTM relationships
	// http://cakephp.lighthouseapp.com/projects/42648/tickets/1598
	// so we need to filter all those extra values out
	public function afterFind($results, $primary = false) {
		// this tries to kill any duplicates at the source
		// if it's a HABTM relationship, it's not the primary
		if ( ! $primary) {
			$used = array( );
			foreach ($results as $key => $value) {
				// if it's a HABTM relationship, it will have an id, and no alias
				// but if it's another relationship, $value will not be an array
				if ( ! is_array($value) || ! isset($value['id'])) {
					continue;
				}

				if ( ! in_array($value['id'], $used)) {
					$used[] = $value['id'];
				}
				else {
					unset($results[$key]);
				}
			}
		}

		// this kills any duplicated relationships if they happen to slip through
		foreach ($this->hasAndBelongsToMany as $alias => $options) {
			foreach ($results as $key1 => $value) {
				$used = array( );
				if (is_array($value) && array_key_exists($alias, $value) && ! empty($value[$alias])) {
					foreach ($value[$alias] as $key2 => $entry) {
						if (is_array($entry) && ! in_array($entry['id'], $used)) {
							$used[] = $entry['id'];
						}
						else {
							unset($results[$key1][$alias][$key2]);
						}
					}
				}
			}
		}

		// clean up any missing data from behaviors
		if ( ! $primary) {
			$results = $this->_fix_image_array($results);
			$results = $this->_fix_upload_path($results);
		}

		return parent::afterFind($results, $primary);
	}


	// this is to set the recursion level for admin_edit pages
	// in the models, this can be overriden with an actual contain( ) call
	public function _setContains( ) {
		$this->recursive = 1;
	}


	// if your paginate query has a group by clause, cake chokes on the
	// counting query, this fixes that
	// http://stackoverflow.com/questions/7120257/cakephp-pagination-count-not-matching-query
	public function paginateCount($conditions = null, $recursive = 0, $extra = array( )) {
		$parameters = compact('conditions', 'recursive');

		if (isset($extra['group'])) {
			$parameters['fields'] = $extra['group'];

			if (is_string($parameters['fields'])) {
				// pagination with single GROUP BY field
				if (substr($parameters['fields'], 0, 9) != 'DISTINCT ') {
					$parameters['fields'] = 'DISTINCT ' . $parameters['fields'];
				}

				unset($extra['group']);

				$count = $this->find('count', array_merge($parameters, $extra));
			}
			else {
				// resort to inefficient method for multiple GROUP BY fields
				$count = $this->find('count', array_merge($parameters, $extra));
				$count = $this->getAffectedRows( );
			}
		}
		else {
			// regular pagination
			$count = $this->find('count', array_merge($parameters, $extra));
		}

		return $count;
	}

	// cake does not do afterFinds for related models
	// so call this function to fix the image data in those models
	public function _fix_image_array($results) {
		if ( ! isset($this->Behaviors->Image)) {
			return $results;
		}

		return $this->Behaviors->Image->afterFind($this, $results);
	}


	// cake does not do afterFinds for related models
	// so call this function to fix the upload data in those models
	public function _fix_upload_path($results) {
		if ( ! isset($this->Behaviors->MeioUpload)) {
			return $results;
		}

		return $this->Behaviors->MeioUpload->afterFind($this, $results);
	}


	// for use with auto complete fields to either
	// insert the value in the table and grab the id
	// or find the value and return the id
	public function prepare_data($data, $other_data = array( )) {
		if ( ! $data) {
			return false;
		}

		$result = $this->find('first', array(
			'conditions' => array_merge(array(
				$this->displayField => $data,
			), $other_data),
		));

		if ($result) {
			return (int) $result[$this->alias]['id'];
		}

		$data = array($this->displayField => $data);
		if (is_array($other_data)) {
			$data = array_merge($data, $other_data);
		}

		$this->create( );
		if ($this->save($data, false)) {
			return (int) $this->id;
		}

		return false;
	}

	public function _fix_tree_sort( ) {
		// repair this portion of the tree
		// because we are not setting the left and right values directly

		$this->reorder(array(
			// make sure we edit root if we need to
			'id' => ( ! empty($this->data[$this->alias]['parent_id']) ? $this->data[$this->alias]['parent_id'] : 0),
			'field' => $this->alias.'.sort',
		));
	}

	public function valueBetween($value, $min, $max) {
		list($key, $value) = each($value);
		return ($value >= $min) && ($value <= $max);
	}

}

