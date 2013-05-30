<?php

App::uses('AppModel', 'Model');

class Setting extends AppModel {

	public $displayField = 'name';
	public $validateFile = array( );

	public $_settings = array( );

	public function load( ) {
		$data = $this->find('all');

		$this->_settings = array( );
		foreach ($data as $datum) {
			$this->_settings[$datum['Setting']['name']] = ( ! empty($datum['Setting']['value']) ? $datum['Setting']['value'] : $datum['Setting']['default']);
		}

		return $this->_settings;
	}

	public function grab($value) {
		if (empty($this->_settings)) {
			$this->load( );
		}

		if (isset($this->_settings[$value])) {
			return $this->_settings[$value];
		}
		else {
			return null;
		}
	}

	public function generateUniqueFilename($fileName, $path = 'files/setting') {
		$path = empty($path) ? WWW_ROOT.'files'.DS : WWW_ROOT.$path.DS;

		// remove anything that isn't a word character, dot, space, plus, underscore, or dash
		$fileName = preg_replace('/[^a-z0-9. +_-]+/i', '', $fileName);
		// and replace all spaces, pluses, and dashes with an underscore ( _ )
		$fileName = preg_replace('/[\\s+-]+/', '_', $fileName);

		$newFileName = $fileName;

		while (file_exists($path.DS.$newFileName)) {
			$newFileName = substr_replace($fileName, '_'.substr(md5(uniqid(rand( ), true)), 0, 5), strrpos($fileName, '.'), 0);
		}

		return $newFileName;
	}

	// NOTE: not a typical file upload
	public function handleFileUpload($fileData, $fileName, $path = 'files/setting') {
		$error = false;
		$path = empty($path) ? WWW_ROOT.'files'.DS : WWW_ROOT.$path.DS;

		// make sure our folder exists
		$Folder = new Folder(ROOT);
		$Folder->create($path);

		// get file extension
		$ext = substr($fileData['name'], strrpos($fileData['name'], '.') + 1);

		// if size is provided for validation check with that size. else compare the size with INI file
		if ((isset($this->validateFile['size']) && $this->validateFile['size'] && $fileData['size'] > $this->validateFile['size']) || $fileData['error'] == UPLOAD_ERR_INI_SIZE) {
			$error = 'File is too large to upload';
		}
		elseif (isset($this->validateFile['type']) && $this->validateFile['type'] && (false === strpos(strtolower($this->validateFile['type']), strtolower($ext)))) {
			// file type is not the one we are going to accept. Error!!
			$error = 'Invalid file type';
		}
		else {
			// data looks OK at this stage. Let's proceed.
			if ($fileData['error'] == UPLOAD_ERR_OK) {
				// oops!! File size is zero. Error!
				if ($fileData['size'] == 0) {
					$error = 'Zero size file found.';
				}
				else {
					if (is_uploaded_file($fileData['tmp_name'])) {
						// finally we can upload file now. Let's do it and return without errors if success in moving.
						if ( ! move_uploaded_file($fileData['tmp_name'], $path.$fileName)) {
							$error = 'Error storing file';
						}
					}
					else {
						$error = 'File went missing';
					}
				}
			}
		}

		return $error;
	}

	public function deleteMovedFile($fileName, $path = 'files/setting') {
		$path = WWW_ROOT.$path.DS;

		if ( ! $fileName || ! is_file($path.$fileName)) {
			return true;
		}

		if (unlink($path.$fileName)) {
			return true;
		}

		return false;
	}

	// NOTE: not a typical file upload
	public function afterFind($results, $primary = false) {
		$results = parent::afterFind($results, $primary);

		foreach ($results as $key => $value) {
			if ('file' == $value[$this->alias]['type']) {
				$results[$key][$this->alias]['value'] = '/files/setting/'.$value[$this->alias]['value'];
			}
		}

		return $results;
	}

}

