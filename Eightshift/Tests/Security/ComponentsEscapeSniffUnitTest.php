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
class ComponentsEscapeSniffUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList(): array
	{
		return [
		];
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
