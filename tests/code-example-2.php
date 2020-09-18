<?php

/**
 * Example file
 *
 * @package Test
 */

$fruit = ['apple', 'pear', 'peach'];

array_map(function ($element) {
	return '1' . $element;
}, $fruit);
