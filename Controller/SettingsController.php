<?php

App::uses('AppController', 'Controller');

class SettingsController extends AppController {

	public function admin_edit($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			$saved = true;
			foreach ($this->request->data['Setting'] as $key => $setting) {
				if (is_array($setting['value'])) {
					$setting['value'] = $this->_upload_file($setting['value']);

					if (empty($setting['value'])) {
						continue;
					}
				}

				if ( ! is_int($key)) {
					continue;
				}

				$setting['value'] = trim($setting['value']);

				$setting = array('Setting' => $setting);

				$saved = $saved && $this->Setting->save($setting);
			}

			if ($saved) {
				$this->Session->setFlash(__('The Settings have been saved'));
			}
			else {
				$this->Session->setFlash(__('The Settings could not be saved. Please, try again.'));
			}
		}

		$this->request->data = $this->Setting->find('all');
	}

	public function admin_index( ) {
		return $this->redirect(array('action' => 'edit'));
	}

	public function admin_view($id = null) {
		return $this->redirect(array('action' => 'edit'));
	}

	public function admin_add( ) {
		return $this->redirect(array('action' => 'edit'));
	}

	public function admin_delete($id = null) {
		return $this->redirect(array('action' => 'edit'));
	}

	protected function _upload_file($file_data) {
		$error = false;
		if ( ! empty($file_data['tmp_name'])) {
			$fileName = $this->Setting->generateUniqueFilename($file_data['name']);
			$error = $this->Setting->handleFileUpload($file_data, $fileName);

			if ($error) {
				return false;
			}
		}
		else {
			return false;
		}

		return $fileName;
	}

	// this function only works when it is specifically enabled
	// via the $enabled variable and debug mode is on
	public function admin_update_install( ) {
		$enabled = false;

		if ( ! $enabled || ! Configure::read('debug')) {
			return $this->redirect('/');
		}
		Configure::write('debug', 2);

		$windows = isset($_SERVER['WINDIR']) && (false !== strpos(strtolower($_SERVER['WINDIR']), 'win'));
		debug('On '.($windows ? 'Windows' : '*nix').' box');

		// set our path the the cake shell script
		$cake = CAKE_CORE_INCLUDE_PATH.DS.'Cake'.DS.'Console'.DS;
		if ($windows) {
			if ('/' == substr(CAKE_CORE_INCLUDE_PATH, 0, 1) || ! realpath($cake)) {
				$cake = '';
			}
		}
		$cake .= 'cake';
		debug($cake);

		// move us to the right dir
		chdir(ROOT.DS.APP_DIR);
		($windows ? debug(`echo %cd%`) : debug(`pwd`));

//* ---- *\
		$cachePaths = array('js', 'css', 'menus', 'views', 'persistent', 'models');
		foreach ($cachePaths as $config) {
			clearCache(null, $config);
		}
		debug('Cache cleared');
//* ---- */

//* ---- *\
		// migrate up
		$return = shell_exec("{$cake} migrate up 2> tmp/output");
		debug($return ? $return : join('', file('tmp/output')));
		unlink('tmp/output');
		debug('Schema Updated');
//* ---- */

		if ( ! $this->use_acl) {
			die('DONE - NO ACL');
		}

		// kill all the ArosAcos
		$query = "
			TRUNCATE TABLE `{$this->Acl->Aro->Permission->tablePrefix}{$this->Acl->Aro->Permission->table}`
		";
		debug($query);
		debug($this->Acl->Aro->Permission->query($query));

//* ---- *\
		// kill all the Acos
		$query = "
			TRUNCATE TABLE `{$this->Acl->Aco->tablePrefix}{$this->Acl->Aco->table}`
		";
		debug($query);
		debug($this->Acl->Aco->query($query));

		// rebuild the aco table

		// aco sync
		$return = shell_exec("{$cake} AclExtras.AclExtras aco_sync 2> tmp/output");
		debug($return ? $return : join('', file('tmp/output')));
		unlink('tmp/output');
//* ---- */

		for ($id = 1; $id <= 3; ++$id) {
			$aro = array('model' => 'Group', 'foreign_key' => $id);
			debug($aro);

			switch ($id) {
				case 1 : // admin
					debug('--- ADMIN ---');

					$this->Acl->allow($aro, 'controllers');
					break;

				case 2 : // users
					debug('--- USERS ---');

					$deny = array(
						// 'Act' => '*',
					);

					$children = $this->Acl->Aco->children(1, true);

					foreach ($children as $child) {
						if (0 === strcmp($child['Aco']['alias'], 'Admin')) {
							continue;
						}

						debug($child);

						$sub_children = $this->Acl->Aco->children($child['Aco']['id'], true);

						// basically give access to everything except the admin area
						foreach ($sub_children as $sub_child) {
							$not_admin = (false === strpos($sub_child['Aco']['alias'], 'admin_'));
							$deny_all = (isset($deny[$child['Aco']['alias']]) && ('*' === $deny[$child['Aco']['alias']]));
							$deny_child = (isset($deny[$child['Aco']['alias']]) && is_array($deny[$child['Aco']['alias']]) && in_array($sub_child['Aco']['alias'], $deny[$child['Aco']['alias']]));

							// don't include the Facebook Connect hooks, either
							$facebook = (bool) preg_match('/(?:before|after)Facebook(?:Login|Save)/i', $sub_child['Aco']['alias']);

							if ($not_admin && ! $facebook && ! ($deny_all || $deny_child)) {
								debug($child['Aco']['alias'].'/'.$sub_child['Aco']['alias']);
								$this->Acl->allow($aro, $child['Aco']['alias'].'/'.$sub_child['Aco']['alias']);
							}
						}
					}
					break;

				case 3 : // strangers / guests
					debug('--- GUESTS ---');

					$allowed = array(
						'CakeError' => '*',
						'Contact' => '*',
						'Pages' => '*',
						'Users' => array(
							'add',
							'login',
							'logout',
						),
					);

					if ($this->use_forgot_pass) {
						$allowed['Forgots'] = array('index');
					}

					$deny = array(
						// 'Act' => '*',
					);

					$children = $this->Acl->Aco->children(1, true);

					foreach ($children as $child) {
						if ( ! in_array($child['Aco']['alias'], array_keys($allowed))) {
							continue;
						}

						debug($child);

						$sub_children = $this->Acl->Aco->children($child['Aco']['id'], true);

						// give access to anything in $allowed except the admin area
						foreach ($sub_children as $sub_child) {
							$not_admin = (false === strpos($sub_child['Aco']['alias'], 'admin_'));
							$allow_all = (isset($allowed[$child['Aco']['alias']]) && ('*' === $allowed[$child['Aco']['alias']]));
							$allow_child = (isset($allowed[$child['Aco']['alias']]) && is_array($allowed[$child['Aco']['alias']]) && in_array($sub_child['Aco']['alias'], $allowed[$child['Aco']['alias']]));
							$allow_ac = ('ac' === $sub_child['Aco']['alias']);
							$deny_all = (isset($deny[$child['Aco']['alias']]) && ('*' === $deny[$child['Aco']['alias']]));
							$deny_child = (isset($deny[$child['Aco']['alias']]) && is_array($deny[$child['Aco']['alias']]) && in_array($sub_child['Aco']['alias'], $deny[$child['Aco']['alias']]));

							// don't include the Facebook Connect hooks, either
							$facebook = (bool) preg_match('/(?:before|after)Facebook(?:Login|Save)/i', $sub_child['Aco']['alias']);

							if ($not_admin && ! $facebook && ($allow_all || $allow_child || $allow_ac) && ! ($deny_all || $deny_child)) {
								debug($child['Aco']['alias'].'/'.$sub_child['Aco']['alias']);
								$this->Acl->allow($aro, $child['Aco']['alias'].'/'.$sub_child['Aco']['alias']);
							}
						}
					}
					break;
			}
		}

		die('DONE');
	}

}

