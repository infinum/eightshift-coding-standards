<?php

/**
 * Unit test class for FunctionComment sniff.
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team.wordpress@infinum.com>
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
 * @since 1.4.0
 */
class FunctionCommentUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array
	{
		return [
			12 => 1,
			25 => 1,
			43 => 1,
			62 => 1,
			79 => 1,
			116 => 1,
		];
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
