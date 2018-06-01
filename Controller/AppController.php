<?php

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $helpers = array('Session', 'Html', 'Form', 'Plural', 'Menu', 'Identicon.Identicon');
	public $components = array('Session', 'Auth', 'DebugKit.Toolbar');

	public $user = array( );
	public $use_settings = false;
	public $use_acl = false;
	public $use_remember_me = true;
	public $use_forgot_pass = true;
	public $main_page = '/';
	public $filter_skip_fields = array( );
	public $filter_skip_models = array( );

	public $allowed = array(
		'*' => '*',
		'pages' => '*',
		'users' => array('login', 'logout', 'admin_login', 'admin_logout'),
	);

	public function __construct($request = null, $response = null) {
		if ($this->use_acl) {
			$this->components[] = 'Acl';
		}

		if ($this->use_remember_me) {
			$this->components[] = 'Cookie';
		}

		if ($this->use_forgot_pass) {
			$this->allowed['forgots'] = '*';
		}

		parent::__construct($request, $response);
	}

	public function beforeFilter( ) {
		parent::beforeFilter( );

		if ($this->Session->check('LOGIN.blocked')) {
			echo 'Access Denied';
			exit;
		}

		if (isset($this->Cookie)) {
			$this->Cookie->path = preg_replace('%/+%', '/', '/'.APP_DIR.'/');
			$this->Cookie->type('rijndael'); //Enable AES symetric encryption of cookie
		}

		if (isset($this->Auth)) {
			if ($this->use_acl) {
				$this->Auth->authorize = array(
					AuthComponent::ALL => array('actionPath' => 'controllers'),
					'Actions',
				);
			}

			$this->Auth->authenticate = array(
				AuthComponent::ALL => array(
					// do not edit the following values, edit the values in the User model
					'fields' => array('username' => m('User')->auth_username, 'password' => m('User')->auth_password),
					'scope' => array('User.active' => 1),
					'userModel' => 'User',
				),
				'Cookie',
				'Blowfish',
			);

			$this->Auth->loginError = __('Username / Password does not match.  Please try again.');
			$this->Auth->loginAction = array('admin' => true, 'prefix' => 'admin', 'controller' => 'users', 'action' => 'login');
			$this->Auth->unauthorizedRedirect = array('admin' => true, 'prefix' => 'admin', 'controller' => 'users', 'action' => 'login');
			$this->Auth->loginRedirect = array('admin' => true, 'prefix' => 'admin', 'controller' => 'users', 'action' => 'index');
			$this->Auth->logoutRedirect = '/';

			// this is only valid _after_ login, not during
			m('User')->contain('Group');
			$this->user = m('User')->findById($this->Auth->user('id'));
			$this->set('Auth', $this->user);

			if ( ! $this->user) {
				$guest_alias = array('model' => 'Group', 'foreign_key' => 3);

				// check this guest aro for access
				if ($this->use_acl && $this->Acl->check($guest_alias, $this->name.'/'.$this->action)) {
					$this->Auth->allow( );
				}
				else {
					$this->Auth->deny( );
				}
			}

//* -- DEBUGGING --
		Configure::write('debug', 2);
		$this->Auth->allow( );
//* -- END DEBUGGING -- */

			// if the request has the admin prefix switch the layout
			if ( ! empty($this->request->params['admin'])) {
				$this->layout = 'admin';

				if (isset($this->user['User']['id']) && (1 == $this->user['User']['id'])) {
					$this->Auth->allow('admin_update_install');
				}

				$this->Auth->allow($this->action);
			}
			elseif ( ! $this->use_acl && (isset($this->allowed)
				// set the preceding to true to always allow non-admin pages to guests
				&& array_key_exists($this->request['params']['controller'], $this->allowed)
				&& ('*' == $this->allowed[$this->request['params']['controller']]
					|| (is_array($this->allowed[$this->request['params']['controller']])
						&& in_array($this->action, $this->allowed[$this->request['params']['controller']]))))
			) {
				$this->Auth->allow($this->action);
			}
		}

		// grab the settings
		if ($this->use_settings && ! isset($this->Setting)) {
			$this->Setting = m('Setting');
			$this->set('settings', $this->Setting->load( ));
		}
		else {
			$this->set('settings', array( ));
		}
	}

	public function beforeRender( ) {
		parent::beforeRender( );

		// override the default flash message holder
		if ($this->Session->check('Message.flash')) {
			$flash = $this->Session->read('Message.flash');

			if ($flash['element'] == 'default') {
				$flash['element'] = 'flash_info';
				$this->Session->write('Message.flash', $flash);
			}
		}

		// override the default auth message holder
		if ($this->Session->check('Message.auth')) {
			$auth = $this->Session->read('Message.auth');

			if ($auth['element'] == 'default') {
				$auth['element'] = 'flash_info';
				$this->Session->write('Message.auth', $auth);
			}
		}

		// if we are on an admin page then set up the admin menu
		if ( ! empty($this->request->params['admin'])) {
			$controllerList = App::objects('controller');

			if (isset($this->AclFilter)) {
				// grab the list of allowed controllers and actions
				// and filter the list of controllers with this list
				$allowedControllers = $this->AclFilter->getACO( );
				$this->set('allowed_controllers', $allowedControllers);

				// remove the admin prefix from each action
				foreach ($allowedControllers as $key => $actions) {
					foreach ($actions as $act_key => $action) {
						$allowedControllers[$key][$act_key] = substr($action, 6); // the string length of "admin_"
					}
				}

				$controllerList = array_intersect($controllerList, array_keys($allowedControllers));
			}

			$admin = array( );

			// add our own custom nav items here
//			$admin[] = array('Contact Page', array('controller' => 'settings', 'action' => 'contact'));

			foreach ($controllerList as $controllerItem) {
				$controller = Inflector::underscore(str_replace('Controller', '', $controllerItem));
				$hide = array( // add the controller to this array to hide it from the menu
					'adjectives',
					'admin',
					'animals',
					'app',
					'colors',
					'contact',
					'home',
					'forgots',
					'imgs',
					'pages',
					'stats',
					'users',
					'votes',
				);

				if ( ! $this->use_settings) {
					$hide[] = 'settings';
				}

				if ( ! in_array($controller, $hide)) {
					$rename = array( // add the controller as the key, and the display name as the value
						'faqs' => 'FAQs',
						'faq_categories' => 'FAQ Categories',
					);
					if (in_array($controller, array_keys($rename))) {
						$controllerText = $rename[$controller];
					}
					else {
						$controllerText = Inflector::humanize($controller);
					}

					$action = 'index';
					if ('settings' == $controller) {
						$action = 'edit';

						if (isset($this->AclFilter)) {
							if ( ! in_array('edit', $allowedControllers[$controllerItem])) {
								$action = $allowedControllers[$controllerItem][0];
							}
						}
					}

					if (isset($this->AclFilter)) {
						if ( ! in_array('index', $allowedControllers[$controllerItem])) {
							$action = $allowedControllers[$controllerItem][0];
						}
					}

					$admin[] = array($controllerText, array('controller' => $controller, 'action' => $action));
				}
			}
			asort($admin);

			// rearrange these for more clarity for the client
			// these controllers will be at the top of the nav with
			// the rest of the links in alphabetical order after that
			$order = array(
//				'users',
			);

			$orig_admin = $admin;
			$admin = array( );
			$i = 0;
			while ($order && (999 >= $i++)) {
				foreach ($orig_admin as $key => $entry) {
					$next = array_shift($order);

					if ( ! $next) {
						break;
					}

					if ($next == $entry[1]['controller']) {
						$admin[] = $entry;
						unset($orig_admin[$key]);
						continue;
					}

					array_unshift($order, $next);
				}
			}

			$admin = array_values(array_merge($admin, $orig_admin));

#			$admin[] = array('Log Out', array('controller' => 'users', 'action' => 'logout'));
			$this->set('admin', $admin);
		}
	}


	/**
	* index method
	*
	* Just a catch-all index function
	*
	* @access public
	* @return void
	*/
	public function index( ) {
		return $this->redirect($this->main_page);
	}


	/**
	* ac method
	*
	* for our auto_complete fields
	*
	* @access public
	* @return void
	*/
	public function ac($term = false) {
		switch (true) {
			case ( ! empty($this->request->params['form']['term'])) :
				$term = $this->request->params['form']['term'];
				break;

			case ( ! empty($this->request->params['named']['term'])) :
				$term = $this->request->params['named']['term'];
				break;

			case ( ! empty($_GET['term'])) :
				$term = $_GET['term'];
				break;
		}

		$term = trim((string) $term);

		if ($term) {
			// grab a list of items that start with the search term
			$list = $this->{$this->modelClass}->find('list', array(
				'conditions' => array(
					$this->modelClass.'.'.$this->{$this->modelClass}->displayField.' LIKE' => $term.'%',
				),
				'order' => array(
					$this->modelClass.'.'.$this->{$this->modelClass}->displayField => 'ASC',
				),
			));

			// append a list of items that contain the search term
			$list = array_merge($list, $this->{$this->modelClass}->find('list', array(
				'conditions' => array(
					$this->modelClass.'.'.$this->{$this->modelClass}->displayField.' NOT LIKE' => $term.'%',
					$this->modelClass.'.'.$this->{$this->modelClass}->displayField.' LIKE' => '%'.$term.'%',
				),
				'order' => array(
					$this->modelClass.'.'.$this->{$this->modelClass}->displayField => 'ASC',
				),
			)));

			Configure::write('debug', 0);
			echo json_encode(array_values($list));
		}

		exit;
	}


	/**
	* admin_index method
	*
	* Use the Filer component to check for POST/GET data to use for searching.
	* An example of how to load a component for one action only
	*
	* @access public
	* @return void
	*/
	public function admin_index( ) {
		$this->_use_filter_data( );

		if (empty($this->{$this->modelClass}->Behaviors->Containable->runtime[$this->{$this->modelClass}->alias]['contain'])) {
			$this->{$this->modelClass}->recursive = 2;
		}

		$this->request->data = $this->paginate( );
		$this->set(Inflector::variable($this->name), $this->request->data);

		$this->_set_filter_data( );
	}


	/**
	* admin_view method
	*
	* @param int $id
	* @access public
	* @return void
	*/
	public function admin_view($id = null) {
		if (empty($this->{$this->modelClass}->Behaviors->Containable->runtime[$this->{$this->modelClass}->alias]['contain'])) {
			$this->{$this->modelClass}->recursive = 3;
		}

		$this->{$this->modelClass}->id = $id;

		if ( ! $this->{$this->modelClass}->exists( )) {
			throw new NotFoundException(__('Invalid '.Inflector::humanize(Inflector::underscore($this->modelClass))));
		}

		$this->request->data = $this->{$this->modelClass}->read(null, $id);
		$this->set(Inflector::variable(Inflector::singularize($this->name)), $this->request->data);
	}


	/**
	* admin_add method
	*
	* @access public
	* @return void
	*/
	public function admin_add( ) {
		if ( ! isset($this->prevent_redirect)) {
			$this->prevent_redirect = false;
		}

		if ( ! isset($this->prevent_render)) {
			$this->prevent_render = false;
		}

		if ( ! isset($this->preset_data)) {
			$this->preset_data = array( );
		}
		$this->preset_data = array_merge(array('active' => 1), $this->preset_data);

		if ($this->request->is('post')) {
			$this->{$this->modelClass}->create( );
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->Session->setFlash(__('The '.Inflector::humanize(Inflector::underscore($this->modelClass)).' has been saved'), 'flash_success');
				if ( ! $this->prevent_redirect) {
					return $this->redirect(array('action' => 'index'));
				}
				else {
					return true;
				}
			}
			else {
				$this->request->data = $this->{$this->modelClass}->data;
				$this->Session->setFlash(__('The '.Inflector::humanize(Inflector::underscore($this->modelClass)).' could not be saved. Please, try again.'), 'flash_error');
			}
		}
		else {
			$this->request->data[$this->modelClass] = $this->preset_data;
		}

		$this->_setSelects( );

		if ( ! $this->prevent_render) {
			$this->render('admin_edit');
		}
	}


	/**
	* admin_edit method
	*
	* @param mixed $id
	* @access public
	* @return void
	*/
	public function admin_edit($id = null) {
		if ( ! isset($this->prevent_redirect)) {
			$this->prevent_redirect = false;
		}

		$this->{$this->modelClass}->id = $id;

		if ( ! $this->{$this->modelClass}->exists( )) {
			throw new NotFoundException(__('Invalid '.Inflector::humanize(Inflector::underscore($this->modelClass))));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->Session->setFlash(__('The '.Inflector::humanize(Inflector::underscore($this->modelClass)).' has been saved'), 'flash_success');
				if ( ! $this->prevent_redirect) {
					return $this->redirect(array('action' => 'index'));
				}
				else {
					return true;
				}
			}
			else {
				$this->request->data = $this->{$this->modelClass}->data;
				$this->Session->setFlash(__('The '.Inflector::humanize(Inflector::underscore($this->modelClass)).' could not be saved. Please, try again.'), 'flash_error');
			}
		}
		else {
			$this->{$this->modelClass}->recursive = 1;
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}

		$this->_setSelects( );
	}


	/**
	* admin_delete method
	*
	* @param mixed $id
	* @access public
	* @return void
	*/
	public function admin_delete($id = null) {
		if ( ! $this->request->is('post')) {
			throw new MethodNotAllowedException( );
		}

		$this->{$this->modelClass}->id = $id;

		if ( ! $this->{$this->modelClass}->exists( )) {
			throw new NotFoundException(__('Invalid '.Inflector::humanize(Inflector::underscore($this->modelClass))));
		}

		if ($this->{$this->modelClass}->delete( )) {
			$this->Session->setFlash(__(Inflector::humanize(Inflector::underscore($this->modelClass)).' deleted'), 'flash_success');
			return $this->redirect(array('action' => 'index'));
		}

		$this->Session->setFlash(__(Inflector::humanize(Inflector::underscore($this->modelClass)).' was not deleted'), 'flash_error');
		return $this->redirect(array('action' => 'index'));
	}


	/**
	* setSelects method
	*
	* Populate variables used for selects
	*
	* @access protected
	* @return void
	*/
	protected function _setSelects($active_only = false) {
		$models = array($this->modelClass);
		if ($this->uses) {
			foreach ($this->uses as $key => $value) {
				if (is_string($key)) {
					$models[] = $key;
				}
				else {
					$models[] = $value;
				}
			}
		}
		$models = array_unique($models);

		foreach ($models as $model) {
			if ( ! array_key_exists('parents', $this->viewVars)
				&& (array_key_exists('Tree', (array) $this->{$model}->actsAs)
					|| in_array('Tree', (array) $this->{$model}->actsAs))
			) {
				$conditions = array( );

				if ($active_only && array_key_exists('active', $this->{$model}->_schema)) {
					$conditions = array_merge($conditions, array(
						$model.'.active' => 1,
					));
				}

				if ( ! empty($this->request->data[$model]['lft'])) {
					// don't allow a node as a child of itself or any of it's children
					// ...the universe will implode, and that's... bad
					$conditions = array_merge($conditions, array(
						$model.'.lft NOT BETWEEN ? AND ?' => array($this->request->data[$model]['lft'], $this->request->data[$model]['rght']),
					));
				}

				$parents = array(0 => 'No Parent') + $this->{$model}->generateTreeList($conditions);
				$this->set('parents', $parents);
				unset($conditions);
			}

			foreach (array('hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany') as $type) {
				// the model name might not be the key, but the value (if no options specified)
				$associatedModels = array( );
				foreach ($this->{$model}->{$type} as $key => $value) {
					if (is_int($key)) {
						$key = $value;
					}

					$associatedModels[] = $key;
				}

				foreach ($associatedModels as $assoc_model) {
					$var_name = Inflector::variable(Inflector::pluralize($assoc_model));

					if (array_key_exists($var_name, $this->viewVars)) {
						continue;
					}

					$conditions = array( );
					if ($active_only && $this->{$model}->{$assoc_model}->hasField('active')) {
						$conditions = array_merge($conditions, array(
							$this->{$model}->{$assoc_model}->alias.'.active' => 1,
						));
					}

					$items = array( );
					if (isset($this->{$model}->{$assoc_model}->actsAs['Tree'])
						|| $this->{$model}->{$assoc_model}->actsAs && in_array('Tree', $this->{$model}->{$assoc_model}->actsAs))
					{
						$items = $this->{$model}->{$assoc_model}->generateTreeList($conditions);
					}
					else {
						$order = array( );
						if ($this->{$model}->{$assoc_model}->hasField('sort')) {
							$order = $this->{$model}->{$assoc_model}->alias.'.sort';
						}
						elseif (is_array($this->{$model}->{$assoc_model}->displayField)) {
							$order = implode($this->{$model}->{$assoc_model}->displayField, ', ');
						}
						else {
							$order = $this->{$model}->{$assoc_model}->alias.'.'.$this->{$model}->{$assoc_model}->displayField;
						}
						$items = $this->{$model}->{$assoc_model}->find('list', compact('order', 'conditions'));
					}

					$this->set($var_name, $items);
				}
			}
		}

		$this->set('yesno', array(1 => 'Yes', 0 => 'No'));
		$this->set('noyes', array(0 => 'No', 1 => 'Yes'));

		// grab the games list in order of most played
		if (isset($this->viewVars['games'])) {
			$this->set('games', m('Tournament')->Game->find('list', array(
				'joins' => array(
					array(
						'table' => 'tournaments',
						'alias' => 'Tournament',
						'type' => 'LEFT',
						'conditions' => array(
							'Tournament.game_id = Game.id',
						),
					),
				),
				'order' => array(
					'COUNT(Tournament.id)' => 'DESC',
					'Game.name' => 'ASC',
				),
				'group' => array(
					'Game.id',
				),
			)));
		}
	}


	public function admin_filter_select($filter_item) {
		$this->Session->write('AdminFilter.item', $filter_item);

		// remove the 'Model.' portion
		$model_path = explode('.', $filter_item);
		unset($model_path[0]);

		$tree = false;
		$Model = $this->{$this->modelClass};
		while ($model_path) {
			$next = array_shift($model_path);

			if ('TREE' == $next) {
				$tree = true;
				break;
			}

			$Model = $Model->{$next};
		}

		if ( ! $tree) {
			$filter_selects = $Model->find('list', array(
				'order' => array(
					$Model->displayField => 'asc',
				),
			));
		}
		else {
			$filter_selects = $Model->generateTreeList( );
		}
		$this->set('filter_selects', $filter_selects);
		$this->Session->write('AdminFilter.selects', $filter_selects);

		$this->layout = 'ajax';
		$this->render('/Elements/admin_filter_select');
	}


	public function _set_filter_data( ) {
		// put models to ignore in here
		$used = array(
			$this->modelClass,
			'Group',
			'PlayerRanking',
			'User',
			'Forgot',
		);

		$related = $this->_get_related_models($this->{$this->modelClass}, $used);

		// flatten this array and sort
		$related = array_unique(array_flatten_keys($related));

		// fix the keys
		$related = array_flip($related);
		foreach ($related as & $value) {
			$value = 'Model.'.$value;
		}
		unset($value);

		// fix the values
		$related = array_flip($related);
		foreach ($related as $key => & $value) {
			if (false !== strpos($value, 'TREE')) {
				$value = substr($value, 0, -5).' Anywhere';
			}
		}
		unset($value);

		asort($related);

		// now grab all of the table fields and humanize
		$fields = array( );
		foreach ($this->{$this->modelClass}->_schema as $field => $null) {
			if (in_array($field, $this->filter_skip_fields)) {
				continue;
			}

			// skip the related models
			if ('_id' == substr($field, -3)) {
				// but only if it's actually a related model
				$related_model = 'Model.'.Inflector::classify(substr($field, 0, -3));
				if (in_array($related_model, array_keys($related))) {
					continue;
				}
			}

			$fields[$field] = Inflector::humanize($field);
		}

		$filter_items = array( );

		if ( ! empty($related)) {
			$filter_items['Related'] = $related;
		}

		if ( ! empty($fields)) {
			$filter_items['Fields'] = $fields;
		}

		$this->set('filter_items', $filter_items);

		if ($this->Session->check('AdminFilter.item')) {
			$this->set('filter_item', $this->Session->read('AdminFilter.item'));
		}

		if ($this->Session->check('AdminFilter.selects')) {
			$this->set('filter_selects', $this->Session->read('AdminFilter.selects'));
		}

		if ($this->Session->check('AdminFilter.select')) {
			$this->set('filter_select', $this->Session->read('AdminFilter.select'));
		}

		$filter_comparisons = array(
			'=' => 'is equal to',
			'<>' => 'is not equal to',
			'>' => 'is greater than',
			'>=' => 'is greater than or equal to',
			'<' => 'is less than',
			'<=' => 'is less than or equal to',
			'LIKE %' => 'contains',
			'NOT LIKE %' => 'does not contain',
			'IN' => 'has one of',
			'NOT IN' => 'does not have one of',
			'BETWEEN' => 'is between',
			'NULL' => 'has no value',
			'NOT NULL' => 'has a value',
			'= 1' => 'is true (yes)',
			'= 0' => 'is false (no)',
		);
		$this->set('filter_comparisons', $filter_comparisons);

		if ($this->Session->check('AdminFilter.compare')) {
			$this->set('filter_compare', $this->Session->read('AdminFilter.compare'));
		}

		if ($this->Session->check('AdminFilter.value')) {
			$this->set('filter_value', $this->Session->read('AdminFilter.value'));
		}
	}


	public function _use_filter_data( ) {
		if ($this->request->data) {
			if ('' == $this->request->data['AdminFilter']['item']) {
				$this->Session->delete('AdminFilter');
				return;
			}

			foreach ($this->request->data['AdminFilter'] as $key => $value) {
				$this->Session->write('AdminFilter.'.$key, $value);
			}
			$this->Session->write('AdminFilter.model', $this->modelClass);
		}

		if ($this->Session->check('AdminFilter')) {
			$admin_filter = $this->Session->read('AdminFilter');

			// clear our data if we switch pages
			if (empty($admin_filter['model']) || ($this->modelClass != $admin_filter['model'])) {
				$this->Session->delete('AdminFilter');
				return;
			}

			// we need an alias suffix so we don't collide with any containable joins we might have
			$suffix = '__F';

			// if it's a model, do the joins
			if (preg_match('/^Model\./', $admin_filter['item'])) {
				$joins = array( );
				$last = $this->modelClass; // no suffix here, this is the model we are joining to
				$prev = $this->{$last};
				$models = explode('.', $admin_filter['item']);
				unset($models[0]);

				foreach ($models as $model) {
					if ('TREE' == $model) {
						// grab all the children of the selected item
						$ids = Set::extract($prev->children($admin_filter['select']), '/'.$prev->alias.'/id');

						// include the selected item
						array_push($ids, $admin_filter['select']);

						// edit the conditions with the new value
						$admin_filter['select'] = $ids;

						break;
					}

					// find out how the models are related
					// and create our joins based on their relationships
					foreach (array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany') as $assoc) {
						if (in_array($model, array_keys($prev->{$assoc}))) {
							if ('hasAndBelongsToMany' == $assoc) {
								// we need to add the with table as well
								$joins[] = array(
									'table' => $prev->{$assoc}[$model]['joinTable'],
									'alias' => $prev->{$assoc}[$model]['with'].$suffix,
									'type' => 'inner',
									'conditions' => array(
										$last.'.id = '.$prev->{$assoc}[$model]['with'].$suffix.'.'.$prev->{$assoc}[$model]['foreignKey'],
									),
								);

								$joins[] = array(
									'table' => Inflector::tableize($prev->{$assoc}[$model]['className']),
									'alias' => $model.$suffix,
									'type' => 'inner',
									'conditions' => array(
										$prev->{$assoc}[$model]['with'].$suffix.'.'.$prev->{$assoc}[$model]['associationForeignKey'].' = '.$model.$suffix.'.id',
									),
								);
							}
							elseif ('belongsTo' == $assoc) {
								$joins[] = array(
									'table' => Inflector::tableize($prev->{$assoc}[$model]['className']),
									'alias' => $model.$suffix,
									'type' => 'inner',
									'conditions' => array(
										$last.'.'.$prev->{$assoc}[$model]['foreignKey'].' = '.$model.$suffix.'.id',
									),
								);
							}
							else {
								// do we need these?  (hasMany, hasOne)
								$joins[] = array(
									'table' => Inflector::tableize($prev->{$assoc}[$model]['className']),
									'alias' => $model.$suffix,
									'type' => 'inner',
									'conditions' => array(
										$last.'.id = '.$model.$suffix.'.'.$prev->{$assoc}[$model]['foreignKey'],
									),
								);
							}
						}
					}

					$last = $model.$suffix;
					$prev = $prev->{$model};
				}

				$this->paginate = array_merge($this->paginate, array(
					'joins' => $joins,
					'conditions' => array(
						$last.'.id' => $admin_filter['select'],
					),
				));
			}
			else { // it's a field name
				$condition = array( );
				switch ($admin_filter['compare']) {
					case '<>' : // no break
					case '>' : // no break
					case '>=' : // no break
					case '<' : // no break
					case '<=' :
						$conditions = array($this->modelClass.'.'.$admin_filter['item'].' '.$admin_filter['compare'] => $admin_filter['value']);
						break;

					case 'LIKE %' : // no break
					case 'NOT LIKE %' :
						$compare = substr($admin_filter['compare'], 0, -2);
						$conditions = array($this->modelClass.'.'.$admin_filter['item'].' '.$compare => '%'.$admin_filter['value'].'%');
						break;

					case 'IN' : // no break
					case 'NOT IN' :
						$values = array_trim($admin_filter['value']);
						$conditions = array($this->modelClass.'.'.$admin_filter['item'].' '.$admin_filter['compare'] => $values);
						break;

					case 'BETWEEN' :
						$values = array_trim($admin_filter['value']);
						$conditions = array($this->modelClass.'.'.$admin_filter['item'].' '.$admin_filter['compare'].' ? AND ?' => $values);
						break;

					case 'NULL' : // no break
					case 'NOT NULL' :
						$conditions = array($this->modelClass.'.'.$admin_filter['item'].' IS '.$admin_filter['compare']);
						break;

					case '= 1' :
						$admin_filter_test = true;
						// no break
					case '= 0' :
						$admin_filter_test = isset($admin_filter_test); // if it exists (from above), it's true; if not... false
						$conditions = array($this->modelClass.'.'.$admin_filter['item'] => $admin_filter_test);
						break;

					case '=' : // no break
					default :
						$conditions = array($this->modelClass.'.'.$admin_filter['item'] => $admin_filter['value']);
						break;
				}

				$this->paginate = array_merge($this->paginate, array(
					'conditions' => $conditions,
				));
			}
		}
	}


	// pass in the Model object
	public function _get_related_models($Model, $used = array( ), $relationships = array('hasMany', 'hasOne', 'belongsTo', 'hasAndBelongsToMany')) {
		// find all the models this model is related to
		$related = array( );

		foreach ($relationships as $relation) {
			foreach ($Model->{$relation} as $table => $options) {
				if (is_int($table)) {
					$table = $options;
				}

				// watch out for self-referencing loops
				if (in_array($table.'.'.$Model->name, $used)) {
					continue;
				}

				if (in_array($table, $used) || in_array($Model->name.'.'.$table, $used)) {
					continue;
				}

				$used[] = $Model->name.'.'.$table;
//				$related[$table] = $this->_get_related_models($Model->{$table}, $used, $relationships);
				$related[$table] = array( );

				// check and see if it's a tree
				if (in_array('Tree', $Model->{$table}->actsAs) || array_key_exists('Tree', $Model->{$table}->actsAs)) {
					$related[$table.'.TREE'] = array( );
				}
			}
		}

		return $related;
	}


	public function _fix_add_sort($models, $extra_fields = array('sort'), $required_fields = array('id')) {
		if ($this->request->is('post') || $this->request->is('put')) {
			foreach ($models as $model) {
				unset($this->request->data[$model]['NNN']);
				$habtm = $this->{$this->modelClass}->hasAndBelongsToMany[$model];

				foreach ($this->request->data[$model] as $key => $value) {
					foreach ($required_fields as $field) {
						if (empty($value[$field])) {
							unset($this->request->data[$model][$key]);
							continue 2;
						}
					}

					$new = array($habtm['with'] => array(
						$habtm['associationForeignKey'] => $value['id'],
					));

					foreach ($extra_fields as $field_name) {
						$new[$habtm['with']][$field] = $value[$field];
					}

					$this->request->data[$model][$key] = $new;
				}

				$this->request->data[$model] = array($model => $this->request->data[$model]);
			}
		}
	}


	public function _create_token($namespace = null) {
		$sessionvar = 'token';
		if ( ! empty($namespace)) {
			$sessionvar = $namespace.'.token';
		}

		$token = sha1(uniqid(md5(microtime(true)), true));

		$this->Session->write($sessionvar, $token);

		return $token;
	}


	public function _check_token($token, $namespace = null) {
		$sessionvar = 'token';
		if ( ! empty($namespace)) {
			$sessionvar = $namespace.'.token';
		}

		if ( ! $this->Session->check($sessionvar)) {
			return false;
		}

		return (0 === strcmp((string) $token, (string) $this->Session->read($sessionvar)));
	}


	// prepare the data that may have come in through auto complete fields
	public function _prepare_data($model, $data) {
		// put models that have numeric values in here
		// so we don't confuse them with IDs
		$numeric = array(
			'Zip',
			'ZipCode',
		);

		// always test zip codes, for obvious reasons...
		// ...the obvious reason being that it looks like an ID
		// and will get returned here
		if (is_numeric($data) && ! in_array($model, $numeric)) {
			return (int) $data;
		}

		if ('' === trim($data)) {
			return false;
		}

		return m($model)->prepare_data($data);
	}


	function do_log($e) {
		foreach ($e->getTrace( ) as $item) {
			if (false === strpos($item['file'], DS.'Cake'.DS)) {
				$location = ' on line '.$item['line'].' of '.$item['file'].': ';
				break;
			}
		}
		$this->log(get_class($e).' #'.$e->getCode( ).$location.$e->getMessage( ), 'debug');
	}

}

