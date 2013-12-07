<?php

App::uses('ControllerTask', 'Console/Command/Task');

/**
 * Task class for creating and updating controller files.
 */
class MyControllerTask extends ControllerTask {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
	public $tasks = array('MyModel', 'Test', 'Template', 'DbConfig', 'Project');

/**
 * Tables to skip
 *
 * @var array
 */
	public $skipTables = array('acos', 'aros', 'aros_acos', 'schema_migrations', 'i18n');

/**
 * Bake All the controllers at once. Will only bake controllers for models that exist.
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
		ClassRegistry::config('Model', array('ds' => $this->connection));

		foreach ($this->__tables as $table) {
			if (in_array($table, $this->skipTables)) {
				continue;
			}
			$model = $this->_modelName($table);
			$controller = $this->_controllerName($model);
			App::uses($model, 'Model');
			if (class_exists($model)) {
				$this->bake($controller);
			}
		}
	}

/**
 * Assembles and writes a Controller file
 *
 * @param string $controllerName Controller name already pluralized and correctly cased.
 * @param string $actions Actions to add, or set the whole controller to use $scaffold (set $actions to 'scaffold')
 * @param array $helpers Helpers to use in controller
 * @param array $components Components to use in controller
 * @return string Baked controller
 */
	public function bake($controllerName, $actions = '', $helpers = null, $components = null) {
		$this->out("\n" . __d('cake_console', '-- Baking controller class for %s...', $controllerName), 1, Shell::QUIET);

		$isScaffold = ($actions === 'scaffold') ? true : false;

		$this->Template->set(array(
			'plugin' => $this->plugin,
			'pluginPath' => empty($this->plugin) ? '' : $this->plugin . '.'
		));

		$this->Template->set(compact('controllerName', 'actions', 'helpers', 'components', 'isScaffold'));
		$contents = $this->Template->generate('classes', 'controller');

		$path = $this->getPath();
		$filename = $path . $controllerName . 'Controller.php';

		$filename = str_replace(DS . DS, DS, $filename);
		if (is_file($filename) && isset($this->skip_existing) && $this->skip_existing) {
			return false;
		}

		if ($this->createFile($filename, $contents)) {
			return $contents;
		}
		return false;
	}

}
