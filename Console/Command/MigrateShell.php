<?php

App::uses('ConnectionManager', 'Model');
App::uses('Folder', 'Utility');

class MigrateShell extends Shell {

	public $path = null;
	public $prefix = '';
	public $uses = array('schema_migrations');
	protected $db;

	function initialize( ) {
		$this->path = APP . 'Config' . DS . 'Schema' . DS . 'migrations';
		$this->db =& ConnectionManager::getDataSource('default');
		$this->prefix = empty($this->db->config['prefix']) ? '' : $this->db->config['prefix'];
	}

	function help( ) {
		$this->out('Shell to run sql files in '.$this->path);
		$this->out('Runs mysql on the command line');
		$this->out('Depends on the "default" database config');
		$this->hr();
		$this->out('cake migrate create (name)');
		$this->out('    - creates a migration file with the given name');
		$this->out('    - eg. cake migrate up add users table');
		$this->out('cake migrate up');
		$this->out('    - Update to the latest file');
		$this->out('cake migrate up [num]');
		$this->out('    - Update to file [num]');
	}

	function create( ) {
		$name = (empty($this->args)) ? 'update' : implode('_', $this->args);

		date_default_timezone_set('UTC');
		$filename = date('YmdHis') . '_' . $name . '.sql';
		$file = new File($this->path . DS . $filename);

		if ($file->create( )) {
			$this->out("Migration file $filename created");
		}
		else {
			$this->out('Failed to create the file');
		}
	}

	function up( ) {
		$endVersion = null;
		if ( ! empty($this->args[0])) {
			$endVersion = $this->args[0];
		}

		// check to make sure the table exists
		$this->checkMigrationTable( );

		// pull which numbers have been run and sort them
		$query = "
			SELECT *
			FROM {$this->prefix}schema_migrations
		";
		$schemaVersions = $this->schema_migrations->query($query);

		if ( ! empty($schemaVersions)) {
			$schemaVersions = Set::extract($schemaVersions, '/'.$this->prefix.'schema_migrations/version');
			sort($schemaVersions);
		}

		$files = $this->listFiles($this->path);
		sort($files);

		$toRun = array( );
		foreach ($files as $file) {
			$filename = basename($file);

			if ( preg_match('/^([0-9]{14})_(.*).sql$/', $filename, $matches) ) {
				$num = $matches[1];
			}
			else {
				list($num) = explode('.', $filename);
			}

			if ( ! in_array($num, $schemaVersions)) {
				$toRun[] = $filename;
			}
		}

		foreach ($toRun as $file) {
			$filename = basename($file);
			list($num) = explode('.', $filename);

			if (($num <= $endVersion) || is_null($endVersion)) {
				$this->out('Running: '. $filename);
				$this->runFile($this->path . DS . $file);
			}
		}
	}

	function runFile($file) {
		$filename = basename($file);
		if ( preg_match('/^([0-9]{14})_(.*).sql$/', $filename, $matches) ) {
			$num = $matches[1];
		} else { // old method
			list($num) = explode('.', $filename);
		}

		// *nix box
		$cmd = "mysql -h '{$this->db->config['host']}' -u '{$this->db->config['login']}'";
		if ( ! empty($this->db->config['password'])) {
			$cmd.= " -p'{$this->db->config['password']}'";
		}
		$cmd.= " '{$this->db->config['database']}' < {$file}";
		$cmd.= " 2>&1";

		// Windows box
		if (false !== stripos(PHP_OS, 'win')) {
			$cmd = "mysql -h {$this->db->config['host']} -u {$this->db->config['login']}";
			if ( ! empty($this->db->config['password'])) {
				$cmd.= " --password={$this->db->config['password']}";
			}
			$cmd.= " {$this->db->config['database']} < {$file}";
			$cmd.= " 2>&1";
		}

		exec($cmd, $output, $return);

		if (0 == $return) {
			$this->schema_migrations->create( );
			$this->schema_migrations->save(array('version' => $num));
		}
		else {
			$this->error("File $filename failed", "Reason: \n". implode("\n", $output));
		}
	}

	function listFiles($path = null) {
		$folder = new Folder($path);
		$return = $folder->findRecursive('.*sql');
		return $return;
	}

	// Make sure the table exists, if not create it
	// Then load the model
	function checkMigrationTable( ) {
		$tables = $this->getAllTables( );

		if ( ! in_array('schema_migrations', $tables)) {
			$this->out('Creating schema_migrations table.');

			$this->db->cacheSources = false;

			$query = "
				CREATE TABLE `{$this->prefix}schema_migrations` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`version` varchar(255) NOT NULL,
					PRIMARY KEY (`id`),
					UNIQUE KEY `version` (`version`)
				)
			";

			$this->db->query($query);
			Cache::clear( );
		}

		$this->_loadModels( );
	}

	function getAllTables($useDbConfig = 'default') {
		$tables = $this->db->listSources( );

		if ($this->prefix) {
			foreach ($tables as & $table) {
				if ( ! strncmp($table, $this->prefix, strlen($this->prefix))) {
					$table = substr($table, strlen($this->prefix));
				}
			}
		}

		$this->__tables = $tables;

		return $tables;
	}

}

