<?php

/**
 * Unit test class for CustomEscapeOutput sniff.
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
 * Unit test class for the CustomEscapeOutput sniff.
 *
 * @since 1.3.0 Added additional tests.
 * @since 1.2.0
 */
class CustomEscapeOutputUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList(): array
	{
		return [
			17  => 1,
			19  => 1,
			36  => 1,
			39  => 1,
			40  => 1,
			41  => 1,
			43  => 1,
			46  => 1,
			53  => 1,
			59  => 1,
			60  => 1,
			65  => 1,
			68  => 1,
			71  => 1,
			73  => 1,
			75  => 1,
			101 => 1,
			103 => 1,
			111 => 1,
			112 => 1,
			113 => 1,
			114 => 1,
			125 => 1,
			126 => 1, // Old-style WPCS ignore comments are no longer supported.
			127 => 1, // Old-style WPCS ignore comments are no longer supported.
			128 => 1, // Old-style WPCS ignore comments are no longer supported.
			131 => 1,
			135 => 1,
			138 => 1,
			145 => 1,
			147 => 1,
			149 => 1,
			152 => 2,
			159 => 1,
			162 => 1,
			169 => 1,
			172 => 1,
			173 => 1,
			182 => 3,
			190 => 1,
			191 => 2,
			205 => 1,
			206 => 1,
			207 => 1,
			223 => 1,
			225 => 1,
			226 => 1,
			241 => 1, // Old-style WPCS ignore comments are no longer supported.
			245 => 1, // Old-style WPCS ignore comments are no longer supported.
			249 => 1, // Old-style WPCS ignore comments are no longer supported.
			252 => 1,
			253 => 1,
			263 => 1,
			264 => 1,
			266 => 1,
			282 => 1,
			286 => 1,
			289 => 1,
			294 => 1,
			297 => 1,
			309 => 1,
			314 => 1,
			317 => 1,
			318 => 1,
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
