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

namespace EightshiftCS\Eightshift\Sniffs\Security;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\UseStatements;
use WordPressCS\WordPress\Sniffs\Security\EscapeOutputSniff;

/**
 * Override the WordPress.Security.EscapeOutput sniff
 *
 * This sniff will ignore escaping errors whenever it finds the
 * EightshiftLibs\Helpers\Helpers::$allowedMethods, where the $allowedMethods are by default `render` and `outputCssVariables`, but can be extended in the ruleset.
 *
 * $allowedMethods are considered safe.
 *
 * @since 2.0.0 Add list of allowed static methods that shouldn't trigger the sniff error.
 * @since 1.4.0
 */
class HelpersEscapeSniff extends EscapeOutputSniff
{
	/**
	 * A fully qualified class name for Helpers class override.
	 *
	 * You should put the fully qualified class name of the class you used
	 * to override the EightshiftLibs\Helpers\Component class.
	 *
	 * For Example: namespace\\SomeSubNamespace\\MyHelpers.
	 *
	 * @since 1.4.0
	 *
	 * @var string Defaults to empty string.
	 */
	public string $overriddenClass = '';

	/**
	 * List of allowed methods that won't trigger the EscapeOutput error.
	 *
	 * @since 2.0.0
	 *
	 * @var array<string, string>
	 */
	public array $allowedMethods = [
		'render' => 'render',
		'outputCssVariables' => 'outputCssVariables',
	];

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @since 1.4.0
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
		$importData = [
			'importExists' => false,
			'fullImportExists' => false
		];
		$importExists = false;

		// Check if the current token is a part of the import, if it is, skip the check.
		$useToken = $phpcsFile->findPrevious(\T_USE, ($stackPtr - 1), null, false, null, false);

		if ($useToken) {
			// Find all use tokens.
			foreach ($tokens as $tokenPtr => $token) {
				if ($token['code'] === \T_USE) {
					$importData = $this->checkIfImportInformation($tokenPtr, $importData);

					if ($importData['importExists']) {
						break;
					}
				}
			}

			$importExists = $importData['importExists'];
		}

		if ($tokens[$stackPtr]['code'] === \T_ECHO) {
			// Check the next token after echo.
			$elementPtr = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);

			// If it's not the string token, move on, we're only interested in Helpers string.
			if ($tokens[$elementPtr]['code'] !== \T_STRING) {
				return parent::process_token($stackPtr);
			}

			// Check the next string token content.
			if ($importExists) {
				$fullyQualifiedImport = $importData['importName'];
				if (\strpos($fullyQualifiedImport, $tokens[$elementPtr]['content']) === false) {
					return parent::process_token($stackPtr);
				}
			}

			// Check for Helpers string token.
			$helpersClassNamePtr = $phpcsFile->findNext(\T_STRING, ($stackPtr + 1), null, false, 'Helpers');

			if (!$helpersClassNamePtr) {
				// If there is no Helpers down the line, just run the regular sniff.
				return parent::process_token($stackPtr);
			}

			// Check if the next token is double colon. We are interested in static methods.
			if ($tokens[$helpersClassNamePtr + 1]['code'] !== \T_DOUBLE_COLON) {
				$echoPtr = $phpcsFile->findPrevious(\T_ECHO, ($helpersClassNamePtr - 1), null, false, null, true);

				return parent::process_token($echoPtr);
			}

