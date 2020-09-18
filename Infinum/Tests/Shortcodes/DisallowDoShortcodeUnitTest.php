<?php
/**
 * Unit test class for DisallowDoShortcode Standard.
 *
 * @package Infinum\Tests\Shortcodes
 * @link    https://github.com/infinum/coding-standards-wp
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Infinum\Tests\Shortcodes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the DisallowDoShortcode sniff.
 *
 * @since 1.0.0 Added $testFile parameter.
 * @since 0.4.0
 */
class DisallowDoShortcodeUnitTest extends AbstractSniffUnitTest {

  /**
   * Returns the lines where errors should occur.
   *
   * @return array <int line number> => <int number of errors>
   */
  public function getErrorList() {
    return array();
  }

  /**
   * Returns the lines where warnings should occur.
   *
   * @return array <int line number> => <int number of warnings>
   */
  public function getWarningList() {
    return array(
        4 => 1,
        5 => 1,
        6 => 1,
        7 => 1,
        8 => 1,
        9 => 1,
    );
  }
}
