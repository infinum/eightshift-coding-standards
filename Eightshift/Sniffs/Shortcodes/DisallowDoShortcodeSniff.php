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

use PHPCSUtils\Utils\TextStrings;
use WordPressCS\WordPress\Sniff;

/**
 * Ensures do_shortcode() function is not being used.
 *
 * Don't use do_shortcode when you can use your callback function directly,
 * which is much more efficient.
 *
 * @since 1.4.0 Updated the sniff
 *              Make the sniff extends the WPCS Sniff class so that we can
 *              catch the cases of namespaced and static calls which would
 *              have been a false positives in the old sniff.
 *              The native WPCS Sniff class has two useful helper methods
 *              for detecting this.
 * @since 0.1.0
 *
 * @link https://konstantin.blog/2013/dont-do_shortcode/
 */
class DisallowDoShortcodeSniff extends Sniff
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
			\T_DOUBLE_COLON,
			\T_NS_SEPARATOR
		];
	}

	/**
	 * Processes a sniff when one of its tokens is encountered.
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
		$content = \strtolower($this->tokens[$stackPtr]['content']);
		if ($this->tokens[$stackPtr]['code'] === \T_CONSTANT_ENCAPSED_STRING) {
			$content = TextStrings::stripQuotes($content);
		}

		// Check for namespaced function named do_shortcode.
		if ($this->is_token_namespaced($stackPtr)) {
			return;
		}

		// If the do_shortcode is a static method of some class, it's also ok.
		if ($this->is_class_object_call($stackPtr)) {
			return;
		}

		if ($content === 'do_shortcode') {
			$this->phpcsFile->addWarning(
				'Do not include do_shortcode() function in theme files. Use shortcode callback function instead.',
				$stackPtr,
				'shortcodeUsageDetected'
			);
		}
	}
}
