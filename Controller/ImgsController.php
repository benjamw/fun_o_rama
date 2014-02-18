<?php

App::uses('AppController', 'Controller');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');

class ImgsController extends AppController {

	public $uses = array( );
	public $allowed = array(
//			'image/bmp',
			'image/gif',
			'image/jpeg',
			'image/pjpeg', // yay, IE is retarded
			'image/png',
			'image/x-png', // yay, IE is retarded
//			'image/tiff',
		);

	public function add( ) {
		Configure::write('debug', 0);

		if (empty($this->params['url']['CKEditorFuncNum'])) {
			return $this->redirect('/');
		}

		$path = IMAGES.'uploads'.DS;
		$callback = $this->params['url']['CKEditorFuncNum'];

		// make sure our folder exists
		$Folder = new Folder(ROOT);
		$Folder->create($path);

		if ( ! empty($this->params['form']['upload']) && is_uploaded_file($this->params['form']['upload']['tmp_name'])) {
			$name = $this->params['form']['upload']['name'];
			$ext = substr($name, strrpos($name, '.'));

			// make sure it's an allowed image
			if ( ! in_array($this->params['form']['upload']['type'], $this->allowed)) {
				$msg = 'ERROR: Image must be a JPG, PNG, or GIF';
				echo '<html><body><script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$callback.',"","'.$msg.'");</script></body></html>';
				exit;
			}

			// make sure the name is unique
			$name = $orig = Inflector::slug(substr($name, 0, strrpos($name, '.'))).$ext;
			while (file_exists($path.$name)) {
				// stick a unique identifier in the file name
				$name = substr_replace($orig, '_'.substr(md5(uniqid(microtime( ), true)), -5), strrpos($orig, '.'), 0);
			}

			if (move_uploaded_file($this->params['form']['upload']['tmp_name'], $path.$name)) {
				$url = Router::url('/'.IMAGES_URL.'uploads/'.$name);
				$msg = 'File Uploaded';
			}
			else {
				$url = '';
				$msg = 'ERROR UPLOADING FILE';
			}

			echo '<html><body><script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$callback.',"'.$url.'","'.$msg.'");</script></body></html>';
			exit;
		}

		return $this->redirect('/');
	}

	public function browse( ) {
		if (empty($this->params['url']['CKEditorFuncNum'])) {
			return $this->redirect('/');
		}
		$this->set('callback', $this->params['url']['CKEditorFuncNum']);

		$this->layout = 'simple';
		$this->set('title', 'Image Browser');

		$path = IMAGES.'uploads'.DS;

		// open the folder
		$dh = opendir($path);

		$filelist = array( );
		while (false !== ($file = readdir($dh))) {
			if ('.' !== $file[0]) { // scanning for bsig_ files only
				$filelist[Router::url('/'.IMAGES_URL.'uploads/'.$file)] = $file;
			}
		}

		$this->set('filelist', $filelist);

		closedir($dh);
		Configure::write('debug', 0);
	}

}

