<?php
// usage: echo $this->Plural->ize($count, $item);
class PluralHelper extends AppHelper {

	/**
	 * Pluralizes a term based on the count of that item given
	 * The term may have spaces in it: e.g. (3, 'Green Apple') -> 3 Green Apples
	 * The order of the item and count is not important
	 * If show_count is false, the output will not include the count: e.g. (3, 'Green Apple') -> Green Apples
	 */
	public function ize($count, $item, $show_count = true) {
		if (is_int($item)) {
			$tmp = $item;
			$item = $count;
			$count = $tmp;
		}

		$return = '';
		if ($show_count) {
			$return = $count.' ';
		}

		if (1 != $count) {
			$items = explode(' ', $item);
			$item = array_pop($items);

			if ($items) {
				$return .= implode(' ', $items).' ';
			}

			$return .= Inflector::pluralize($item);
		}
		else {
			$return .= $item;
		}

		return $return;
	}

}

