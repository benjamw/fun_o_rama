
	public function <?php echo $admin ?>index( ) {
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->set('<?php echo $pluralName ?>', $this->paginate( ));
	}


	public function <?php echo $admin ?>view($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;

		if ( ! $this-><?php echo $currentModelName; ?>->exists( )) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}

		$this->set('<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->read(null, $id));
	}


	public function <?php echo $admin ?>add( ) {
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create( );
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession) { ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
<?php } else { ?>
				$this->flash(__('<?php echo ucfirst(strtolower($currentModelName)); ?> saved.'), array('action' => 'index'));
<?php } ?>
			}
<?php if ($wannaUseSession) { ?>
			else {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'flash_error');
			}
<?php } ?>
		}

		$this->_setSelects( );
	}


	public function <?php echo $admin; ?>edit($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;

		if ( ! $this-><?php echo $currentModelName; ?>->exists( )) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession) { ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
<?php } else { ?>
				$this->flash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.'), array('action' => 'index'));
<?php } ?>
			}
<?php if ($wannaUseSession) { ?>
			else {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'flash_error');
			}
<?php } ?>
		}
		else {
			$this->request->data = $this-><?php echo $currentModelName; ?>->read(null, $id);
		}

		$this->_setSelects( );
	}


	public function <?php echo $admin; ?>delete($id = null) {
		if ( ! $this->request->is('post')) {
			throw new MethodNotAllowedException( );
		}

		$this-><?php echo $currentModelName; ?>->id = $id;

		if ( ! $this-><?php echo $currentModelName; ?>->exists( )) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}

		if ($this-><?php echo $currentModelName; ?>->delete( )) {
<?php if ($wannaUseSession) { ?>
			$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), 'flash_success');
			return $this->redirect(array('action' => 'index'));
<?php } else { ?>
			$this->flash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), array('action' => 'index'));
<?php } ?>
		}

<?php if ($wannaUseSession) { ?>
		$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), 'flash_error');
<?php } else { ?>
		$this->flash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), array('action' => 'index'));
<?php } ?>

		return $this->redirect(array('action' => 'index'));
	}

