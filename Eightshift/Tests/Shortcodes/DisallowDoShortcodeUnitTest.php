<?php

/**
 * Unit test class for DisallowDoShortcode sniff.
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team.wordpress@infinum.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 */

namespace EightshiftCS\Eightshift\Tests\Shortcodes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the DisallowDoShortcode sniff.
 *
 * @covers \EightshiftCS\Eightshift\Sniffs\Shortcodes\DisallowDoShortcodeSniff
 *
 * @since 1.0.0 Improve on the test warning list.
 * @since 0.4.0
 */
class DisallowDoShortcodeUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array
	{
		return [];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList(): array
	{
		return [
			4 => 1,
			5 => 1,
			6 => 1,
			7 => 1,
			8 => 1,
			9 => 1,
			17 => 1,
			22 => 1,
			34 => 1,
			48 => 1,
			64 => 1,
			66 => 1,
			67 => 1,
		];
	}
}
