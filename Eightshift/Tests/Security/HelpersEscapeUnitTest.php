<?php

/**
 * Unit test class for HelpersEscape sniff.
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team.wordpress@infinum.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 */

namespace EightshiftCS\Eightshift\Tests\Security;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the HelpersEscape sniff.
 *
 * @covers \EightshiftCS\Eightshift\Sniffs\Security\HelpersEscapeSniff
 *
 * @since 1.4.0 Added $testFile parameter.
 */
class HelpersEscapeUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(string $testFile = ''): array
	{
		return match ($testFile) {
			'HelpersEscapeUnitTest.1.inc' => [
				12 => 1,
				14 => 2,
				15 => 1,
				21 => 4,
				23 => 4,
			],
			'HelpersEscapeUnitTest.2.inc' => [
				3 => 1,
				5 => 2,
				6 => 1,
				10 => 1,
				12 => 2,
				13 => 1,
				17 => 2,
				18 => 1,
				21 => 1,
				23 => 2,
				24 => 1,
			],
			'HelpersEscapeUnitTest.3.inc' => [
				5 => 1,
				7 => 2,
				8 => 1,
				19 => 1,
				24 => 1,
			],
			'HelpersEscapeUnitTest.4.inc' => [
				102 => 1,
			],
			'HelpersEscapeUnitTest.5.inc' => [
				25 => 1,
				149 => 1,
			],
			'HelpersEscapeUnitTest.6.inc' => [
				5 => 1,
				7 => 2,
				8 => 1,
			],
			default => [],
		};
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList(): array
	{
		return [];
	}
}
