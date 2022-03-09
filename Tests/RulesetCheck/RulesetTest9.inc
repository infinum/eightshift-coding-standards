<?php

/**
 * Example file
 *
 * @package Eight
 */

namespace Eight;

use Name\Typing;
use stdClass;
use Type;

/**
 * Class Foo
 */
class Foo extends stdClass
{
	/**
	 * Example method
	 *
	 * @param Type $param Parameter.
	 * @return void
	 */
	public function bar(Type $param)
	{
		if (\is_int($param)) {
			echo 'bla';
		}
	}

	/**
	 * Baz method
	 *
	 * @param Typing $param Parameter.
	 * @return void
	 */
	public function baz(Typing $param)
	{
		if (\is_int($param)) {
			echo 'bla';
		}
	}
}
