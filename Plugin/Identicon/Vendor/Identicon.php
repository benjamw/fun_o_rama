<?php

// blatantly stolen from wp_identicon

class Identicon {
	var $options;
	var $blocks;
	var $shapes;
	var $rotatable;
	var $square;
	var $im;
	var $colors;
	var $size;
	var $blocksize;
	var $quarter;
	var $half;
	var $diagonal;
	var $halfdiag;
	var $semidiag; // 30/60
	var $transparent = false;
	var $centers;
	var $shapes_mat;
	var $symmetric_num;
	var $rot_mat;
	var $invert_mat;
	var $rotations;
	var $cache_dir;
	var $root;
	var $webroot;

	//constructor
	function __construct($root, $webroot, $cache_dir, $blocks = '') {
		$this->root = $root;
		$this->webroot = $webroot;
		$this->cache_dir = $cache_dir.'/';

		$default_array = array(
			'size' => 50,
			'backr' => array(255, 255),
			'backg' => array(255, 255),
			'backb' => array(255, 255),
			'forer' => array(1, 255),
			'foreg' => array(1, 255),
			'foreb' => array(1, 255),
			'squares' => 4,
			'autoadd' => 1,
			'gravatar' => 0,
			'grey' => 0
		);

		$this->options = $default_array;

		if ($blocks) {
			$this->blocks = $blocks;
		}
		else {
			$this->blocks = $this->options['squares'];
		}

		$this->blocksize = 1000;
		$this->size = $this->blocks * $this->blocksize;
		$this->quarter = $this->blocksize / 4;
		$this->half = $this->blocksize / 2;
		$this->diagonal = sqrt($this->half * $this->half + $this->half * $this->half);
		$this->halfdiag = $this->diagonal / 2;
		$this->semidiag = $this->half / cos(M_PI / 6);

		$this->shapes = array(
			array( // full block
				array(
					array(45, $this->diagonal), array(135, $this->diagonal), array(225, $this->diagonal), array(315, $this->diagonal),
				),
				'symmetry' => 4,
			),
			array( // rectangular half block
				array(
					array(90, $this->half), array(135, $this->diagonal), array(225, $this->diagonal), array(270, $this->half),
				),
			),
			array( // diagonal half block
				array(
					array(45, $this->diagonal), array(135, $this->diagonal), array(225, $this->diagonal),
				),
			),
			array( // triangle
				array(
					array(270, $this->half), array(45, $this->diagonal), array(135, $this->diagonal),
				),
			),
			array( // diamond
				array(
					array(0, $this->half), array(90, $this->half), array(180, $this->half), array(270, $this->half),
				),
				'symmetry' => 4,
			),
			array( // stretched diamond
				array(
					array(0, $this->half), array(135, $this->diagonal), array(270, $this->half), array(315, $this->diagonal),
				),
			),
			array( // triple triangle
				array(
					array(0, $this->quarter), array(90, $this->half), array(180, $this->quarter),
				),
				array(
					array(0, $this->quarter), array(315, $this->diagonal), array(270, $this->half),
				),
				array(
					array(270, $this->half), array(180, $this->quarter), array(225, $this->diagonal),
				),
			),
			array( // pointer
				array(
					array(0, $this->half), array(135, $this->diagonal), array(270, $this->half),
				),
			),
			array( // center square
				array(
					array(45, $this->halfdiag), array(135, $this->halfdiag), array(225, $this->halfdiag), array(315, $this->halfdiag),
				),
				'symmetry' => 4,
			),
			array( // double triangle diagonal
				array(
					array(180, $this->half), array(225, $this->diagonal), array(0, 0),
				),
				array(
					array(45, $this->diagonal), array(90, $this->half), array(0, 0),
				),
			),
			array( // diagonal square
				array(
					array(90, $this->half), array(135, $this->diagonal), array(180, $this->half), array(0, 0),
				),
			),
			array( // quarter triangle out
				array(
					array(0, $this->half), array(180, $this->half), array(270, $this->half),
				),
			),
			array( // quarter triangle in
				array(
					array(315, $this->diagonal), array(225, $this->diagonal), array(0, 0),
				),
			),
			array( // eighth triangle in
				array(
					array(90, $this->half), array(180, $this->half), array(0, 0),
				),
			),
			array( // eighth triangle out
				array(
					array(90, $this->half), array(135, $this->diagonal), array(180, $this->half),
				),
			),
			array( // double corner square
				array(
					array(90, $this->half), array(135, $this->diagonal), array(180, $this->half), array(0, 0),
				),
				array(
					array(0, $this->half), array(315, $this->diagonal), array(270, $this->half), array(0, 0),
				),
			),
			array( // double quarter triangle in
				array(
					array(315, $this->diagonal), array(225, $this->diagonal), array(0, 0),
				),
				array(
					array(45, $this->diagonal), array(135, $this->diagonal), array(0, 0),
				),
			),
			array( // tall quarter triangle
				array(
					array(90, $this->half), array(135, $this->diagonal), array(225, $this->diagonal),
				),
			),
			array( // double tall quarter triangle
				array(
					array(90, $this->half), array(135, $this->diagonal), array(225, $this->diagonal),
				),
				array(
					array(45, $this->diagonal), array(90, $this->half), array(270, $this->half),
				),
			),
			array( // tall quarter + eighth triangles
				array(
					array(90, $this->half), array(135, $this->diagonal), array(225, $this->diagonal),
				),
				array(
					array(45, $this->diagonal), array(90, $this->half), array(0, 0),
				),
			),
			array( // tipped over tall triangle
				array(
					array(135, $this->diagonal), array(270, $this->half), array(315, $this->diagonal),
				),
			),
			array( // triple triangle diagonal
				array(
					array(180, $this->half), array(225, $this->diagonal), array(0, 0),
				),
				array(
					array(45, $this->diagonal), array(90, $this->half), array(0, 0),
				),
				array(
					array(0, $this->half), array(0, 0), array(270, $this->half),
				),
			),
			array( // double triangle flat
				array(
					array(0, $this->quarter), array(315, $this->diagonal), array(270, $this->half),
				),
				array(
					array(270, $this->half), array(180, $this->quarter), array(225, $this->diagonal),
				),
			),
			array( // opposite 8th triangles
				array(
					array(0, $this->quarter), array(45, $this->diagonal), array(315, $this->diagonal),
				),
				array(
					array(180, $this->quarter), array(135, $this->diagonal), array(225, $this->diagonal),
				),
			),
			array( // opposite 8th triangles + diamond
				array(
					array(0, $this->quarter), array(45, $this->diagonal), array(315, $this->diagonal),
				),
				array(
					array(180, $this->quarter), array(135, $this->diagonal), array(225, $this->diagonal),
				),
				array(
					array(180, $this->quarter), array(90, $this->half), array(0, $this->quarter), array(270, $this->half),
				),
			),
			array( // small diamond
				array(
					array(0, $this->quarter), array(90, $this->quarter), array(180, $this->quarter), array(270, $this->quarter),
				),
				'symmetry' => 4,
			),
			array( // 4 opposite 8th triangles
				array(
					array(0, $this->quarter), array(45, $this->diagonal), array(315, $this->diagonal),
				),
				array(
					array(180, $this->quarter), array(135, $this->diagonal), array(225, $this->diagonal),
				),
				array(
					array(270, $this->quarter), array(225, $this->diagonal), array(315, $this->diagonal),
				),
				array(
					array(90, $this->quarter), array(135, $this->diagonal), array(45, $this->diagonal),
				),
				'symmetry' => 4,
			),
			array( // double quarter triangle parallel
				array(
					array(315, $this->diagonal), array(225, $this->diagonal), array(0, 0),
				),
				array(
					array(0, $this->half), array(90, $this->half), array(180, $this->half),
				),
			),
			array( // double overlapping tipped over tall triangle
				array(
					array(135, $this->diagonal), array(270, $this->half), array(315, $this->diagonal),
				),
				array(
					array(225, $this->diagonal), array(90, $this->half), array(45, $this->diagonal),
				),
			),
			array( // opposite double tall quarter triangle
				array(
					array(90, $this->half), array(135, $this->diagonal), array(225, $this->diagonal),
				),
				array(
					array(315, $this->diagonal), array(45, $this->diagonal), array(270, $this->half),
				),
			),
			array( // 4 opposite 8th triangles+tiny diamond
				array(
					array(0, $this->quarter), array(45, $this->diagonal), array(315, $this->diagonal),
				),
				array(
					array(180, $this->quarter), array(135, $this->diagonal), array(225, $this->diagonal),
				),
				array(
					array(270, $this->quarter), array(225, $this->diagonal), array(315, $this->diagonal),
				),
				array(
					array(90, $this->quarter), array(135, $this->diagonal), array(45, $this->diagonal),
				),
				array(
					array(0, $this->quarter), array(90, $this->quarter), array(180, $this->quarter), array(270, $this->quarter),
				),
				'symmetry' => 4,
			),
			array( // diamond C
				array(
					array(0, $this->half), array(90, $this->half), array(180, $this->half), array(270, $this->half), array(270, $this->quarter), array(180, $this->quarter), array(90, $this->quarter), array(0, $this->quarter),
				),
			),
			array( // narrow diamond
				array(
					array(0, $this->quarter), array(90, $this->half), array(180, $this->quarter), array(270, $this->half),
				),
			),
			array( // quadruple triangle diagonal
				array(
					array(180, $this->half), array(225, $this->diagonal), array(0, 0),
				),
				array(
					array(45, $this->diagonal), array(90, $this->half), array(0, 0),
				),
				array(
					array(0, $this->half), array(0, 0), array(270, $this->half),
				),
				array(
					array(90, $this->half), array(135, $this->diagonal), array(180, $this->half),
				),
			),
			array( // diamond donut
				array(
					array(0, $this->half), array(90, $this->half), array(180, $this->half), array(270, $this->half), array(0, $this->half), array(0, $this->quarter), array(270, $this->quarter), array(180, $this->quarter), array(90, $this->quarter), array(0, $this->quarter),
				),
				'symmetry' => 4,
			),
			array( // triple turning triangle
				array(
					array(90, $this->half), array(45, $this->diagonal), array(0, $this->quarter),
				),
				array(
					array(0, $this->half), array(315, $this->diagonal), array(270, $this->quarter),
				),
				array(
					array(270, $this->half), array(225, $this->diagonal), array(180, $this->quarter),
				),
			),
			array( // double turning triangle
				array(
					array(90, $this->half), array(45, $this->diagonal), array(0, $this->quarter),
				),
				array(
					array(0, $this->half), array(315, $this->diagonal), array(270, $this->quarter),
				),
			),
			array( // diagonal opposite inward double triangle
				array(
					array(90, $this->half), array(45, $this->diagonal), array(0, $this->quarter),
				),
				array(
					array(270, $this->half), array(225, $this->diagonal), array(180, $this->quarter),
				),
			),
			array( // star fleet
				array(
					array(90, $this->half), array(225, $this->diagonal), array(0, 0), array(315, $this->diagonal),
				),
			),
			array( // hollow half triangle
				array(
					array(90, $this->half), array(225, $this->diagonal), array(0, 0), array(315, $this->halfdiag), array(225, $this->halfdiag), array(225, $this->diagonal), array(315, $this->diagonal),
				),
			),
			array( // double eighth triangle out
				array(
					array(90, $this->half), array(135, $this->diagonal), array(180, $this->half),
				),
				array(
					array(270, $this->half), array(315, $this->diagonal), array(0, $this->half),
				),
			),
			array( // double slanted square
				array(
					array(90, $this->half), array(135, $this->diagonal), array(180, $this->half), array(180, $this->quarter),
				),
				array(
					array(270, $this->half), array(315, $this->diagonal), array(0, $this->half), array(0, $this->quarter),
				),
			),
			array( // double diamond
				array(
					array(0, $this->half), array(45, $this->halfdiag), array(0, 0), array(315, $this->halfdiag),
				),
				array(
					array(180, $this->half), array(135, $this->halfdiag), array(0, 0), array(225, $this->halfdiag),
				),
			),
			array( // double pointer
				array(
					array(0, $this->half), array(45, $this->diagonal), array(0, 0), array(315, $this->halfdiag),
				),
				array(
					array(180, $this->half), array(135, $this->halfdiag), array(0, 0), array(225, $this->diagonal),
				),
			),
			array( // fat diagonal
				array(
					array(90, $this->half), array(135, $this->diagonal), array(270, $this->half), array(315, $this->diagonal),
				),
			),
			array( // double diagonal triangle
				array(
					array(90, $this->half), array(45, $this->diagonal), array(315, $this->diagonal), array(270, $this->half), array(0, $this->half),
				),
			),
			array( // inverse diamond
				array(
					array(0, $this->half), array(45, $this->diagonal), array(135, $this->diagonal), array(225, $this->diagonal), array(315, $this->diagonal), array(0, $this->half), array(90, $this->half), array(180, $this->half), array(270, $this->half),
				),
				'symmetry' => 4,
			),
			array( // 3/4 diamond
				array(
					array(0, $this->half), array(90, $this->half), array(0, 0), array(180, $this->half), array(270, $this->half),
				),
			),
			array( // iron cross
				array(
					array(0, 0), array(30, $this->semidiag), array(330, $this->semidiag), array(0, 0), array(300, $this->semidiag), array(240, $this->semidiag), array(0, 0), array(210, $this->semidiag), array(150, $this->semidiag), array(0, 0), array(120, $this->semidiag), array(60, $this->semidiag),
				),
				'symmetry' => 4,
			),
			array( // star
				array(
					array(0, $this->quarter), array(45, $this->diagonal), array(90, $this->quarter), array(135, $this->diagonal), array(180, $this->quarter), array(225, $this->diagonal), array(270, $this->quarter), array(315, $this->diagonal),
				),
				'symmetry' => 4,
			),
			array( // double eighth triangles
				array(
					array(90, $this->half), array(135, $this->diagonal), array(180, $this->half),
				),
				array(
					array(45, $this->diagonal), array(90, $this->half), array(0, 0),
				),
			),
			array( // chevron
				array(
					array(0, $this->half), array(90, $this->half), array(135, $this->diagonal), array(0, 0), array(225, $this->diagonal), array(270, $this->half),
				),
			),
			array( // fish
				array(
					array(0, $this->half), array(45, $this->diagonal), array(90, $this->half), array(270, $this->half), array(180, $this->half),
				),
			),
			array( // half triangle + 8th triangle
				array(
					array(315, $this->diagonal), array(225, $this->diagonal), array(135, $this->diagonal),
				),
				array(
					array(0, $this->half), array(90, $this->half), array(0, 0),
				),
			),
			array( // double parallelogram
				array(
					array(0, $this->half), array(90, $this->half), array(270, $this->half), array(180, $this->half), array(135, $this->diagonal), array(315, $this->diagonal),
				),
			),
			array( // inverse radioactive
				array(
					array(225, $this->diagonal), array(0, $this->half), array(135, $this->diagonal), array(45, $this->diagonal), array(315, $this->diagonal),
				),
				array(
					array(180, $this->half), array(90, $this->quarter), array(270, $this->quarter),
				),
			),
			array( // triple outward 8th triangle
				array(
					array(270, $this->half), array(0, $this->half), array(90, $this->half), array(180, $this->half), array(135, $this->diagonal), array(45, $this->diagonal), array(315, $this->diagonal),
				),
			),
		);

		$this->rotatable = array( );
		foreach ($this->shapes as $idx => & $shape) { // mind the reference
			if ( ! empty($shape['symmetry']) && (4 === $shape['symmetry'])) {
				$this->rotatable[] = $idx;
				unset($shape['symmetry']);
			}
		}
		unset($shape); // kill the reference

		$this->square = $this->shapes[0][0];
		$this->symmetric_num = ceil($this->blocks * $this->blocks / 4);

		for ($i = 0; $i < $this->blocks; $i++) {
			for ($j = 0; $j < $this->blocks; $j++) {
				$this->centers[$i][$j] = array(
					$this->half + $this->blocksize * $j,
					$this->half + $this->blocksize * $i,
				);

				$this->shapes_mat[$this->xy2symmetric($i, $j)] = 1;
				$this->rot_mat[$this->xy2symmetric($i, $j)] = 0;
				$this->invert_mat[$this->xy2symmetric($i, $j)] = 0;

				if (floor(($this->blocks - 1) / 2 - $i) >= 0 & floor(($this->blocks - 1) / 2 - $j) >= 0 & ($j >= $i | $this->blocks % 2 == 0)) {
					$inversei = $this->blocks - 1 - $i;
					$inversej = $this->blocks - 1 - $j;

					$symmetrics = array(
						array($i, $j),
						array($inversej, $i),
						array($inversei, $inversej),
						array($j, $inversei),
					);

					$fill = array(0, 270, 180, 90);

					for ($k = 0; $k < count($symmetrics); $k++) {
						$this->rotations[$symmetrics[$k][0]][$symmetrics[$k][1]] = $fill[$k];
					} //$k = 0; $k < count($symmetrics); $k++
				} //floor(($this->blocks - 1) / 2 - $i) >= 0 & floor(($this->blocks - 1) / 2 - $j) >= 0 & ($j >= $i | $this->blocks % 2 == 0)
			} //$j = 0; $j < $this->blocks; $j++
		} //$i = 0; $i < $this->blocks; $i++
	}

