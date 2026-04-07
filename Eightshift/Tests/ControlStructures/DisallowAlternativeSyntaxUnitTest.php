<?php

/**
 * Unit test class for DisallowAlternativeSyntax sniff.
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team.wordpress@infinum.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 */

namespace EightshiftCS\Eightshift\Tests\ControlStructures;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the DisallowAlternativeSyntax sniff.
 *
 * @covers \EightshiftCS\Eightshift\Sniffs\ControlStructures\DisallowAlternativeSyntaxSniff
 *
 * @since 2.1.0
 */
class DisallowAlternativeSyntaxUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array
	{
		return [
			11 => 1,
			15 => 1,
			19 => 1,
			23 => 1,
			29 => 1,
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
