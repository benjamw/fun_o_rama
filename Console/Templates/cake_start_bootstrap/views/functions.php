<?php

if ( ! function_exists('replace_tlis')) {
	function replace_tlis($string) {
		$TLIs = array(
			'Faq' => 'FAQ',
			'Pdf' => 'PDF',
			'Rss' => 'RSS',
			'Seo' => 'SEO',
			'Url' => 'URL',
		);

		$string = preg_replace('`Id$`', 'ID', $string);

		return str_replace(array_keys($TLIs), array_values($TLIs), $string);
	}
}

