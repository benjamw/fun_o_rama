<?php
class MenuHelper extends Helper {

	var $helpers = array('Html');
	var $__out;

	var $__typeTags = array('dl'=>'dd', 'ul'=>'li', 'ol'=>'li');

	// the methods available are:
	// 	- array: tests the URL array and finds the highest score
	// 	- string: tests the URL string and finds the highest score
	// 	- exact: tests the URL string and only selects an exact match
	// 	- mixed: tests both the URL string and URL array and finds highest score
	// points: When using the array or mixed methods, favor certain parts higher
	// than others - i.e. a match on action ('edit') isn't as
	// important as a match on controller, prefix, plugin, etc.
	var $_defaultSettings = array(
			'activeClass' => 'active',
			'firstClass' => 'first',
			'lastClass' => 'last',
			'tag' => 'li',
			'inactive' => false,
			'method' => 'array',
			'points' => array(
				'admin' => 2,
				'prefix' => 2,
				'plugin' => 4,
				'controller' => 3,
				'action' => 1,
				'pass' => 1, // Multiplies by count( )!
				'named' => 1, // Multiplies by count( )!
			),
		);

	/**
	 *
	 * @param array 	$data data for menu as Name=>value pairs (value can be a cake url array)
	 * @param array 	settings array
	 * @access public
	 *
	 * usage: <?php $this->Menus->menu($data, array('tag' => 'li', 'activeClass' => 'current')); ?>
	 */
	function menu($data, $settings = array( )) {
		if (empty($data)) {
			return '';
		}

		// creates $activeClass, $tag, $inactive, $method, and $points
		extract(am($this->_defaultSettings, $settings));

		$here_str = strtolower(substr($this->here, strlen($this->base)));
		$here_str_arr = explode('/', substr($here_str, 1));
		$here_arr = Router::parse($here_str);

		$here['url'] = null; // it causes false positives
		$matchingLinks = array( );

		foreach ($data as $item => $link) {
			$score = 0;

			// test for a manually set active link
			if (isset($link['active']) && $link['active']) {
				$matchingLinks[$item] = 10000; // a very large number
			}

			// set defaults on the link params
			// $link[0] is the link text
			$link[1] = (isset($link[1]) ? $link[1] : null); // the URL
			$link[2] = (isset($link[2]) ? $link[2] : array( )); // the html attributes
			$link[3] = (isset($link[3]) ? $link[3] : false); // the confirm message
			$link[4] = (isset($link[4]) ? $link[4] : true); // escape title
			$data[$item] = $link;

			$link_str = strtolower(substr(Router::url($link[1]), strlen($this->base)));
			$link_str_arr = explode('/', substr($link_str, 1));
			$link_arr = Router::parse($link_str);

			switch ($method) {
				case 'string' :
					// same as below
					$score += 2 * count(array_intersect_assoc($link_str_arr, $here_str_arr));
					break;

				case 'mixed' :
					// same as above
					$score += 2 * count(array_intersect_assoc($link_str_arr, $here_str_arr));
					// no break

				case 'array' :
					$test = array_intersect_assoc_recursive($link_arr, $here_arr);
					foreach ($test as $key => $value) {
						if ( ! empty($value)) {
							if (is_array($value)) {
								$score += count($value) * ( ! empty($points[$key]) ? $points[$key] : 1);
							}
							else {
								$score += ( ! empty($points[$key]) ? $points[$key] : 1);
							}
						}
					}
					break;

				case 'exact' :
					if (0 == strcmp($link_str, $here_str)) {
						$score += 1000; // a large number, but not larger than manual above
					}
					break;

				default :
					break;
			}

			if ($score) {
				$matchingLinks[$item] = $score;
			}
		}

		if ($inactive) {
			// remove any duplicate scores
			$matchingLinks = array_diff($matchingLinks, array_unique(array_diff_assoc($matchingLinks, array_unique($matchingLinks))));

			if ('exact' === $method) {
				foreach ($matchingLinks as $key => $score) {
					if ($score < 1000) {
						unset($matchingLinks[$key]);
					}
				}
			}
		}

		arsort($matchingLinks);
		$matchingLinkKeys = array_keys($matchingLinks);
		$activeLink = reset($matchingLinkKeys);

		# VIEW html

		$i = 0;
		$n = count($data);
		foreach ($data as $item => $link) {
			++$i;
			$classes = array( );
			$classes[] = (($item === $activeLink) ? $activeClass : '');
			$classes[] = (($i == 1) ? $firstClass : false);
			$classes[] = (($i == $n) ? $lastClass : false);
			$options = ' class="'.join(' ',$classes).'"';

			if ($item == $activeLink) {
				$link[2]['class'] = ((isset($link[2]['class'])) ? $link[2]['class'].' ' : '').$activeClass;
			}

			$this->__out[] = $this->Html->useTag($tag, $options, $this->Html->link($link[0], $link[1], $link[2], $link[3], $link[4]));
		}

		return join("\n", $this->__out);
	}

// previous menu, not used
	function menud($data = array( ), $activeClass = 'current', $tag = 'li')
	{
		// reset output
		$this->__out = array( );

		// check data
		if (empty($data) && count($data) < 1) {
			return '';
		}

		// sort out matching links
		$matchingLinks = array();

		foreach ($data as $link) {
			if (is_array($link)) {
				if (isset($link[0])) {
					$link = Router::url($link[0], false);
				}
				else {
					$link = Router::url($link, false);
				}
			}

			if ($mainMenuActive) {
				if (preg_match('%^'.$link.'.*%i', $mainMenuActive)) {
					$matchingLinks[strlen($link)] = $link;
				}
			}
			else {
				if (preg_match('%^'.$link.'.*%i', substr($this->here, strlen($this->base)))) {
					$matchingLinks[strlen($link)] = $link;
				}
			}
		}

		krsort($matchingLinks);
		$activeLink = ( ! empty($matchingLinks) ? array_shift($matchingLinks) : false);
		if ($activeLink == '/') {
			// Since it detects / as the longest match when we're not in any of the Nav sections, check first
			if ($activeLink == substr($this->here, strlen($this->base))) {
				//$activeClass = 'home';
			}
			else {
				// Nothing is active...
				$activeLink = '';
			}
		}

		# VIEW html

		$i = 0;
		$n = count($data);
		foreach($data as $title => $link) {
			$options = array( );
			if (is_array($link)) {
				if (isset($link[0])) {
					$link = Router::url($link[0], false);
					$options = $link[1];
				}
				else {
					$link = Router::url($link, false);
				}
			}

			$i++;
			$classes = array( );
			$classes[] = (($link == $activeLink) ? $activeClass : '');
			$classes[] = (($i == $n) ? 'lastnav' : false);
			$options = ' class="'.join(' ', $classes).'"';
			$this->__out[] = $this->Html->useTag($tag, $options, $this->Html->link($title, $link, $options));
		}

		return join("\n", $this->__out);
	}


