<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

if (class_exists('Inflector')) {
	// remove all unneeded irregular forms from the default inflections
	// also fix viri => viruses
	Inflector::rules('plural', array(
		'rules' => array(
			'/(s)tatus$/i' => '\1\2tatuses',
			'/(quiz)$/i' => '\1zes',
			'/^(ox)$/i' => '\1\2en',
			'/([m|l])ouse$/i' => '\1ice',
			'/(matr|vert|ind)(ix|ex)$/i' => '\1ices',
			'/(x|ch|ss|sh)$/i' => '\1es',
			'/([^aeiouy]|qu)y$/i' => '\1ies',
			'/(hive)$/i' => '\1s',
			'/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
			'/sis$/i' => 'ses',
			'/([ti])um$/i' => '\1a',
			'/(p)erson$/i' => '\1eople',
			'/(m)an$/i' => '\1en',
			'/(c)hild$/i' => '\1hildren',
			'/(buffal|tomat)o$/i' => '\1\2oes',
			'/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin)us$/i' => '\1i',
			'/us$/i' => 'uses',
			'/(alias)$/i' => '\1es',
			'/(ax|cris|test)is$/i' => '\1es',
			'/s$/' => 's',
			'/^$/' => '',
			'/$/' => 's',
		),
		'irregular' => array(
			'atlas' => 'atlases',
			'cafe' => 'cafes',
			'genus' => 'genera',
			'graffito' => 'graffiti',
			'loaf' => 'loaves',
			'money' => 'monies',
			'mythos' => 'mythoi',
			'numen' => 'numina',
			'penis' => 'penises',
			'trilby' => 'trilbys',
			'turf' => 'turfs',
		),
	), true);

	Inflector::rules('plural', array(
		'irregular' => array(
			'human' => 'humans',
			'musk-ox' => 'musk-oxen',
		),
		'uninflected' => array(
			'.*meta',
			'software',
		),
	));

	Inflector::rules('singular', array(
		'irregular' => array(
			'cookies' => 'cookie',
			'genies' => 'genie',
			'musk-oxen' => 'musk-ox',
			'niches' => 'niche',
			'testes' => 'testis',
		),
		'uninflected' => array(
			'.*meta',
			'software',
		),
	));
}

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

