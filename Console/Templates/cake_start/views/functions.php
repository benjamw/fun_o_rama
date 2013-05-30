<?php

if ( ! function_exists('replace_tlis')) {
	function replace_tlis($string) {
		$TLIs = array(
			'Faq' => 'FAQ',
			'Rss' => 'RSS',
			'Pdf' => 'PDF',
			'Seo' => 'SEO',
		);

		return str_replace(array_keys($TLIs), array_values($TLIs), $string);
	}
}

