<?php

App::uses('AppController', 'Controller');

class AdminController extends AppController {

	public $uses = array( );

	public function index( ) {
		return $this->redirect(array('admin' => true, 'prefix' => 'admin', 'controller' => 'players', 'action' => 'index'));
	}

	public function admin_index( ) {
		$this->index( );
	}

}

