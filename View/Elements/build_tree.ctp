<?php

$tree_wrap_tag = ( ! isset($tree_wrap_tag) ? 'ul' : $tree_wrap_tag);
$item_wrap_tag = ( ! isset($item_wrap_tag) ? 'li' : $item_wrap_tag);

$item_spacer = (empty($item_spacer) ? '_' : $item_spacer);

$_ul = (empty($tree_wrap_tag) ? '' : '<'.$tree_wrap_tag.'>');
$_li = (empty($item_wrap_tag) ? '' : '<'.$item_wrap_tag.'>');
$__ul = (empty($tree_wrap_tag) ? '' : '</'.$tree_wrap_tag.'>');
$__li = (empty($item_wrap_tag) ? '' : '</'.$item_wrap_tag.'>');

if ( ! empty($tree_data)) {
	echo "\n\n\t".$_ul;

	$level = 0;
	$open = false;
	foreach ($tree_data as $item) {
		preg_match('%^(?:'.$item_spacer.')+%', $item, $match);

		$this_level = 0;
		if ($match) {
			$this_level = floor(strlen($match[0]) / strlen($item_spacer));
			$item = substr($item, strlen($match[0]));
		}

		if ($level == $this_level) {
			if ($open) {
				echo $__li;
			}

			echo "\n\t\t".str_repeat("\t", ($level * 2)).$_li.$item;
		}
		elseif ($level < $this_level) {
			echo "\n\t\t".str_repeat("\t", (($this_level * 2) - 1)).$_ul."\n\t\t".str_repeat("\t", ($this_level * 2)).$_li.$item;
		}
		elseif ($level > $this_level) {
			echo $__li;

			$cur_level = $level;
			while ($cur_level > $this_level) {
				echo "\n\t\t".str_repeat("\t", (($cur_level * 2) - 1)).$__ul."\n\t\t".str_repeat("\t", ($cur_level * 2) - 2).$__li;
				$cur_level -= 1;
			}

			echo "\n\t\t".str_repeat("\t", ($this_level * 2)).$_li.$item;
		}

		$open = true;
		$level = $this_level;
	}

	if (0 < $level) {
		echo $__li;

		$cur_level = $level;
		while ($cur_level > 0) {
			echo "\n\t\t".str_repeat("\t", (($cur_level * 2) - 1)).$__ul."\n\t\t".str_repeat("\t", ($cur_level * 2) - 2).$__li;
			$cur_level -= 1;
		}
	}

	echo "\n\t".$__ul."\n\n";
}