			// If it is, check, if the class is imported or fully qualified.
			$nameEnd = $phpcsFile->findPrevious(\T_STRING, ($helpersClassNamePtr + 1));
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
				// Fully qualified import, i.e. EightshiftLibs\Helpers\Helpers.
				if ($importData['fullImportExists']) {
					// Helpers name is ok, \Helpers is not ok, \Anything\Helpers is not ok FQCN is ok.
					if (
						$className === 'Helpers'
						|| \strpos($className, 'EightshiftLibs\\Helpers\\Helpers') !== false
						|| (! empty($this->overriddenClass) && \strpos($className, $this->overriddenClass) !== false)
					) {
						// Check the static method name.
						$methodNamePtr = $phpcsFile->findNext(
							\T_STRING,
							($helpersClassNamePtr + 1),
							null,
							false,
							null,
							true
						);

						if (\in_array($tokens[$methodNamePtr]['content'], $this->allowedMethods, true)) {
							return; // Skip sniffing allowed methods.
						} else {
							// Not allowed method, continue as usual.
							$echoPtr = $this->getEchoToken($helpersClassNamePtr);

							return parent::process_token($echoPtr);
						}
					} else {
						// Some other class we don't care about.
						$echoPtr = $this->getEchoToken($helpersClassNamePtr);

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
							($helpersClassNamePtr + 1),
							null,
							false,
							null,
							true
						);

						if (\in_array($tokens[$methodNamePtr]['content'], $this->allowedMethods, true)) {
							return; // Skip sniffing allowed methods.
						} else {
							// Not allowed method, continue as usual.
							$echoPtr = $this->getEchoToken($helpersClassNamePtr);

							return parent::process_token($echoPtr);
						}
					} else {
						// Wrongly imported, or class that is not related to the libs.
						$echoPtr = $this->getEchoToken($helpersClassNamePtr);

						return parent::process_token($echoPtr);
					}
				}
			} else {
				// Check if the class name is fully qualified and contains the helper part.
				if (
					\strpos($className, 'EightshiftLibs\\Helpers\\Helpers') !== false
					|| (! empty($this->overriddenClass) && \strpos($className, $this->overriddenClass) !== false)
				) {
					$methodNamePtr = $phpcsFile->findNext(
						\T_STRING,
						($helpersClassNamePtr + 1),
						null,
						false,
						null,
						true
					);

					if (\in_array($tokens[$methodNamePtr]['content'], $this->allowedMethods, true)) {
						return; // Skip sniffing allowed methods.
					} else {
						// Not allowed method, continue as usual.
						$echoPtr = $this->getEchoToken($helpersClassNamePtr);

						return parent::process_token($echoPtr);
					}
				} else {
					$echoPtr = $this->getEchoToken($helpersClassNamePtr);

					return parent::process_token($echoPtr);
				}
			}
		}

		// Process the sniff as usual.
		return parent::process_token($stackPtr);
	}

	/**
	 * Checks if the import statement exists in the current file, for the given stack pointer.
	 *
	 * @since 1.4.0
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 * @param String[] $importData Import data array.
	 *
	 * @return array<string, bool|string> Information about the imports
	 */
	private function checkIfImportInformation(int $stackPtr, array $importData): array
	{
		$phpcsFile = $this->phpcsFile;

		$overriddenClass = \str_replace('\\\\', '\\', $this->overriddenClass);

		if (UseStatements::isImportUse($phpcsFile, $stackPtr)) {
			$importInfo = UseStatements::splitImportUseStatement($phpcsFile, $stackPtr);

			if (!empty($importInfo)) {
				foreach ($importInfo['name'] as $fullyQualifiedClassNameImport) {
					if (
						\strpos($fullyQualifiedClassNameImport, 'EightshiftLibs\\Helpers') !== false
						|| (! empty($overriddenClass) && \strpos($fullyQualifiedClassNameImport, $overriddenClass) !== false)
					) {
						$importData['importExists'] = true;
						$importData['importName'] = $fullyQualifiedClassNameImport;

						// Check for fully qualified import.
						if (
							\strpos($fullyQualifiedClassNameImport, 'EightshiftLibs\\Helpers\\Helpers') !== false
							|| (! empty($overriddenClass) && \strpos($fullyQualifiedClassNameImport, $overriddenClass) !== false) // phpcs:ignore Generic.Files.LineLength.TooLong
						) {
							$importData['fullImportExists'] = true;
							$importData['importName'] = $fullyQualifiedClassNameImport;

							break;
						}

						break;
					}
				}
			}
		}

		return $importData;
	}

	/**
	 * Return the position of the previous echo pointer.
	 *
	 * @since 1.4.0
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return int Echo token pointer number.
	 */
	private function getEchoToken(int $stackPtr): int
	{
		return $this->phpcsFile->findPrevious(\T_ECHO, ($stackPtr - 1), null, false, null, true);
	}
}
