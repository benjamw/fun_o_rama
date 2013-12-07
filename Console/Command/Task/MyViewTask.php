<?php

App::uses('ViewTask', 'Console/Command/Task');

/**
 * Task class for creating and updating view files.
 */
class MyViewTask extends ViewTask {

/**
 * Tasks to be loaded by this Task
 *
 * @var array
 */
	public $tasks = array('Project', 'MyController', 'DbConfig', 'Template');

/**
 * Actions to use for scaffolding
 *
 * @var array
 */
	public $scaffoldActions = array('index', 'view', 'edit');

/**
 * Tables to skip
 *
 * @var array
 */
	public $skipTables = array('acos', 'aros', 'aros_acos', 'schema_migrations', 'i18n');

/**
 * Bake All views for Full project.
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
		$this->Controller->interactive = false;
		$tables = $this->Controller->listAll($this->connection, false);

		$actions = null;
		if (isset($this->args[1])) {
			$actions = array($this->args[1]);
		}
		$this->interactive = false;
		foreach ($tables as $table) {
			if (in_array($table, $this->skipTables)) {
				continue;
			}
			$model = $this->_modelName($table);
			$this->controllerName = $this->_controllerName($model);
			App::uses($model, 'Model');
			if (class_exists($model)) {
				$vars = $this->_loadController();

				$admin = $this->Project->getPrefix();
				$regularActions = $this->scaffoldActions;
				$adminActions = array();
				foreach ($regularActions as $action) {
					$adminActions[] = $admin . $action;
				}
				$this->bakeActions($adminActions, $vars);
			}
		}
	}

/**
 * Assembles and writes bakes the view file.
 *
 * @param string $action Action to bake
 * @param string $content Content to write
 * @return boolean Success
 */
	public function bake($action, $content = '') {
		if ($content === true) {
			$content = $this->getContent($action);
		}
		if (empty($content)) {
			return false;
		}
		$this->out("\n" . __d('cake_console', '-- Baking view file for %s::%s...', Inflector::classify($this->controllerName), $action), 1, Shell::QUIET);
		$path = $this->getPath();
		$filename = $path . $this->controllerName . DS . Inflector::underscore($action) . '.ctp';

		$filename = str_replace(DS . DS, DS, $filename);
		if (is_file($filename) && isset($this->skip_existing) && $this->skip_existing) {
			return false;
		}

		return $this->createFile($filename, $content);
	}

}
