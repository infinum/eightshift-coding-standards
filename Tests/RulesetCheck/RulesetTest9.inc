<?php

namespace Eight;

use Name\Typing;

class Foo extends \stdClass {

	public function Bar(\Type $param) {
		if(is_int($param)) {
			trigger_error('wrong', E_USER_NOTICE);
		}
	}

	public function Baz(Typing $param) {
		if(\is_int($param)) {
			\trigger_error('wrong', \E_USER_NOTICE);
		}
	}
}
