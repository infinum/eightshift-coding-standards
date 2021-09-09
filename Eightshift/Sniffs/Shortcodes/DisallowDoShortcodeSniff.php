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
 * @since 1.0.0 Removed the Tokens util. Modified the warning code
 * @since 0.4.2 Renamed the WPCS namespace - changed in v2.0.0 of WPCS
 * @since 0.3.0 Updated sniff to be compatible with latest PHPCS and WPCS
 * @since 0.1.0
 */

namespace EightshiftCS\Eightshift\Sniffs\Shortcodes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPCSUtils\Utils\TextStrings;

/**
 * Ensures do_shortcode() function is not being used.
 *
 * Don't use do_shortcode when you can use your callback function directly,
 * which is much more efficient.
 *
 * @link https://konstantin.blog/2013/dont-do_shortcode/
 */
class DisallowDoShortcodeSniff implements Sniff
{
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return [
			\T_STRING,
			\T_CONSTANT_ENCAPSED_STRING,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int $stackPtr  The position of the current token in the stack.
	 *
	 * @return void
	 */
	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens  = $phpcsFile->getTokens();
		$content = \strtolower($tokens[$stackPtr]['content']);
		if ($tokens[$stackPtr]['code'] === \T_CONSTANT_ENCAPSED_STRING) {
			$content = TextStrings::stripQuotes($content);
		}

		if ($content === 'do_shortcode') {
			$phpcsFile->addWarning(
				'Do not include do_shortcode() function in theme files. Use shortcode callback function instead.',
				$stackPtr,
				'shortcodeUsageDetected'
			);
		}
	}
}