CakePlugin::loadAll( );

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter . By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

	// run the update scripts
	if (isset($_GET['url']) && ('cake_aco_sync_migrate_up' == $_GET['url'])) {
		Configure::write('debug', 2);

		// set our path the the cake shell script
		$path = CAKE_CORE_INCLUDE_PATH.DS.'cake'.DS.'console'.DS;
		if (isset($_SERVER['WINDIR']) && (false !== strpos(strtolower($_SERVER['WINDIR']), 'win'))) {
			if ('/' == substr(CAKE_CORE_INCLUDE_PATH, 0, 1)) {
				$path = '';
			}
		}
		$path .= 'cake';

		// move us to the right dir
		chdir(ROOT.DS.APP_DIR);
		debug(`pwd`);

		// migrate up
		$return = `{$path} migrate up`;
		debug($return);

		// aco sync
		$return = `{$path} acl_extras aco_sync`;
		debug($return);

		die('DONE');
	}

	// clear the cache
	if (isset($_GET['url']) && ('cake_clear_cache' == $_GET['url'])) {
		Configure::write('debug', 2);

		// move us to the right dir
		chdir(ROOT.DS.APP_DIR);
		debug(`pwd`);

		// clear everything not a folder and not in .svn dirs
		// this only works on *nix boxes, sorry windows =(
		debug(`find tmp/cache/ -type f -not -path '*.svn*' -delete`);
		debug('Cache cleared');

		die('DONE');
	}


	function base64url_encode($string) {
		return trim(strtr(base64_encode((string) $string), '+/', '-_'), ' \t\n\r\0\x0B=');
	}

	function base64url_decode($string) {
		return base64_decode(strtr((string) $string, '-_', '+/'));
	}


	function m($name) {
		if (ClassRegistry::isKeySet($name)) {
			return ClassRegistry::getObject($name);
		}
		else {
			return ClassRegistry::init(array('class' => $name, 'alias' => $name));
		}
	}


	// convert a one dimensional array into a tree array
	function array_inflate($array, $model_name, $remove_extra = false) {
		$inflated = Set::combine($array, '/'.$model_name.'/id', '/.');

		foreach ($inflated as $key => $node) {
			if ( ! $node[$model_name]['parent_id']) {
				continue;
			}

			if ( ! isset($inflated[$node[$model_name]['parent_id']]['Child'.$model_name])) {
				$inflated[$node[$model_name]['parent_id']]['Child'.$model_name] = array( );
			}

			$inflated[$node[$model_name]['parent_id']]['Child'.$model_name][] =& $inflated[$key];
		}

		if ($remove_extra) {
			foreach ($inflated as $key => $node) {
				if ( ! empty($node[$model_name]['parent_id'])) {
					unset($inflated[$key]);
				}
			}
		}

		return $inflated;
	}


	function array_flatten_keys($array, $return = array( ), $prefix = array( )) {
		$this_array = array_keys($array);
		sort($this_array);
		$orig_prefix = $prefix;

		foreach ($this_array as $value) {
			$prefix = $orig_prefix;
			$prefix[] = $value;

			$str_prefix = implode('.', $prefix);

			$return[$str_prefix] = $str_prefix;

			$pass = $array[$value];
			if (is_array($pass) && ! empty($pass)) {
				$return = array_merge($return, array_flatten_keys($pass, $return, $prefix));
			}
		}

		return $return;
	}

	function array_trim( & $array, $type = null) {
		$types = array(
			'int' , 'integer' ,
			'bool' , 'boolean' ,
			'float' , 'double' , 'real' ,
			'string' ,
			'array' ,
			'object' ,
		);

		// if a non-empty string value comes through, don't erase it
		// this is specifically for '0', but may work for others
		$is_non_empty_string = (is_string($array) && strlen(trim($array)));
		if ( ! $array && ! $is_non_empty_string) {
			$array = array( );
		}

		if ( ! in_array($type, $types)) {
			$type = null;
		}

		if ( ! is_array($array)) {
			$array = explode(',', $array);
		}

		if ( ! is_null($type)) {
			array_walk_recursive($array, create_function('&$v', '$v = ('.$type.') trim($v);'));
		}
		else {
			array_walk_recursive($array, create_function('&$v', '$v = trim($v);'));
		}

		return $array; // returns by reference as well
	}
	function arrayTrim( & $array, $type = null) { return array_trim($array, $type); }


	/** function implode_full [implodeFull]
	 *		Much like implode, but including the keys with an
	 *		extra divider between key-value pairs
	 *		Can be used to create URL GET strings from arrays
	 *
	 * @param array
	 * @param string optional separator between elements (for URL GET, use '&', default)
	 * @param string optional divider between key-value pairs (for URL GET, use '=', default)
	 * @param bitwise int optional URL encode flag
	 * @return string
	 */
	define('URL_ENCODE_NONE', 0);
	define('URL_ENCODE_KEY', 1);
	define('URL_ENCODE_VAL', 2);
	define('URL_ENCODE_FULL', 4);
	function implode_full($array, $separator = '&', $divider = '=', $url = URL_ENCODE_NONE)
	{
		if ( ! is_array($array) || (0 == count($array))) {
			return $array;
		}

		$str = '';
		foreach ($array as $key => $val) {
			if (URL_ENCODE_KEY & $url) {
				$key = urlencode($key);
			}

			if (URL_ENCODE_VAL & $url) {
				$val = urlencode($val);
			}

			$str .= $key.$divider.$val.$separator;
		}

		$str = substr($str, 0, -(strlen($separator)));

		if (URL_ENCODE_FULL & $url) {
			$str = urlencode($str);
		}

		return $str;
	}
	function implodeFull($array, $separator = '&', $divider = '=', $url = URL_ENCODE_NONE) { return implode_full($array, $separator, $divider, $url); }


	function geocode($address) {
		$base_url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

		$json = file_get_contents($base_url . urlencode($address));

		if ( ! $json) {
			return false;
		}

		$json = json_decode($json, true);

		if (0 === strcmp($json['status'], 'OK')) {
			return $json['results'][0]['geometry']['location'];
		}
		else {
			// failure to geocode
			return $json['status'];
		}

		return false;
	}


	// make sure to include rot13.js when using this function
	function email_link($addr, $link_content = false) {
		if ( ! $link_content) {
			$link_content = $addr;
		}

		//build the mailto link
		$unencrypted_link = '<a href="mailto:'.$addr.'">'.$link_content.'</a>';
		//build this for people with js turned off
		$noscript_link = '<noscript><span style="unicode-bidi:bidi-override;direction:rtl;">'.strrev($link_content.' ;tg& '.$addr.' ;tl&').'</span></noscript>';
		//put them together and encrypt
		$encrypted_link = '<script type="text/javascript">Rot13.write(\''.str_rot13($unencrypted_link).'\');</script>'.$noscript_link;

		return $encrypted_link;
	}


	/** function swap
	 *		swaps lower values with higher values if reversed
	 *		performs action on variable references
	 *
	 * @param numerical reference lower var to test
	 * @param numerical reference higher var to test
	 * @action swaps vars if $lower is higher than $higher
	 * @return array of values
	 */
	function swap( & $lower, & $higher) {
		if ($lower > $higher) {
			list($higher, $lower) = array($lower, $higher);
		}

		return array($lower, $higher); // returns both by reference as well
	}


	/** function ife
	 *		if-else
	 *		This function returns the value if it exists (or is optionally not empty)
	 *		or a default value if it does not exist (or is empty)
	 *
	 * @param mixed var to test
	 * @param mixed optional default value
	 * @param bool optional allow empty value
	 * @param bool optional change the passed reference var
	 * @return mixed $var if exists (and not empty) or $default otherwise
	 */
	function ife( & $var, $default = null, $allow_empty = true, $change_reference = false) {
		if ( ! isset($var) || ( ! (bool) $allow_empty && empty($var))) {
			if ((bool) $change_reference) {
				$var = $default; // so it can also be used by reference
			}

			return $default;
		}

		return $var;
	}
	function my_ife( & $var, $default = null, $allow_empty = true, $change_reference = false) { return ife($var, $default, $allow_empty, $change_reference); }


	/** function ifer
	 *		if-else reference
	 *		This function returns the value if it exists (or is optionally not empty)
	 *		or a default value if it does not exist (or is empty)
	 *		It also changes the reference var
	 *
	 * @param mixed var to test
	 * @param mixed optional default value
	 * @param bool optional allow empty value
	 * @action updates/sets the reference var if needed
	 * @return mixed $var if exists (and not empty) or default otherwise
	 */
	function ifer( & $var, $default = null, $allow_empty = true) {
		return ife($var, $default, $allow_empty, true);
	}


	/** function ifenr
	 *		if-else non-reference
	 *		This function returns the value if it is not empty
	 *		or a default value if it is empty
	 *		It does not use references so function returns and
	 *		other non-variable inputs may be used
	 *
	 * @param mixed var to test
	 * @param mixed optional default value
	 * @return mixed $var if not empty or default otherwise
	 */
	function ifenr($var, $default = null) {
		if (empty($var)) {
			return $default;
		}

		return $var;
	}


	/** function excerpt
	 *		returns a tag-stripped excerpt of the given copy
	 *
	 * @param string copy to excerpt
	 * @param int number of words to return
	 * @param bool count chracters instead
	 * @param string html for read more link
	 * @return string tag-stripped excerpt of $words length
	 */
	function excerpt($content, $words = 200, $chars = false, $link = '') {
		$words = (int) $words;
		$chars = (bool) $chars;

		$content = trim(strip_tags($content));

		if ( ! $chars) {
			$array = explode(' ', $content);
			$array = array_filter($array);

			if ($words < count($array)) {
				$array = array_slice($array, 0, $words);
				$content = implode(' ', $array).'...';

				if ( ! empty($link)) {
					$content .= ' '.$link;
				}
			}
		}
		else {
			$short = substr($content, 0, $words);

			if ($content !== $short) {
				$content = $short.'...';

				if ( ! empty($link)) {
					$content .= ' '.$link;
				}
			}
		}

		return $content;
	}


	function link_urls($text, $twitter = false) {
		// replace URLs with clickable links
		// http://daringfireball.net/2010/07/improved_regex_for_matching_urls
		$text = preg_replace('#\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()[\]{};:\'".,<>?«»“”‘’]))#i', '<a href="$1" rel="nofollow">$1</a>', $text);

		if ($twitter) {
			// replace any @mentions with links to the users profile
			$text = preg_replace('%(?!<\w)@(\w+)%i', '<a href="http://twitter.com/#!/$1" rel="nofollow">@$1</a>', $text);

			// replace any #hashtags with links to those search results
			$text = preg_replace('%(?!<\w)#(\w+)%i', '<a href="http://twitter.com/#!/search?q=%23$1" rel="nofollow">#$1</a>', $text);
		}

		return $text;
	}


	function clean_phone($number) {
		return preg_replace('/^(?:\+?1)?\D*([2-9][0-8][0-9])\D*([2-9][0-9]{2})\D*([0-9]{4})$/', '($1) $2-$3', $number);
	}


	/**
	 * strposall
	 *
	 * Find all occurrences of a needle in a haystack
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @return array or false
	 */
	function strposall($haystack, $needle) {
		$s = 0;
		$i = 0;

		while (is_integer($i)) {
			$i = strpos($haystack, $needle, $s);

			if (is_integer($i)) {
				$aStrPos[] = $i;
				$s = $i + strlen($needle);
			}
		}

		if (isset($aStrPos)) {
			return $aStrPos;
		}
		else {
			return false;
		}
	}


	/**
	 * Computes the number of combinations
	 * of k elements chosen from a set S of size n
	 *
	 * @param int n
	 * @param int k
	 * @return int n! / (k! (n - k)!)
	 */
	function nCk($n, $k) {
		return (fact($n) / (fact($k) * fact($n - $k)));
	}
	function num_combos($n, $k) { return nCk($n, $k); }


	/**
	 *	Computes the factorial (n!) of given n
	 *
	 * @param int n
	 * @return int n!
	 */
	function fact($n) {
		$out = 1;

		for ($i = 2; $i <= $n; ++$i) {
			$out *= $i;
		}

		return $out;
	}


	if ( ! function_exists('g')) {
		function g($var = '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-') {
			var_dump($var);
		}
	}

	if ( ! function_exists('gg')) {
		function gg($var = '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-') {
			g($var);
			exit;
		}
	}

