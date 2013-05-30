<?php

	Router::connect('/', array('controller' => 'home', 'action' => 'index'));
	Router::connect('/about', array('controller' => 'pages', 'action' => 'display', 'about'));

	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/forgot', array('controller' => 'forgots', 'action' => 'index'));
	Router::connect('/forgot/:token', array('controller' => 'forgots', 'action' => 'index'));

	Router::connect('/profile', array('controller' => 'users', 'action' => 'edit'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));

	Router::connect('/admin', array('prefix' => 'admin', 'admin' => true, 'controller' => 'admin'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes( );

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';

