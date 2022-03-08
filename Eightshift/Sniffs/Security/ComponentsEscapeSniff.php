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
		$importData = [];
		$importExists = false;

		// Check if the current token is a part of the import, if it is, skip the check.
		$useToken = $phpcsFile->findPrevious(\T_USE, ($stackPtr - 1), null, false, null, false);

		if ($useToken) {
			$endOfUse = $phpcsFile->findNext(\T_SEMICOLON, $useToken, null, false, null, false);

			// Ignore all the tokens that are a part of the import statement. Every import statement ends in the semicolon.
			if ($stackPtr <= $endOfUse) {
				return;
			}

			$importData = $this->checkIfImportInformation($stackPtr);
			$importExists = $importData['importExists'];
		}

		if ($tokens[$stackPtr]['code'] === \T_ECHO) {
			// Check the next token after echo.
			$elementPtr = $phpcsFile->findNext(\T_WHITESPACE, ($stackPtr + 1), null, true, null, false);

			// If it's not the string token, move on, we're only interested in Components string.
			if ($tokens[$elementPtr]['code'] !== \T_STRING) {
				return parent::process_token($stackPtr);
			}

			// Check for Components string token.
			$componentsClassNamePtr = $phpcsFile->findNext(\T_STRING, ($stackPtr + 1), null, false, 'Components', false);

			if (!$componentsClassNamePtr) {
				// If there is no Components down the line, just run the regular sniff.
				return parent::process_token($stackPtr);
			}

			// Check if the next token is double colon. We are interested in static methods.
			if ($tokens[$componentsClassNamePtr + 1]['code'] !== \T_DOUBLE_COLON) {
				$echoPtr = $phpcsFile->findPrevious(\T_ECHO, ($componentsClassNamePtr - 1), null, false, null, true);

				return parent::process_token($echoPtr);
			}

			// If it is, check, if the class is imported or fully qualified.
			$nameEnd = $phpcsFile->findPrevious(\T_STRING, ($componentsClassNamePtr + 1));
			$nameStart = ($phpcsFile->findPrevious(
				[\T_STRING, \T_NS_SEPARATOR, \T_NAMESPACE],
				($nameEnd - 1),
				null,
				true,
				null,
				true
			) + 1);

			$className = GetTokensAsString::normal($phpcsFile, $nameStart, $nameEnd);

			if ($importExists) {
				// Fully qualified import, i.e. EightshiftLibs\Helpers\Components.
				if ($importData['fullImportExists']) {
					// Components name is ok, \Components is not ok, \Anything\Components is not ok FQCN is ok.
					if (
						$className === 'Components' ||
						\strpos($className, 'EightshiftLibs\\Helpers\\Components') !== false
					) {
						// Check the static method name.
						$methodNamePtr = $phpcsFile->findNext(
							\T_STRING,
							($componentsClassNamePtr + 1),
							null,
							false,
							null,
							true
						);

						if (
							$tokens[$methodNamePtr]['content'] === 'render' ||
							$tokens[$methodNamePtr]['content'] === 'outputCssVariables'
						) {
							return; // Skip sniffing allowed methods.
						} else {
							// Not allowed method, continue as usual.
							$echoPtr = $phpcsFile->findPrevious(
								\T_ECHO,
								($componentsClassNamePtr - 1),
								null,
								false,
								null,
								true
							);

							return parent::process_token($echoPtr);
						}
					} else {
						// Some other class we don't care about.
						$echoPtr = $phpcsFile->findPrevious(
							\T_ECHO,
							($componentsClassNamePtr - 1),
							null,
							false,
							null,
							true
						);

						return parent::process_token($echoPtr);
					}
				} else {
					// Partial import, Check if the last part of the import exists at the beginning of the class name.
					$import = \explode('\\', $importData['importName'] ?? '');
					$lastNamespacePart = \end($import);

					$checkedClassName = \explode('\\', $className);
					$firstNamespacePart = $checkedClassName[0];

					if ($lastNamespacePart === $firstNamespacePart) {
						// Correctly used class name.
						$methodNamePtr = $phpcsFile->findNext(
							\T_STRING,
							($componentsClassNamePtr + 1),
							null,
							false,
							null,
							true
						);

						if (
							$tokens[$methodNamePtr]['content'] === 'render' ||
							$tokens[$methodNamePtr]['content'] === 'outputCssVariables'
						) {
							return; // Skip sniffing allowed methods.
						} else {
							// Not allowed method, continue as usual.
							$echoPtr = $phpcsFile->findPrevious(
								\T_ECHO,
								($componentsClassNamePtr - 1),
								null,
								false,
								null,
								true
							);

							return parent::process_token($echoPtr);
						}
					} else {
						// Wrongly imported, or class that is not related to the libs.
						$echoPtr = $phpcsFile->findPrevious(
							\T_ECHO,
							($componentsClassNamePtr - 1),
							null,
							false,
							null,
							true
						);

						return parent::process_token($echoPtr);
					}
				}
			} else {
				// Check if the class name is fully qualified and contains the helper part.
				if (\strpos($className, 'EightshiftLibs\\Helpers\\Components') !== false) {
					$methodNamePtr = $phpcsFile->findNext(
						\T_STRING,
						($componentsClassNamePtr + 1),
						null,
						false,
						null,
						true
					);

					if (
						$tokens[$methodNamePtr]['content'] === 'render' ||
						$tokens[$methodNamePtr]['content'] === 'outputCssVariables'
					) {
						return; // Skip sniffing allowed methods.
					} else {
						// Not allowed method, continue as usual.
						$echoPtr = $phpcsFile->findPrevious(
							\T_ECHO,
							($componentsClassNamePtr - 1),
							null,
							false,
							null,
							true
						);

						return parent::process_token($echoPtr);
					}
				} else {
					$echoPtr = $phpcsFile->findPrevious(
						\T_ECHO,
						($componentsClassNamePtr - 1),
						null,
						false,
						null,
						true
					);

					return parent::process_token($echoPtr);
				}
			}
		}

		// Process the sniff as usual.
		return parent::process_token($stackPtr);
	}

	/**
	 * Checks if the import statement exists in the current file, for the given stack pointer
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return array<string, bool|string> Information about the imports
	 */
	private function checkIfImportInformation($stackPtr): array
	{
		$tokens = $this->tokens;
		$phpcsFile = $this->phpcsFile;

		$importData = [
			'importExists' => false,
			'fullImportExists' => false
		];

		// Check if the correct import exists at the top of the file.
		$importPtr = $phpcsFile->findPrevious(\T_USE, ($stackPtr - 1), null, false, null, false);

		if ($tokens[$importPtr]['code'] === \T_USE) {
			if (UseStatements::isImportUse($phpcsFile, $importPtr)) {
				$importInfo = UseStatements::splitImportUseStatement($phpcsFile, $importPtr);

				if (!empty($importInfo)) {
					foreach ($importInfo['name'] as $fullyQualifiedClassNameImport) {
						// Check for partial import.
						if (\strpos($fullyQualifiedClassNameImport, 'EightshiftLibs\\Helpers') !== false) {
							$importData['importExists'] = true;
							$importData['importName'] = $fullyQualifiedClassNameImport;

							// Check for fully qualified import.
							if (\strpos($fullyQualifiedClassNameImport, 'EightshiftLibs\\Helpers\\Components') !== false) {
								$importData['fullImportExists'] = true;
								$importData['importName'] = $fullyQualifiedClassNameImport;

								break;
							}

							break;
						}
					}
				}
			}
		}

		return $importData;
	}
}
