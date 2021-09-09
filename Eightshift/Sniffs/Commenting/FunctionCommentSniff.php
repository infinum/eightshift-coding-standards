<?php

/**
 * Eightshift coding standards for WordPress
 *
 * @package EightshiftCS
 *
 * @author  Eightshift <team@eightshift.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 *
 * @since 1.4.0
 */

namespace EightshiftCS\Eightshift\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentSniff as SquizFunctionComment;
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Override the Squiz.Commenting.FunctionComment sniff
 *
 * This sniff will ignore the __invoke method in all the classes
 * that are extending the AbstractCli, which is used in the
 * eightshift-libs library to generate WP-CLI commands.
 */
class FunctionCommentSniff extends SquizFunctionComment
{
	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token
	 *                        in the stack passed in $tokens.
	 *
	 * @return void|int Optionally returns a stack pointer. The sniff will not be
	 *                  called again on the current file until the returned stack
	 *                  pointer is reached. Return (count($tokens) + 1) to skip
	 *                  the rest of the file.
	 */
	public function process(File $phpcsFile, $stackPtr)
	{
		// If the function is the called __invoke, and is inside the class
		// that extends the AbstractCli class, we need to bow out. In all other cases
		// we want to run the sniff.
		$functionName = $phpcsFile->getDeclarationName($stackPtr);

		if ($functionName !== '__invoke') {
			return parent::process($phpcsFile, $stackPtr);
		}

		$classPtr = Conditions::getLastCondition($phpcsFile, $stackPtr, [
			\T_CLASS,
			\T_ANON_CLASS
		]);

		if ($classPtr === false) {
			return parent::process($phpcsFile, $stackPtr);
		}

		$extendedClassName = ObjectDeclarations::findExtendedClassName($phpcsFile, $classPtr);

		if ($extendedClassName !== 'AbstractCli') {
			return parent::process($phpcsFile, $stackPtr);
		}
	}
}