	function xy2symmetric($x, $y) {
		$index = array(
			floor(abs(($this->blocks - 1) / 2 - $x)),
			floor(abs(($this->blocks - 1) / 2 - $y)),
		);

		sort($index);

		$index[1] *= ceil($this->blocks / 2);
		$index = array_sum($index);

		return $index;
	}



	//convert array(array(heading1,distance1),array(heading1,distance1)) to array(x1,y1,x2,y2)
	/*
	headings start at 0 ----------> and proceed clockwise in degrees

	(0,0)                       (1,0)
		  \        |        /
			\     270     /
			 225   |   315
			   \   |   /
				 \ | /
	  --- 180 ---- o ---- 0 ----->
				 / | \
			   /   |   \
			135    |    45
		   /       90     \
		 /         |        \
	(0,1)                       (1,1)

	When rotation gets added into the mix, it also rotates clockwise in degrees
	*/
	function calc_x_y($array, $centers, $rotation = 0) {
		$output = array( );
		$centerx = $centers[0];
		$centery = $centers[1];

		while ($thispoint = array_pop($array)) {
			$y = round($centery + sin(deg2rad($thispoint[0] + $rotation)) * $thispoint[1]);
			$x = round($centerx + cos(deg2rad($thispoint[0] + $rotation)) * $thispoint[1]);
			array_push($output, $x, $y);
		} //$thispoint = array_pop($array)

		return $output;
	}

