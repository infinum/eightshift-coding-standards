<?php

/**
 * Unit test class for FunctionComment sniff.
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team@eightshift.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 */

namespace EightshiftCS\Eightshift\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FunctionCommentUnitTest sniff.
 *
 * @covers \EightshiftCS\Eightshift\Sniffs\Commenting\FunctionCommentSniff
 *
 * @since 1.4.0 Added $testFile parameter.
 */
class FunctionCommentUnitTest extends AbstractSniffUnitTest
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
			case 'FunctionCommentUnitTest.1.inc':
				return [
					12 => 1,
					25 => 1,
					43 => 1,
					62 => 1,
					79 => 1,
				];
			case 'FunctionCommentUnitTest.2.inc':
				return [

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
