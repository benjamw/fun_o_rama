<?php

App::uses('ModelTask', 'Console/Command/Task');

/**
 * Task class for creating and updating model files.
 */
class MyModelTask extends ModelTask {

/**
 * Tables to skip
 *
 * @var array
 */
	public $skipTables = array('acos', 'aros', 'aros_acos', 'schema_migrations', 'i18n');

/**
 * Bake all models at once.
 *
 * @return void
 */
	public function all( ) {
		if ( ! property_exists($this, 'skip_existing')) {
			$this->interactive = true;

			$prompt = __d('cake_console', "Would you like skip existing files?");
			$skip_existing = $this->in($prompt, array('y', 'n'), 'y');

			$this->skip_existing = (strtolower($skip_existing) === 'y');
		}

		$this->interactive = false;
		$this->listAll($this->connection, false);
		foreach ($this->_tables as $table) {
			if (in_array($table, $this->skipTables)) {
				continue;
			}
			$modelClass = Inflector::classify($table);
			$this->out(__d('cake_console', 'Baking %s', $modelClass));
			$object = $this->_getModelObject($modelClass, $table);
			$this->bake($object, false);
		}
	}

/**
 * Assembles and writes a Model file.
 *
 * @param string|object $name Model name or object
 * @param array|boolean $data if array and $name is not an object assume bake data, otherwise boolean.
 * @return string
 */
	public function bake($name, $data = array()) {
		if ($name instanceof Model) {
			if (!$data) {
				$data = array();
				$data['associations'] = $this->doAssociations($name);
				$data['validate'] = $this->doValidation($name);
				$data['actsAs'] = $this->doActsAs($name);
			}
			$data['primaryKey'] = $name->primaryKey;
			$data['useTable'] = $name->table;
			$data['useDbConfig'] = $name->useDbConfig;
			$data['name'] = $name = $name->name;
		} else {
			$data['name'] = $name;
		}

		$defaults = array(
			'associations' => array(),
			'actsAs' => array(),
			'validate' => array(),
			'primaryKey' => 'id',
			'useTable' => null,
			'useDbConfig' => 'default',
			'displayField' => null
		);
		$data = array_merge($defaults, $data);

		$pluginPath = '';
		if ($this->plugin) {
			$pluginPath = $this->plugin . '.';
		}

		$this->Template->set($data);
		$this->Template->set(array(
			'plugin' => $this->plugin,
			'pluginPath' => $pluginPath
		));
		$out = $this->Template->generate('classes', 'model');

		$path = $this->getPath();
		$filename = $path . $name . '.php';
		$this->out("\n" . __d('cake_console', '-- Baking model class for %s...', $name), 1, Shell::QUIET);

		$filename = str_replace(DS . DS, DS, $filename);

		if (is_file($filename) && isset($this->skip_existing) && $this->skip_existing) {
			ClassRegistry::flush();
			return false;
		}

		$this->createFile($filename, $out);
		ClassRegistry::flush();
		return $out;
	}

}