	//draw filled polygon based on an array of (x1,y1,x2,y2,..)
	function draw_shape($x, $y) {
		$index = $this->xy2symmetric($x, $y);
		$shape = $this->shapes[$this->shapes_mat[$index]];
		$invert = $this->invert_mat[$index];
		$rotation = $this->rot_mat[$index];
		$centers = $this->centers[$x][$y];
		$invert2 = abs($invert - 1);
		$points = $this->calc_x_y($this->square, $centers, 0);
		$num = count($points) / 2;

		imagefilledpolygon($this->im, $points, $num, $this->colors[$invert2]);

		foreach ($shape as $subshape) {
			$points = $this->calc_x_y($subshape, $centers, $rotation + $this->rotations[$x][$y]);
			$num = count($points) / 2;
			imagefilledpolygon($this->im, $points, $num, $this->colors[$invert]);
		} //$shape as $subshape
	}

	//use a seed value to determine shape, rotation, and color
	function set_randomness($seed = "") {
		//set seed
		mt_srand(hexdec($seed));

		foreach ($this->rot_mat as $key => $value) {
			$this->rot_mat[$key] = mt_rand(0, 3) * 90;
			$this->invert_mat[$key] = mt_rand(0, 1);
			//&$this->blocks%2
			if ($key == 0) {
				$this->shapes_mat[$key] = $this->rotatable[mt_rand(0, count($this->rotatable) - 1)];
			}
			else {
				$this->shapes_mat[$key] = mt_rand(0, count($this->shapes) - 1);
			}
		} //$this->rot_mat as $key => $value

		$forecolors = array(
			mt_rand($this->options['forer'][0], $this->options['forer'][1]),
			mt_rand($this->options['foreg'][0], $this->options['foreg'][1]),
			mt_rand($this->options['foreb'][0], $this->options['foreb'][1]),
		);
		$this->colors[1] = imagecolorallocate($this->im, $forecolors[0], $forecolors[1], $forecolors[2]);

		if (array_sum($this->options['backr']) + array_sum($this->options['backg']) + array_sum($this->options['backb']) == 0) {
			$this->colors[0] = imagecolorallocatealpha($this->im, 0, 0, 0, 127);
			$this->transparent = true;
			imagealphablending($this->im, false);
			imagesavealpha($this->im, true);
		} //array_sum($this->options['backr']) + array_sum($this->options['backg']) + array_sum($this->options['backb']) == 0
		else {
			$backcolors = array(
				mt_rand($this->options['backr'][0], $this->options['backr'][1]),
				mt_rand($this->options['backg'][0], $this->options['backg'][1]),
				mt_rand($this->options['backb'][0], $this->options['backb'][1]),
			);
			$this->colors[0] = imagecolorallocate($this->im, $backcolors[0], $backcolors[1], $backcolors[2]);
		}

		if ($this->options['grey']) {
			$this->colors[1] = imagecolorallocate($this->im, $forecolors[0], $forecolors[0], $forecolors[0]);

			if ( ! $this->transparent) {
				$this->colors[0] = imagecolorallocate($this->im, $backcolors[0], $backcolors[0], $backcolors[0]);
			}
		} //$this->options['grey']

		return true;
	}

