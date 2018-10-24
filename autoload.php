<?php
/**
 * WPThemeReview Coding Standard autoload file.
 *
 * Taken from WPThemeReview. Needed because our custom sniffs are extending WPCS sniffs.
 *
 * @see https://github.com/WPTRT/WPThemeReview/blob/develop/autoload.php
 *
 * @package Infinum\coding-standards-wp
 * @link    https://github.com/infinum/coding-standards-wp
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @since   TRTCS 0.1.0 Adjusted for use in the WPThemeReview standard.
 * @since   Infinum 0.4.0 Added to the Infinum Coding standards for WordPress.
 */

namespace Infinum;

$ds           = DIRECTORY_SEPARATOR;
$project_root = __DIR__ . $ds;

/*
 * Load the WPCS autoload file.
 */
// Get the WPCS dir from an environment variable.
$wpcs_dir                   = getenv( 'WPCS_DIR' );
$composer_wpcs_path         = $project_root . 'vendor' . $ds . 'wp-coding-standards' . $ds . 'wpcs';
$composer_wpcs_path_project = $project_root . '..' . $ds . '..' . $ds . 'wp-coding-standards' . $ds . 'wpcs';
if ( false === $wpcs_dir && is_dir( $composer_wpcs_path ) ) {
  // WPCS installed via Composer.
  $wpcs_dir = $composer_wpcs_path;
} elseif ( false === $wpcs_dir && is_dir( $composer_wpcs_path_project ) ) {
  // TRTCS + WPCS installed via Composer.
  $wpcs_dir = $composer_wpcs_path_project;
} elseif ( false !== $wpcs_dir ) {
  /*
   * WPCS in a custom directory [1].
   * For this to work, the `WPCS_DIR` needs to be set as an environment variable.
   */
  $wpcs_dir = realpath( $wpcs_dir );
} elseif ( file_exists( $project_root . '.pathtowpcs' ) ) {
  /*
   * WPCS in a custom directory [2].
   * For this to work, a file called `.pathtowpcs` needs to be placed in the project
   * root directory. The only content in the file should be the absolute path to
   * the developers WPCS install.
   */
  $wpcs_path = file_get_contents( $project_root . '.pathtowpcs' );
  if ( false !== $wpcs_path ) {
    $wpcs_path = trim( $wpcs_path );
    if ( file_exists( $wpcs_path ) ) {
      $wpcs_dir = realpath( $wpcs_path );
    }
  }
}
// Try and load the WPCS class aliases file.
if ( false !== $wpcs_dir && file_exists( $wpcs_dir . $ds . 'WordPress' . $ds . 'PHPCSAliases.php' ) ) {
  require_once $wpcs_dir . $ds . 'WordPress' . $ds . 'PHPCSAliases.php';
} else {
  echo 'Uh oh... can\'t find WPCS.
If you use Composer, please run `composer install`.
Otherwise, make sure you set a `WPCS_DIR` environment variable
pointing to the WPCS directory.
';
  die( 1 );
}
// Clean up.
unset( $ds, $project_root, $wpcs_dir, $composer_wpcs_path, $composer_wpcs_path_project, $wpcs_path );

