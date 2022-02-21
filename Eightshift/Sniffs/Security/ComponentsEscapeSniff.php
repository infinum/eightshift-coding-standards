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

namespace EightshiftCS\Eightshift\Sniffs\Security;

use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\UseStatements;
use WordPressCS\WordPress\Sniffs\Security\EscapeOutputSniff;

/**
 * Override the WordPress.Security.EscapeOutput sniff
 *
 * This sniff will ignore escaping errors whenever it finds the
 * EightshiftLibs\Helpers\Components::render or
 * EightshiftLibs\Helpers\Components::outputCssVariables methods.
 *
 * These methods are considered safe because the components
 * should be properly escaped on the output.
 */
class ComponentsEscapeSniff extends EscapeOutputSniff
{
	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_token($stackPtr)
	{
		$tokens = $this->tokens;
		$phpcsFile = $this->phpcsFile;

		// This one catches namespace separators. We don't need those.
		$clsPtr = $phpcsFile->findNext(\T_WHITESPACE, ($stackPtr + 1), null, true, null, true);

		// We are interested in classes.
		if ($tokens[$clsPtr]['code'] === \T_DOUBLE_COLON) {

			// Find the method name. If the method name is render or outputCssVariables,
			// try to resolve the class name.
			// If the class name is not Components, bail out. If it is, check if it's imported
			// or fully qualified. If it's not, bail out.
			$methodNamePtr = $phpcsFile->findNext(\T_STRING, ($clsPtr + 1), null, false, null, true);

			if ($tokens[$methodNamePtr]['content'] === 'render' || $tokens[$methodNamePtr]['content'] === 'outputCssVariables') {

				$nameEnd = $phpcsFile->findPrevious(\T_STRING, ($methodNamePtr - 1));
				$nameStart = ($phpcsFile->findPrevious([\T_STRING, \T_NS_SEPARATOR, \T_NAMESPACE], ($nameEnd - 1), null, true, null, true) + 1);
				$className = GetTokensAsString::normal($phpcsFile, $nameStart, $nameEnd);

				// Check if it's fully qualified, or if it's imported. If not, we should throw error.
				if (!empty($className)) {
					if ($this->checkIfImportExists($methodNamePtr) && $className === 'Components') {
						return; // Stop processing the sniff, all is ok!
					} elseif (strpos($className, 'EightshiftLibs\\Helpers\\Components') !== false) {
						// Ok, because the name is fully qualified.
						// Even though this ruleset forbids FQCN, and enforces imports.
						return;
					} else {
						// Process the sniff as usual.
						return parent::process_token($stackPtr);
					}
				} else {
					// Process the sniff as usual.
					return parent::process_token($stackPtr);
				}
			} else {
				// Process the sniff as usual.
				return parent::process_token($stackPtr);
			}
		}
	}

	/**
	 * Checks if the import statement exists in the current file, for the given stack pointer
	 * @param $stackPtr
	 * @return bool
	 */
	private function checkIfImportExists($stackPtr): bool
	{
		$tokens = $this->tokens;
		$phpcsFile = $this->phpcsFile;

		$importExists = false;

		// Check if the correct import exists at the top of the file.
		$importPtr = $phpcsFile->findPrevious(\T_USE, ($stackPtr - 1), null, false, null, false);

		if ($tokens[$importPtr]['code'] === \T_USE) {
			if (UseStatements::isImportUse($phpcsFile, $importPtr)) {
				$importInfo = UseStatements::splitImportUseStatement($phpcsFile, $importPtr);

				if (!empty($importInfo)) {
					foreach ($importInfo['name'] as $fullyQualifiedClassName) {
						if (strpos($fullyQualifiedClassName, 'EightshiftLibs\\Helpers\\Components') !== false) {
							$importExists = true;
							break;
						}
					}
				}
			}
		}

		return $importExists;
	}
}
