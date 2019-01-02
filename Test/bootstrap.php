<?php
/**
 * Bootstrap file for running the tests.
 *
 * Taken from WPThemeReview
 *
 * @see https://github.com/WPTRT/WPThemeReview/blob/develop/Test/bootstrap.php
 *
 * @package Infinum\coding-standards-wp
 * @link    https://github.com/infinum/coding-standards-wp
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @since   Infinum 0.4.2 removed the autoload.php since it's not needed on WPCS 2.0.0
 * @since   WPCS 0.13.0
 * @since   TRTCS 0.1.0 Adjusted for use in the WPThemeReview standard.
 * @since   Infinum 0.4.0 Added to the Infinum Coding standards for WordPress.
 */

if ( ! \defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
  define( 'PHP_CODESNIFFER_IN_TESTS', true );
}

$ds       = DIRECTORY_SEPARATOR;
$root_dir = dirname( __DIR__ ) . $ds;

/*
 * Load the necessary PHPCS files.
 */
// Get the PHPCS dir from an environment variable.
$phpcs_dir           = getenv( 'PHPCS_DIR' );
$composer_phpcs_path = $root_dir . 'vendor' . $ds . 'squizlabs' . $ds . 'php_codesniffer';

if ( $phpcs_dir === false && is_dir( $composer_phpcs_path ) ) {
  // PHPCS installed via Composer.
  $phpcs_dir = $composer_phpcs_path;
} elseif ( $phpcs_dir !== false ) {
  /*
   * PHPCS in a custom directory.
   * For this to work, the `PHPCS_DIR` needs to be set in a custom `phpunit.xml` file.
   */
  $phpcs_dir = realpath( $phpcs_dir );
}

// Try and load the PHPCS autoloader.
if ( $phpcs_dir !== false
  && file_exists( $phpcs_dir . $ds . 'autoload.php' )
  && file_exists( $phpcs_dir . $ds . 'tests' . $ds . 'bootstrap.php' )
) {
  require_once $phpcs_dir . $ds . 'autoload.php';
  require_once $phpcs_dir . $ds . 'tests' . $ds . 'bootstrap.php'; // PHPUnit 6.x+ support.
} else {
  echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.

Please read the contributors guidelines for more information:
https://is.gd/contributing2WPCS
';

  die( 1 );
}

// Clean up.
unset( $ds, $root_dir, $phpcs_dir, $composer_phpcs_path );
