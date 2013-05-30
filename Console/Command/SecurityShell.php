<?php

if ( ! class_exists('File')) {
	uses('file');
}

class SecurityShell extends Shell {

	function help( ) {
		$this->out('Shell to automatically update Security.salt value in '.$this->path);
		$this->hr( );
		$this->out('cake security salt');
		$this->out('    - Sets the security salt value in the');
		$this->out('    - APP config file');
		$this->out('    - Will only work on uninitialized values');
	}

	function salt( ) {
		if (true === $this->securitySalt($this->params['working'].DS)) {
			$this->out(__('Random hash key created for \'Security.salt\''));
		}
		else {
			$this->err(sprintf(__('Unable to generate random hash for \'Security.salt\', you should change it in %s'), CONFIGS . 'core.php'));
		}
	}

/**
 * Generates and writes 'Security.salt'
 *
 * @param string $path Project path
 * @return boolean Success
 * @access public
 */
	function securitySalt($path) {
		$File =& new File($path . 'config' . DS . 'core.php');
		$contents = $File->read();
		if (preg_match('/([\\t\\x20]*Configure::write\\(\\\'Security.salt\\\',[\\t\\x20\'A-z0-9]*\\);)/', $contents, $match)) {
			if (!class_exists('Security')) {
				uses('Security');
			}
			$string = Security::generateAuthKey();
			$result = str_replace($match[0], "\t" . 'Configure::write(\'Security.salt\', \''.$string.'\');', $contents);
			if ($File->write($result)) {
				return true;
			}
			return false;
		}
		return false;
	}

}

