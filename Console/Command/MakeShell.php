<?php

App::uses('BakeShell', 'Console/Command');
App::uses('Model', 'Model');

/**
 * Command-line code generation utility to automate programmer chores.
 *
 * Bake is CakePHP's code generation script, which can help you kickstart
 * application development by writing fully functional skeleton controllers,
 * models, and views. Going further, Bake can also write Unit Tests for you.
 *
 * @package       Cake.Console.Command
 * @link          http://book.cakephp.org/2.0/en/console-and-shells/code-generation-with-bake.html
 */
class MakeShell extends BakeShell {

/**
 * Contains tasks to load and instantiate
 *
 * @var array
 */
	public $tasks = array('Make', 'Project', 'DbConfig', 'MyModel', 'MyController', 'MyView', 'Plugin', 'Fixture', 'Test');

/**
 * Override main() to handle action
 *
 * @return mixed
 */
	public function main() {
		if (!is_dir($this->DbConfig->path)) {
			$path = $this->Project->execute();
			if (!empty($path)) {
				$this->DbConfig->path = $path . 'Config' . DS;
			} else {
				return false;
			}
		}

		if (!config('database')) {
			$this->out(__d('cake_console', 'Your database configuration was not found. Take a moment to create one.'));
			$this->args = null;
			return $this->DbConfig->execute();
		}
		$this->out(__d('cake_console', 'Interactive Bake Shell'));
		$this->hr();
		$this->out(__d('cake_console', '[D]atabase Configuration'));
		$this->out(__d('cake_console', '[M]odel'));
		$this->out(__d('cake_console', '[V]iew'));
		$this->out(__d('cake_console', '[C]ontroller'));
		$this->out(__d('cake_console', '[P]roject'));
		$this->out(__d('cake_console', '[F]ixture'));
		$this->out(__d('cake_console', '[T]est case'));
		$this->out(__d('cake_console', '[E]verything (MVC)'));
		$this->out(__d('cake_console', '[Q]uit'));

		$classToBake = strtoupper($this->in(__d('cake_console', 'What would you like to Bake?'), array('D', 'M', 'V', 'C', 'P', 'F', 'T', 'E', 'Q')));
		switch ($classToBake) {
			case 'D':
				$this->DbConfig->execute();
				break;
			case 'M':
				$this->MyModel->execute();
				break;
			case 'V':
				$this->MyView->execute();
				break;
			case 'C':
				$this->MyController->execute();
				break;
			case 'P':
				$this->Project->execute();
				break;
			case 'F':
				$this->Fixture->execute();
				break;
			case 'T':
				$this->Test->execute();
				break;
			case 'E':
				$this->everything();
				break;
			case 'Q':
				return $this->_stop();
			default:
				$this->out(__d('cake_console', 'You have made an invalid selection. Please choose a type of class to Bake by entering D, M, V, F, T, or C.'));
		}
		$this->hr();
		$this->main();
	}

/**
 * Quickly bake Everything in the Project (MVC)
 *
 * @return void
 */
	public function everything() {
		$this->out('Bake Everything');
		$this->hr();

		if (!isset($this->params['connection']) && empty($this->connection)) {
			$this->connection = $this->DbConfig->getConfig();
		}

		$prompt = __d('cake_console', "Would you like skip existing files?");
		$skip_existing = $this->in($prompt, array('y', 'n'), 'y');

		$skip_existing = (strtolower($skip_existing) === 'y');

		foreach (array('MyModel', 'MyController', 'MyView') as $task) {
			$this->{$task}->connection = $this->connection;
			$this->{$task}->interactive = false;
			$this->{$task}->skip_existing = $skip_existing;
		}

		$this->MyModel->all( );
		$this->MyController->all( );
		$this->MyView->all( );

		$this->out('', 1, Shell::QUIET);
		$this->out(__d('cake_console', '<success>Bake All complete</success>'), 1, Shell::QUIET);

		return $this->_stop();
	}

}
