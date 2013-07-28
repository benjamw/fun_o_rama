<?php

/*
 * Image for cakePHP
 * comments, bug reports are welcome skie AT mail DOT ru
 * @author Yevgeny Tomenko aka SkieDr
 * @version 1.0.0.5
 * @website http://bakery.cakephp.org/articles/view/actas-image-column-behavior

	public $actsAs = array(
		'Image' => array(
			'fields' => array(
				'[fieldname]' => array(
					'resize' => array(
						'width' => 100,
						'height' => 100,
					) OR null for no resize,
					'thumbnail' => array(
						'prefix' => 'thumb',
						'create' => false,
						'width' => 100,
						'height' => 100,
						'aspect' => true,
						'crop' => false,
						'allow_enlarge' => true,
					) OR null for no thumbnail,
					'versions' => array(
						array(
							'prefix' => '[prefixname]',
							'width' => 100,
							'height' => 200,
							'aspect' => true,
							'crop' => true,
							'allow_enlarge' => true,
						),
						array( ... ),
					),
				),
			),
		),
	);


files stored in structure
/images/{models}/{$id}/{field}.ext

// put this in your model to fix any failed afterFinds

	function _fix_image_array($results) {
		return $this->Behaviors->Image->afterFind($this, $results);
	}


 */

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class ImageBehavior extends ModelBehavior {

	public $settings = null;


	public function setup(Model $model, $config = array( ))
	{
		$this->imageSetup($model, $config);
	}


	public function imageSetup(Model $model, $config = array( ))
	{
		$settings = Set::merge(array(
				'baseDir'=> '',
			), $config);

		if ( ! isset($settings['fields'])) {
			$settings['fields'] = array( );
		}

		$fields = array( );
		foreach($settings['fields'] as $key => $value) {
			$field = (is_numeric($key) ? $value : $key);
			$conf = (is_numeric($key) ? array( ) : ((is_array($value) ? $value : array( ))));
			$conf = Set::merge(array(
					'thumbnail' => array(
						'prefix' => 'thumb',
						'create' => false,
						'width' => '100',
						'height' => '100',
						'aspect' => true,
						'crop' => false,
						'allow_enlarge' => true,
					),
					'resize' => null, // array('width'=>'100', 'height'=>'100'),
					'versions' => array( ),
				), $conf);
			foreach ($conf['versions'] as $id => $version) {
				$new_version = Set::merge(array('aspect' => true, 'crop' => false, 'allow_enlarge' => false), $version);

				if ($new_version['crop']) {
					$new_version['aspect'] = false;
				}

				$conf['versions'][$id] = $new_version;

				if ('thumb' == $version['prefix']) {
					$conf['thumbnail'] = false;
				}
			}

			if (is_array($conf['resize'])) {
				$conf['resize']['aspect'] = isset($conf['resize']['aspect']) ? $conf['resize']['aspect'] : true;
				$conf['resize']['allow_enlarge'] = isset($conf['resize']['allow_enlarge']) ? $conf['resize']['allow_enlarge'] : false;
			}

			$fields[$field] = $conf;
		}
		$settings['fields'] = $fields;

		$this->settings[$model->alias] = $settings;
	}


	public function beforeValidate(Model $model)
	{
		foreach ($this->settings[$model->alias]['fields'] as $key => $setting) {
			if ( ! empty($model->data[$model->alias][$key]['type'])) {
				$ext = $this->decodeContent($model->data[$model->alias][$key]['type']);
				if ( ! preg_match('/jpg|jpeg|png|gif/i', $ext)) {
					$model->invalidate($key, 'Please provide a valid image (jpg, jpeg, png, gif)');
					return false;
				}
			}
		}

		return true;
	}


	/**
	 * Before save method. Called before all saves
	 *
	 * Overriden to transparently manage setting the item position to the end of the list
	 *
	 * @param AppModel $model
	 * @return boolean True to continue, false to abort the save
	 */
	public function beforeSave(Model $model)
	{
		extract($this->settings[$model->alias]);

		$tempData = array( );
		foreach ($fields as $key => $value) {
			$field = (is_numeric($key) ? $value : $key);
			if ( ! empty($model->data[$model->alias]['delete_'.$field])) {
				$this->__deleteFile($model, $field);
				unset($model->data[$model->alias]['delete_'.$field]);
				$model->data[$model->alias][$field] = '';
			}
			elseif (isset($model->data[$model->alias][$field])) {
				if ($this->__isUploadFile($model->data[$model->alias][$field])) {
					$tempData[$field] = $model->data[$model->alias][$field];
					$model->data[$model->alias][$field] = $this->__getContent($model->data[$model->alias][$field]);
				}
				else {
					unset($model->data[$model->alias][$field]);
				}
			}
		}

		$this->runtime[$model->alias]['beforeSave'] = $tempData;
		return true;
	}


	public function afterSave(Model $model, $created)
	{
		extract($this->settings[$model->alias]);

		$tempData = $this->runtime[$model->alias]['beforeSave'];
		unset($this->runtime[$model->alias]['beforeSave']);

		foreach($tempData as $field => $value) {
			$this->__saveFile($model, $field, $value);
		}

		return true;
	}


	// recursively parses the array looking for and updating all relevant data
	protected function _parse_result_array(Model $model, $results) {
		extract($this->settings[$model->alias]);

		// look for the model in this array
		if ( ! empty($results[$model->alias]) && is_array($results[$model->alias])) {
			if (array_key_exists(0, $results[$model->alias])) {
				foreach ($results[$model->alias] as $i => $result) {
					foreach ($fields as $field => $fieldParams) {
						if ( ! empty($result[$field]) && ! is_array($result[$field])) {
							$value = $result[$field];
							$results[$model->alias][$i][$field] = $this->__getParams($model, $field, $value, $fieldParams, $result);
						}
					}
				}
			}
			else {
				foreach ($fields as $field => $fieldParams) {
					if ( ! empty($results[$model->alias][$field]) && ! is_array($results[$model->alias][$field])) {
						$value = $results[$model->alias][$field];
						$results[$model->alias][$field] = $this->__getParams($model, $field, $value, $fieldParams, $results[$model->alias]);
					}
				}
			}
		}
		else {
			if (is_array($results)) {
				$found = false;
				foreach ($fields as $field => $fieldParams) {
					if ( ! empty($results[$field]) && ! is_array($results[$field])) {
						$value = $results[$field];
						$results[$field] = $this->__getParams($model, $field, $value, $fieldParams, $results);
						$found = true;
					}
				}

				if ( ! $found) {
					foreach ($results as $key => $result) {
						// recurse into the array if we need to
						$results[$key] = $this->_parse_result_array($model, $result);
					}
				}
			}
		}

		return $results;
	}


	public function afterFind(Model $model, $results, $primary = false)
	{
		$results = $this->_parse_result_array($model, $results);
		return $results;
	}


	protected function __getParams(Model $model, $field, $value, $fieldParams, $record)
	{
		extract($this->settings[$model->alias]);
		$result = array( );
		if ($value != '') {
			$folderName = $this->__getFolder($model, $record);
			$ext = $this->decodeContent($value);
			$fileName = $field .'.'. $ext;
			$result['path'] = $folderName.$fileName;

			$thumb = $fields[$field]['thumbnail'];
			if ($thumb['create']) {
				$result['thumb'] = $folderName.$this->__getPrefix($thumb).'_'.$fileName;
			}

			foreach($fields[$field]['versions'] as $version) {
				$result[$this->__getPrefix($version)] = $folderName.$this->__getPrefix($version).'_'.$fileName;
			}
		}

		return $result;
	}


	/**
	 * Before delete method. Called before all deletes
	 *
	 * Will delete the current item from list and update position of all items after one
	 *
	 * @param AppModel $model
	 * @return boolean True to continue, false to abort the delete
	 */
	public function beforeDelete(Model $model, $cascade = true)
	{
		$this->runtime[$model->alias]['ignoreUserAbort'] = ignore_user_abort( );
		@ignore_user_abort(true);
		return true;
	}


	public function afterDelete(Model $model)
	{
		extract($this->settings[$model->alias]);

		foreach ($fields as $field => $fieldParams) {
			$this->__deleteFile($model, $field);
		}

		@ignore_user_abort((bool) $this->runtime[$model->alias]['ignoreUserAbort']);
		unset($this->runtime[$model->alias]['ignoreUserAbort']);
		return true;
	}


	protected function __isUploadFile($file)
	{
		if (!isset($file['tmp_name'])) return false;
		return (file_exists($file['tmp_name']) && $file['error']==0);
	}


	protected function __getContent($file)
	{
		return $file['type'];
	}


	public function decodeContent($content)
	{
		$contentsMaping=array(
			"image/gif" => "gif",
			"image/jpeg" => "jpg",
			"image/pjpeg" => "jpg",
			"image/x-png" => "png",
			"image/jpg" => "jpg",
			"image/png" => "png",
			"application/x-shockwave-flash" => "swf",
			"application/pdf" => "pdf",
			"application/pgp-signature" => "sig",
			"application/futuresplash" => "spl",
			"application/msword" => "doc",
			"application/postscript" => "ps",
			"application/x-bittorrent" => "torrent",
			"application/x-dvi" => "dvi",
			"application/x-gzip" => "gz",
			"application/x-ns-proxy-autoconfig" => "pac",
			"application/x-shockwave-flash" => "swf",
			"application/x-tgz" => "tar.gz",
			"application/x-tar" => "tar",
			"application/zip" => "zip",
			"audio/mpeg" => "mp3",
			"audio/x-mpegurl" => "m3u",
			"audio/x-ms-wma" => "wma",
			"audio/x-ms-wax" => "wax",
			"audio/x-wav" => "wav",
			"image/x-xbitmap" => "xbm",
			"image/x-xpixmap" => "xpm",
			"image/x-xwindowdump" => "xwd",
			"text/css" => "css",
			"text/html" => "html",
			"text/javascript" => "js",
			"text/plain" => "txt",
			"text/xml" => "xml",
			"video/mpeg" => "mpeg",
			"video/quicktime" => "mov",
			"video/x-msvideo" => "avi",
			"video/x-ms-asf" => "asf",
			"video/x-ms-wmv" => "wmv"
		);

		if (isset($contentsMaping[$content])) {
			return $contentsMaping[$content];
		}
		else {
			return $content;
		}
	}


	protected function __getFolder(Model $model, $record)
	{
		extract($this->settings[$model->alias]);
		return $baseDir.Inflector::camelize($model->name).'/'.$record[$model->primaryKey].'/';
	}


	protected function __getFullFolder(Model $model, $field)
	{
		extract($this->settings[$model->alias]);
		return WWW_ROOT.IMAGES_URL.$baseDir.DS.Inflector::camelize($model->name).DS.$model->id.DS;
	}


	protected function __saveFile(Model $model, $field, $fileData)
	{
		extract($this->settings[$model->alias]);
		$folderName = $this->__getFullFolder($model, $field);
		$ext = $this->decodeContent($this->__getContent($fileData));
		$fileName = $field.'.'.$ext;

		$folder = new Folder($path = $folderName, $create = true, $mode = 0777);

		$files = $folder->find($fileName);

		$file = new File($folder->pwd( ).DS.$fileName);

		$fileExists = ($file !== false);
		if ($fileExists) {
			@$file->delete( );
		}

		$this->_generate_images($model, $fileName, $fileData['tmp_name']);
	}


	protected function __deleteFile(Model $model, $field)
	{
		$folderPath=$this->__getFullFolder($model, $field);
		$folder = new Folder($path = $folderPath, $create = false);
		if ($folder!==false) {
			@$folder->delete($folder->pwd( ));
		}
	}


	protected function __getPrefix($fieldParams)
	{
		if (isset($fieldParams['prefix'])) {
			return $fieldParams['prefix'];
		}
		else {
			return $fieldParams['width'].'x'.$fieldParams['height'];
		}
	}


	/**
	 * Automatically resizes an image and saves it
	 *
	 * @param string $path Path to the image file, relative to the webroot/img/ directory.
	 * @param integer $width Image of returned image
	 * @param integer $height Height of returned image
	 * @param boolean $aspect Maintain aspect ratio (default: true)
	 * @param array    $htmlAttributes Array of HTML attributes.
	 * @param boolean $return Whether this method should return a value or output it. This overrides AUTO_OUTPUT.
	 * @return void
	 * @access public
	 */
	protected function __resize($folder, $originalName, $newName, $field, $fieldParams)
	{
		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type
		$fullpath = $folder;

		$url = $folder.DS.$originalName;

		if ( ! ($size = getimagesize($url))) {
			return; // image doesn't exist
		}

		$width_set = $height_set = true;
		if ( ! isset($fieldParams['height']) || ! $fieldParams['height']) {
			$height_set = false;
			$fieldParams['height'] = (int) round(($fieldParams['width'] / $size[0]) * $size[1]);
		}
		elseif ( ! isset($fieldParams['width']) || ! $fieldParams['width']) {
			$width_set = false;
			$fieldParams['width'] = (int) round(($fieldParams['height'] / $size[1]) * $size[0]);
		}

		$width = $fieldParams['width'];
		$height = $fieldParams['height'];
		if (false === $fieldParams['allow_enlarge']) { // don't enlarge image
			if (($width > $size[0]) || ($height > $size[1])) {
				$width = $size[0];
				$height = $size[1];
			}
		}
		else {
			if ( ! isset($fieldParams['crop']) || ! $fieldParams['crop']) {
				if (($size[1] / $height) > ($size[0] / $width) && ! $width_set) {
					$width = (int) round(($size[0] / $size[1]) * $height);
				}
				elseif ( ! $height_set) {
					$height = (int) round($width / ($size[0] / $size[1]));
				}
			}
		}

		$cachefile = $fullpath.DS.$newName;  // location on server

		if (file_exists($cachefile)) {
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (@filemtime($cachefile) < @filemtime($url)) { // check if up to date
				$cached = false;
			}
		}
		else {
			$cached = false;
		}

		if ( ! $cached) {
			$resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height || ($fieldParams['allow_enlarge']===false));
		}
		else {
			$resize = false;
		}

		if (isset($fieldParams['crop']) && $fieldParams['crop']) {
			$resize = true;

			if (($size[0] / $width) == ($size[1] / $height)) {
				$src_w = (int) $size[0];
				$src_h = (int) $size[1];
				$start_x = 0;
				$start_y = 0;

				// if the image is uploaded at the right size, don't do anything to it
				if (($src_w == $width) && ($src_h == $height)) {
					$resize = false;
				}
			}
			elseif (($size[0] / $width) > ($size[1] / $height)) {
				$src_h = (int) $size[1];
				$src_w = (int) floor(($size[1] / $height) * $width);
				$start_y = 0;
				$start_x = (int) floor(($size[0] - $src_w) / 2);
			}
			else {
				$src_w = (int) $size[0];
				$src_h = (int) floor(($size[0] / $width) * $height);
				$start_x = 0;
				$start_y = (int) floor(($size[1] - $src_h) / 2);
			}
		}
		else {
			$start_x = 0;
			$start_y = 0;
			$src_w = (int) $size[0];
			$src_h = (int) $size[1];
		}

		if ($resize) {
			$image = call_user_func('imagecreatefrom'.$types[$size[2]], $url);
			$temp = imagecreatetruecolor($width, $height);

			imagealphablending($temp, false);

			$transparent = imagecolorallocatealpha($temp, 0, 0, 0, 127);
			imagefill($temp, 0, 0, $transparent);

			imagecopyresampled($temp, $image, 0, 0, $start_x, $start_y, $width, $height, $src_w, $src_h);

			imagesavealpha($temp, true);

			call_user_func('image'.$types[$size[2]], $temp, $cachefile);
			imagedestroy($image);
			imagedestroy($temp);
		}
		else {
			// we still want our image w/prefix, even if it is the same size
			copy($url, $cachefile);
		}
	}

	public function crop(Model $model, $field, $start_x, $start_y, $width, $height, $src_w = null, $src_h = null) {
		// used to determine image type
		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp");

		// rename the orignal file so we don't lose it
		$folder = $this->__getFullFolder($model, $field);

		// open the folder so we can look at the images
		$dh = opendir($folder);

		while (false !== ($file = readdir($dh))) {
			if ( ! preg_match('/^\./i', $file)) { // scanning for visible files only
				break; // we have the file name
			}
		}
		closedir($dh);

		$ext = pathinfo($folder.DS.$file, PATHINFO_EXTENSION);

		$main_image = $folder.DS.$field.'.'.$ext;
		if ( ! file_exists($folder.DS.'__orig__'.$field.'.'.$ext)) {
			copy($main_image, $folder.DS.'__orig__'.$field.'.'.$ext);
		}

		$size = getimagesize($main_image);
		$act_w = (int) $size[0];
		$act_h = (int) $size[1];

		if (empty($src_w) && is_numeric($src_h)) {
			$src_w = ($src_h / $act_h) * $act_w;
		}
		elseif (empty($src_h) && is_numeric($src_w)) {
			$src_h = ($src_w / $act_w) * $act_h;
		}
		elseif (is_numeric($src_w) && is_numeric($src_h)) {
			// do nothing
		}
		else {
			$src_w = $act_w;
			$src_h = $act_h;
		}

		$src_w = (int) round($src_w);
		$src_h = (int) round($src_h);

		$ratio_w = $act_w / $src_w;
		$ratio_h = $act_h / $src_h;

		// adjust our measurements as needed
		$start_x = (int) round($start_x * $ratio_w);
		$start_y = (int) round($start_y * $ratio_h);
		$width = (int) round($width * $ratio_w);
		$height = (int) round($height * $ratio_h);

		// make our adjustments to the main image
		$image = call_user_func('imagecreatefrom'.$types[$size[2]], $main_image);

		$temp = imagecreatetruecolor($width, $height);

		imagealphablending($temp, false);

		$transparent = imagecolorallocatealpha($temp, 0, 0, 0, 127);
		imagefill($temp, 0, 0, $transparent);

		imagecopyresampled($temp, $image, 0, 0, $start_x, $start_y, $width, $height, $width, $height);

		imagesavealpha($temp, true);

		call_user_func('image'.$types[$size[2]], $temp, $main_image);
		imagedestroy($image);
		imagedestroy($temp);

		// now we recreate all our other images from this new cropped image
		$this->_generate_images($model, $field.'.'.$ext, $main_image);
	}

	protected function _generate_images(Model $model, $fileName, $base_image) {
		$field = explode('.', $fileName);
		$field = $field[0];
		extract($this->settings[$model->alias]);
		$folderName = $this->__getFullFolder($model, $field);
		$folder = new Folder($path = $folderName, $create = true, $mode = 0777);

		if (isset($fields[$field]['resize']['width']) || isset($fields[$field]['resize']['height'])) {
			$file = $folder->pwd( ).DS.'tmp_'.$fileName;
			copy($base_image, $file);
			$this->__resize($folder->pwd( ), 'tmp_'.$fileName, $fileName, $field, $fields[$field]['resize']);
			@unlink($file);
		}
		else {
			$file = $folder->pwd( ).DS.$fileName;
			copy($base_image, $file);
		}

		if ($fields[$field]['thumbnail']['create']) {
			$fieldParams = $fields[$field]['thumbnail'];
			$newFile = $this->__getPrefix($fieldParams).'_'.basename($fileName);
			$this->__resize($folder->pwd( ), $fileName, $newFile, $field, $fieldParams);
		}

		foreach ($fields[$field]['versions'] as $version) {
			$newFile = $this->__getPrefix($version).'_'.basename($fileName);
			$this->__resize($folder->pwd( ), $fileName, $newFile, $field, $version);
		}
	}

}

