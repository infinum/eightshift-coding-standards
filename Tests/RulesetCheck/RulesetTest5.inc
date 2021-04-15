<?php

/**
 * Test
 *
 * @package Test
 */

/**
 * The first example will throw
 * NormalizedArrays.Arrays.ArrayBraceSpacing.SpaceAfterArrayOpenerSingleLine and
 * NormalizedArrays.Arrays.ArrayBraceSpacing.SpaceAfterArrayCloserSingleLine error.
 *
 * We are silencing the error so that our tests pass, but this serves as an example how NOT to code.
 */
$arr = [ 'some value', 'some other value' ]; // phpcs:ignore
$arr2 = ['some value', 'some other value']; // Ok.