	function build($seed = '', $altImgText = '', $img = true, $outsize = '', $write = true, $random = true, $displaysize = '', $gravataron = true) {
		//make an identicon and return the filepath or if write=false return picture directly
		if (function_exists("gd_info")) {
			$memory_limit = ini_set('memory_limit', '256M');

			// init random seed
			if ($random) {
				$id = substr(sha1($seed), 0, 10);
			}
			else {
				$id = $seed;
			}

			$filename = $seed . '.png';

			if ($outsize == '') {
				$outsize = $this->options['size'];
			}

			if ($displaysize == '') {
				$displaysize = $outsize;
			}

			if ( ! file_exists($this->root . $this->cache_dir . $filename)) {
				$this->im = imagecreatetruecolor($this->size, $this->size);
				$this->colors = array(imagecolorallocate($this->im, 255, 255, 255));

				if ($random) {
					$this->set_randomness($id);
				}
				else {
					$this->colors = array(
						imagecolorallocate($this->im, 255, 255, 255),
						imagecolorallocate($this->im, 0, 0, 0),
					);
					$this->transparent = false;
				}

				imagefill($this->im, 0, 0, $this->colors[0]);

				for ($i = 0; $i < $this->blocks; $i++) {
					for ($j = 0; $j < $this->blocks; $j++) {
						$this->draw_shape($i, $j);
					} //$j = 0; $j < $this->blocks; $j++
				} //$i = 0; $i < $this->blocks; $i++

				$out = @imagecreatetruecolor($outsize, $outsize);
				imagesavealpha($out, true);
				imagealphablending($out, false);
				imagecopyresampled($out, $this->im, 0, 0, 0, 0, $outsize, $outsize, $this->size, $this->size);
				imagedestroy($this->im);

				if ($write) {
					if ( ! imagepng($out, $this->root . $this->cache_dir . $filename)) {
						return false; //something went wrong but don't want to mess up blog layout
					}
				} //$write
				else {
					header("Content-type: image/png");
					imagepng($out);
				}
				imagedestroy($out);
			} //!file_exists($this->cache_dir . $filename)

			$filename = $this->webroot . $this->cache_dir . $filename;

			if ($this->options['gravatar'] && $gravataron) {
				$filename = "http://www.gravatar.com/avatar.php?gravatar_id=" . md5($seed) . "&amp;size=$outsize&amp;default=$filename";
			}

			if ($img) {
				$filename = '<img class="identicon" src="' . $filename . '" alt="' . str_replace('"', "'", $altImgText) . ' Identicon Icon" height="' . $displaysize . '" width="' . $displaysize . '" />';
			} //$img

			ini_set('memory_limit', $memory_limit);

			return $filename;
		} //function_exists("gd_info")
		else { //php GD image manipulation is required
			return false; //php GD image isn't installed but don't want to mess up blog layout
		}
	}

	function display_parts( ) {
		$this->identicon(1);
		$output = '';
		$counter = 0;

		for ($i = 0; $i < count($this->shapes); $i++) {
			$this->shapes_mat = array($i);
			$this->invert_mat = array(1);
			$output .= $this->build($seed = 'example' . $i, $altImgText = '', $img = true, $outsize = 255, $write = true, $random = false);
			$counter++;
		} //$i = 0; $i < count($this->shapes); $i++

		$this->identicon( );
		return $output;
	}
}

