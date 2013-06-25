<?php

App::uses('AppHelper', 'View/Helper');
App::uses('Folder', 'Utility');
$path = App::path('Vendor', 'Identicon');

require_once $path[0].'Identicon.php';

class IdenticonHelper extends AppHelper {

	private $blocks = 4;
	private $size = 50;
	private $Identicon;

	public function __construct(View $view, $settings = array( )) {
		parent::__construct($view, $settings);

		if ( ! empty($settings['size'])) {
			$this->size = (int) $settings['size'];
		}

		if ( ! empty($settings['blocks'])) {
			$this->blocks = (int) $settings['blocks'];
		}

		$folder = new Folder(IMAGES.'identicons', true);

		$this->Identicon = new Identicon(IMAGES, $this->url('/'.IMAGES_URL, true), 'identicons', $this->blocks);
	}

	public function create($seed, $size = null) {
		if ( ! $size) {
			$size = $this->size;
		}

		return $this->Identicon->build(md5($seed), '', true, $size);
	}

}