	/**
	 *
	 * @param array 	$data data for menu as Name=>array(Name=>value) pairs
	 * @param array 	$options options for menu as array to enable new features to be added
	 * @access public
	 *
	 * usage: <?php $this->Menu->twoTierMenu($data, array('type'=>'dl', 'class'=>'sub-menu', 'title'=>'dt', 'activeClass'=>'current')); ?>
	 */
	function twoTierMenu($data = array( ), $options = array('activeClass'=>'current', 'type'=>'ul', 'class'=>false, 'title'=>false)) {
		// reset output
		$this->__out = array( );

		// check data
		if (empty($data) && count($data) < 1) {
			return '';
		}

		// check we have a 2 level structure
		$keys = array_keys($data);
		if (!is_array($data[$keys[0]])) {
			return '';
		}

		// sort out matching links
		$activeLinks = array();

		// get array of all links
		foreach ($data as $groupTitle => $groupLinks) {
			$matchingLinks = array( );

			foreach ($groupLinks as $linkTitle => $linkUrl) {
				if (is_array($linkUrl)) {
					$linkUrl = Router::url($linkUrl, false);
				}

				if (preg_match('/^'.preg_quote($linkUrl, '/').'/', substr($this->here, strlen($this->base)))) {
					// if (preg_match('/^'.preg_quote($link, '/').'/', $this->params['url']['url']))
					$matchingLinks[strlen($linkUrl)] = $linkUrl;
				}
				elseif ($linkUrl == substr($this->here, strlen($this->base))) {
					// $matchingLinks[$groupTitle][strlen($linkUrl)] = $linkUrl;
				}
				else {
					// pr('link: '.$link.' | url: '.substr($this->here, strlen($this->base)));
				}
				// pr('preg: '.preg_quote($link).'/');
				// pr('base: '.substr($this->here, strlen($this->base)));
				// pr('url: '.$this->params['url']['url']);
			}
			// sorting
			krsort($matchingLinks);
			// pr($matchingLinks);
			// active link
			$activeLinks[$groupTitle] = ( ! empty($matchingLinks) ? array_shift($matchingLinks) : false);
		}

		// pr($matchingLinks);
		// pr($activeLinks);

		// output menu
		if ($options['class']) {
			$this->__out[] = '<'.$options['type'].' class="'.$options['class'].'">';
		}
		else {
			$this->__out[] = '<'.$options['type'].'>';
		}

		// build html
		foreach ($data as $groupTitle => $links) {
			if ($options['title']) {
				$this->__out[] = '<'.$options['title'].'>'.$groupTitle.'</'.$options['title'].'>';
			}

			foreach($links as $linkTitle => $linkUrl) {
				if (is_array($linkUrl)) {
					$linkUrl = Router::url($linkUrl, false);
				}

				$this->__out[] = '<'.$this->__typeTags[$options['type']].'>'.$this->Html->link($linkTitle, $linkUrl, (($linkUrl == $activeLinks[$groupTitle]) ? array('class'=>$options['activeClass']) : false), null, false).'</'.$this->__typeTags[$options['type']].'>';
			}
		}
		$this->__out[] = '</'.$options['type'].'>';

		// return
		return join("\n", $this->__out);
	}

	function reset( ) {
		$this->__out = array( );
	}

}


if ( ! function_exists('array_intersect_assoc_recursive')) {
	function array_intersect_assoc_recursive($array1, $array2) {
		if ( ! is_array($array1) || ! is_array($array2)) {
			return null;
		}

		$return  = array( );
		foreach ($array1 as $key => $value) {
			if (is_array($value) && isset($array2[$key])) {
				$return[$key] = array_intersect_assoc_recursive($array1[$key], $array2[$key]);
			}
			elseif (isset($array2[$key]) && ((string) $array1[$key] === (string) $array2[$key])) {
				$return[$key] = $value;
			}
		}

		return $return;
	}
}

