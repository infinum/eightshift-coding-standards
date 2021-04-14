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
 * @since 1.2.0
 */

namespace EightshiftCS\Eightshift\Sniffs\Security;

use PHP_CodeSniffer\Util\Tokens;
use WordPressCS\WordPress\Sniffs\Security\EscapeOutputSniff;

/**
 * Escapes all strings with custom exceptions
 *
 * The EscapeOutputSniff has customizable properties added to it, but there is an issue
 * with the sniff at the moment. Because of the issue, it's not possible to specify
 * static methods as custom escape functions in the sniff.
 *
 * Eightshift libs depend heavily on the Components::render method which will echo out
 * a specific component. This output must not be escaped, as that may cause breakage in the
 * output (stripped attributes, etc.).
 *
 * This sniff will extend the original one, but allow this specific method to be marked as safe.
 * Other helpers such as Components::classnames, or Components::ensureString should still be escaped.
 *
 * @link https://github.com/WordPress/WordPress-Coding-Standards/issues/1176#issuecomment-784045848
 */
class CustomEscapeOutputSniff extends EscapeOutputSniff
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
		$this->mergeFunctionLists();

		$function = $this->tokens[$stackPtr]['content'];

		// Find the opening parenthesis (if present; T_ECHO might not have it).
		$openParen = $this->phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);

		// If function, not T_ECHO nor T_PRINT.
		if (\T_STRING === $this->tokens[$stackPtr]['code']) {
			// Skip if it is a function but is not one of the printing functions.
			if (!isset($this->printingFunctions[$this->tokens[$stackPtr]['content']])) {
				return;
			}

			if (isset($this->tokens[$openParen]['parenthesis_closer'])) {
				$endOfStatement = $this->tokens[$openParen]['parenthesis_closer'];
			}

			// These functions only need to have the first argument escaped.
			if (\in_array($function, ['trigger_error', 'user_error'], true)) {
				$firstParam = $this->get_function_call_parameter($stackPtr, 1);
				$endOfStatement = ($firstParam['end'] + 1);
				unset($firstParam);
			}

			/*
			 * If the first param to `_deprecated_file()` follows the typical `basename( __FILE__ )`
			 * pattern, it doesn't need to be escaped.
			 */
			if ('_deprecated_file' === $function) {
				$firstParam = $this->get_function_call_parameter($stackPtr, 1);

				// Quick check. This disregards comments.
				if (preg_match('`^basename\s*\(\s*__FILE__\s*\)$`', $firstParam['raw']) === 1) {
					$stackPtr = ($firstParam['end'] + 2);
				}
				unset($firstParam);
			}
		}

		if (isset($this->unsafePrintingFunctions[$function])) {
			$error = $this->phpcsFile->addError(
				"All output should be run through an escaping function (like %s), found '%s'.",
				$stackPtr,
				'UnsafePrintingFunction',
				[$this->unsafePrintingFunctions[$function], $function]
			);

			// If the error was reported, don't bother checking the function's arguments.
			if ($error) {
				return $endOfStatement ?? null;
			}
		}

		$ternary = false;

		// This is already determined if this is a function and not T_ECHO.
		if (!isset($endOfStatement)) {
			$endOfStatement = $this->phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], $stackPtr);
			$lastToken = $this->phpcsFile->findPrevious(Tokens::$emptyTokens, ($endOfStatement - 1), null, true);

			// Check for the ternary operator. We only need to do this here if this
			// echo is lacking parenthesis. Otherwise it will be handled below.
			if (
				\T_OPEN_PARENTHESIS !== $this->tokens[$openParen]['code'] ||
				\T_CLOSE_PARENTHESIS !== $this->tokens[$lastToken]['code']
			) {
				$ternary = $this->phpcsFile->findNext(\T_INLINE_THEN, $stackPtr, $endOfStatement);

				// If there is a ternary skip over the part before the ?. However, if
				// the ternary is within parentheses, it will be handled in the loop.
				if (false !== $ternary && empty($this->tokens[$ternary]['nested_parenthesis'])) {
					$stackPtr = $ternary;
				}
			}
		}

		// Ignore the function itself.
		$stackPtr++;

		$inCast = false;

		// Looping through echo'd components.
		$watch = true;
		for ($i = $stackPtr; $i < $endOfStatement; $i++) {
			// Ignore whitespaces and comments.
			if (isset(Tokens::$emptyTokens[$this->tokens[$i]['code']])) {
				continue;
			}

			// Ignore namespace separators.
			if (\T_NS_SEPARATOR === $this->tokens[$i]['code']) {
				continue;
			}

			if (\T_OPEN_PARENTHESIS === $this->tokens[$i]['code']) {
				if (!isset($this->tokens[$i]['parenthesis_closer'])) {
					// Live coding or parse error.
					break;
				}

				if ($inCast) {
					// Skip to the end of a function call if it has been casted to a safe value.
					$i = $this->tokens[$i]['parenthesis_closer'];
					$inCast = false;
				} else {
					// Skip over the condition part of a ternary (i.e., to after the ?).
					$ternary = $this->phpcsFile->findNext(\T_INLINE_THEN, $i, $this->tokens[$i]['parenthesis_closer']);

					if (false !== $ternary) {
						$nextParen = $this->phpcsFile->findNext(
							\T_OPEN_PARENTHESIS,
							($i + 1),
							$this->tokens[$i]['parenthesis_closer']
						);

						// We only do it if the ternary isn't within a subset of parentheses.
						if (
							false === $nextParen ||
							(
								isset($this->tokens[$nextParen]['parenthesis_closer']) &&
								$ternary > $this->tokens[$nextParen]['parenthesis_closer']
							)
						) {
							$i = $ternary;
						}
					}
				}

				continue;
			}

			// Handle arrays for those functions that accept them.
			if (\T_ARRAY === $this->tokens[$i]['code']) {
				$i++; // Skip the opening parenthesis.
				continue;
			}

			if (
				\T_OPEN_SHORT_ARRAY === $this->tokens[$i]['code']
				|| \T_CLOSE_SHORT_ARRAY === $this->tokens[$i]['code']
			) {
				continue;
			}

			if (\in_array($this->tokens[$i]['code'], [\T_DOUBLE_ARROW, \T_CLOSE_PARENTHESIS], true)) {
				continue;
			}

			// Handle magic constants for debug functions.
			if (isset($this->magic_constant_tokens[$this->tokens[$i]['type']])) { // phpcs:ignore
				continue;
			}

			// Handle safe PHP native constants.
			if (
				\T_STRING === $this->tokens[$i]['code'] &&
				isset($this->safe_php_constants[$this->tokens[$i]['content']]) && // phpcs:ignore
				$this->is_use_of_global_constant($i)
			) {
				continue;
			}

			// Wake up on concatenation characters, another part to check.
			if (\T_STRING_CONCAT === $this->tokens[$i]['code']) {
				$watch = true;
				continue;
			}

			// Wake up after a ternary else (:).
			if (false !== $ternary && \T_INLINE_ELSE === $this->tokens[$i]['code']) {
				$watch = true;
				continue;
			}

			// Wake up for commas.
			if (\T_COMMA === $this->tokens[$i]['code']) {
				$inCast = false;
				$watch = true;
				continue;
			}

			if (false === $watch) {
				continue;
			}

			// Allow T_CONSTANT_ENCAPSED_STRING eg: echo 'Some String';
			// Also T_LNUMBER, e.g.: echo 45; exit -1; and booleans.
			if (isset($this->safe_components[$this->tokens[$i]['type']])) { // phpcs:ignore
				continue;
			}

			$watch = false;

			// Allow int/double/bool casted variables.
			if (isset($this->safe_casts[$this->tokens[$i]['code']])) { // phpcs:ignore
				$inCast = true;
				continue;
			}

			// Now check that next token is a function call.
			if (\T_STRING === $this->tokens[$i]['code']) {
				$ptr = $i;
				$functionName = $this->tokens[$i]['content'];
				$functionOpener = $this->phpcsFile->findNext(\T_OPEN_PARENTHESIS, ($i + 1), null, false, null, true);
				$isFormattingFunction = isset($this->formattingFunctions[$functionName]);

				if (false !== $functionOpener) {
					if (isset($this->arrayWalkingFunctions[$functionName])) {
						// Get the callback parameter.
						$callback = $this->get_function_call_parameter(
							$ptr,
							$this->arrayWalkingFunctions[$functionName]
						);

						if (!empty($callback)) {
							/*
							 * If this is a function callback (not a method callback array) and we're able
							 * to resolve the function name, do so.
							 */
							$mappedFunction = $this->phpcsFile->findNext(
								Tokens::$emptyTokens,
								$callback['start'],
								($callback['end'] + 1),
								true
							);

							if (
								false !== $mappedFunction &&
								\T_CONSTANT_ENCAPSED_STRING === $this->tokens[$mappedFunction]['code']
							) {
								$functionName = $this->strip_quotes($this->tokens[$mappedFunction]['content']);
								$ptr = $mappedFunction;
							}
						}
					}

					// Skip pointer to after the function.
					// If this is a formatting function we just skip over the opening
					// parenthesis. Otherwise we skip all the way to the closing.
					if ($isFormattingFunction) {
						$i = ($functionOpener + 1);
						$watch = true;
					} else {
						if (isset($this->tokens[$functionOpener]['parenthesis_closer'])) {
							$i = $this->tokens[$functionOpener]['parenthesis_closer'];
						} else {
							// Live coding or parse error.
							break;
						}
					}
				}

				// If this is a safe function, we don't flag it.
				if (
					$isFormattingFunction
					|| isset($this->autoEscapedFunctions[$functionName])
					|| isset($this->escapingFunctions[$functionName])
				) {
					continue;
				}

				$content = $functionName;
			} else {
				$content = $this->tokens[$i]['content'];
				$ptr = $i;
			}

			$componentsFound = false;
			if ($content === 'Components') {
				$colon = $this->phpcsFile->findNext(Tokens::$emptyTokens, ($ptr + 1), null, true);
				$componentsFound = true;

				if ($this->tokens[$colon]['code'] === \T_DOUBLE_COLON) {
					// This is a static call to a Components class method.
					$componentsMethodPtr = $this->phpcsFile->findNext(\T_STRING, ($colon + 1));

					// Check if the call is to a render method.
					if ($this->tokens[$componentsMethodPtr]['content'] === 'render') {
						continue;
					}
				}
			}

			// Make the error message a little more informative for array access variables.
			if (\T_VARIABLE === $this->tokens[$ptr]['code']) {
				$arrayKeys = $this->get_array_access_keys($ptr);

				if (!empty($arrayKeys)) {
					$content .= '[' . implode('][', $arrayKeys) . ']';
				}
			}

			// Make error message nicer when Components helper is found.
			if ($componentsFound) {
				$methodName = $this->tokens[$componentsMethodPtr]['content'];
				$content = "{$content}::{$methodName}";
			}

			$this->phpcsFile->addError(
				"All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found '%s'.", // phpcs:ignore
				$ptr,
				'OutputNotEscaped',
				[$content]
			);
		}

		return $endOfStatement;
	}
}
