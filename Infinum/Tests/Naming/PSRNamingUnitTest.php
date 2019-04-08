<?php
/**
 * Unit test class for PSRNaming.
 *
 * @package Infinum\Tests\Naming
 * @link    https://github.com/infinum/coding-standards-wp
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Infinum\Tests\Naming;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the PSRNaming sniff.
 *
 * @since 0.4.0
 */
class FileIncludeUnitTest extends AbstractSniffUnitTest {

  /**
   * Returns the lines where errors should occur.
   *
   * @return array <int line number> => <int number of errors>
   */
  public function getErrorList() {
    return array(
        10 => 1,
        12 => 1,
        17 => 1,
        18 => 4,
        24 => 3,
    );
  }

  /**
   * Returns the lines where warnings should occur.
   *
   * @return array <int line number> => <int number of warnings>
   */
  public function getWarningList() {
    return array();
  }
}
