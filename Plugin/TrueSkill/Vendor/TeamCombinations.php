<?php

namespace Moserware\Skills;

class TeamCombinations implements \Iterator
{
	protected $c = null;
	protected $s = null;
	protected $n = 0;
	protected $k = 0;
	protected $recurse = false;
	protected $pos = 0;

	public function __construct(array $s, $k) {
		if ( ! is_array($s)) {
			trigger_error(__METHOD__.' expects parameter 1 to be an array, '.gettype($s).' given', E_USER_WARNING);
		}

		$this->s = array_values($s);
		$this->n = (int) count($this->s);

		$this->k = (int) $k;
		$this->teams = (int) floor($this->n / $this->k);

		if (empty($this->s)) {
			$this->pos = -99999;
		}

		if (($this->teams * $this->k) !== $this->n) {
			$this->pos = -99999;
		}

		$this->rewind( );
	}

	public function key( ) {
		return $this->pos;
	}

	public function current( ) {
		$r = array( );
		for ($i = 0; $i < $this->k; $i++) {
			$r[] = $this->s[$this->c[$i]];
		}

		// wrap the local responses in arrays
		// it will group the teams and make post-processing much easier
		if (1 === $this->teams) {
			return array($r);
		}
		elseif (2 === $this->teams) {
			return array_merge(array($r), array(array_diff($this->s, $r)));
		}

		// create the recursion with whatever set values remain
		if ( ! $this->recurse || ! $this->recurse->valid( )) {
			$this->recurse = new self(array_diff($this->s, $r), $this->k);
		}

		// because the return value from $this->recurse->current( )
		// is not local, it has already been wrapped in arrays
		return array_merge(array($r), $this->recurse->current( ));
	}

	public function next( ) {
		if ($this->_next( )) {
			++$this->pos;
		}
		else {
			$this->pos = -99999;
		}
	}

	public function rewind( ) {
		$this->c = range(0, $this->k - 1);
		$this->pos = 0;
	}

	public function valid( ) {
		return $this->pos >= 0;
	}

	protected function _next( ) {
		// if there is still a valid recursion
		// stop this function before it changes anything local
		if ($this->recurse) {
			$this->recurse->next( );

			if ($this->recurse->valid( )) {
				return true;
			}
		}

		$i = $this->k - 1;
		while (($i >= 0) && ($this->c[$i] == ($this->n - $this->k + $i))) {
			--$i;
		}

		// if the 0th (first) element is being changed,
		// then all other combinations are duplicates
		// so even though it's not a complete set as is
		// when taken together with the recursed combinations
		// the set is actually complete
		if ($i <= 0) {
			return false;
		}

		++$this->c[$i];
		while ($i++ < ($this->k - 1)) {
			$this->c[$i] = $this->c[$i - 1] + 1;
		}

		return true;
	}

}

