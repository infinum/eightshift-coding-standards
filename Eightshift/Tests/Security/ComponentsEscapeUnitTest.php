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
 * Unit test class for the FunctionCommentUnitTest sniff.
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
		switch ($testFile) {
			case 'HelpersEscapeUnitTest.1.inc':
				return [
					21 => 1,
					23 => 1,
				];
			case 'HelpersEscapeUnitTest.2.inc':
				return [
					3 => 1,
					10 => 1,
					17 => 1
				];
			case 'HelpersEscapeUnitTest.3.inc':
				return [
					12 => 1,
					19 => 1,
					24 => 1,
				];
			case 'HelpersEscapeUnitTest.4.inc':
				return [
					102 => 1,
				];
			case 'HelpersEscapeUnitTest.5.inc':
				return [
					25 => 1,
					149 => 1,
				];
			case 'HelpersEscapeUnitTest.6.inc':
				return [
					5 => 1,
				];
			default:
				return [];
		}
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
