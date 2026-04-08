<?php

/**
 * Eightshift coding standards for WordPress
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team.wordpress@infinum.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 */

namespace EightshiftCS\Eightshift\Sniffs\ControlStructures;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Disallows alternative control structure syntax.
 *
 * Enforces the use of curly braces for all control structures.
 * Alternative syntax keywords (endif, endforeach, endfor, endwhile, endswitch)
 * are not allowed.
 *
 * @since 2.1.0
 */
class DisallowAlternativeSyntaxSniff implements Sniff
{
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	public function register()
	{
		return [
			\T_ENDIF,
			\T_ENDFOREACH,
			\T_ENDFOR,
			\T_ENDWHILE,
			\T_ENDSWITCH,
		];
	}

	/**
	 * Processes this test when one of its tokens is encountered.
	 *
	 * @since 2.1.0
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack.
	 *
	 * @return void
	 */
	public function process(File $phpcsFile, $stackPtr)
	{
		$phpcsFile->addError(
			'Alternative control structure syntax is not allowed; use curly braces instead.',
			$stackPtr,
			'FoundAlternativeSyntax'
		);
	}
}
