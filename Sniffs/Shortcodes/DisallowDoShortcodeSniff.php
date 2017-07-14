<?php

namespace Sniffs\Shortcodes;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_Tokens;

/**
 * Ensures do_shortcode() function is not being used.
 */
class DisallowDoShortcodeSniff implements PHP_CodeSniffer_Sniff {
  /**
   * Returns an array of tokens this test wants to listen for.
   *
   * @return array
   */
  public function register() {

    $tokens = PHP_CodeSniffer_Tokens::$functionNameTokens;

    return $tokens;
  }


  /**
   * Processes this sniff, when one of its tokens is encountered.
   *
   * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
   * @param int                  $stackPtr  The position of the current token in
   *                                        the token stack.
   *
   * @return void
   */
  public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

    $tokens = $phpcsFile->getTokens();
    $token  = $tokens[ $stackPtr ];

    if ( preg_match( '`do_shortcode`im', $token['content'] ) > 0 ) {
      $phpcsFile->addWarning( 'Do not include do_shortcode() function in theme files. Use shortcode callback function instead.' , $stackPtr, 'do_shortcodeDetected' );
    }
  }

}