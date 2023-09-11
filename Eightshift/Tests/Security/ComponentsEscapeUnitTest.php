<?php

/**
 * Unit test class for ComponentsEscape sniff.
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team@eightshift.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 */

namespace EightshiftCS\Eightshift\Tests\Security;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FunctionCommentUnitTest sniff.
 *
 * @covers \EightshiftCS\Eightshift\Sniffs\Security\ComponentsEscapeSniff
 *
 * @since 1.4.0 Added $testFile parameter.
 */
class ComponentsEscapeUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList($testFile = ''): array
	{
		switch ($testFile) {
			case 'ComponentsEscapeUnitTest.1.inc':
				return [
					21 => 1,
					23 => 1,
				];
			case 'ComponentsEscapeUnitTest.2.inc':
				return [
					3 => 1,
					10 => 1,
					17 => 1
				];
			case 'ComponentsEscapeUnitTest.3.inc':
				return [
					12 => 1,
					19 => 1,
					24 => 1,
				];
			case 'ComponentsEscapeUnitTest.4.inc':
				return [
					102 => 1,
				];
			case 'ComponentsEscapeUnitTest.5.inc':
				return [
					25 => 1,
					149 => 1,
				];
			case 'ComponentsEscapeUnitTest.6.inc':
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
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList(): array
	{
		return [];
	}
}
