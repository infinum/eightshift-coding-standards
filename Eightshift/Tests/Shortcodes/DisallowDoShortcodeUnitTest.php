<?php

/**
 * Unit test class for DisallowDoShortcode sniff.
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team@eightshift.com>
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
 * @since 1.0.0 Added $testFile parameter.
 * @since 0.4.0
 */
class DisallowDoShortcodeUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList(): array
	{
		return [];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
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
		];
	}
}
