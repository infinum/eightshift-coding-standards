<?php

/**
 * Bootstrap file for running the tests.
 *
 * - Load the PHPCS PHPUnit bootstrap file providing cross-version PHPUnit support.
 *   {@link https://github.com/squizlabs/PHP_CodeSniffer/pull/1384}
 * - Load the Composer autoload file.
 * - Automatically limit the testing to the WordPressCS tests.
 *
 * Modified from https://github.com/WordPress/WordPress-Coding-Standards/blob/develop/Tests/bootstrap.php
 *
 * @package EightshiftCS
 * @author Juliette Reinders Folmer <663378+jrfnl@users.noreply.github.com>
 * @link    https://github.com/infinum/eightshift-coding-standards/
 * @license https://opensource.org/licenses/MIT MIT
 */

use PHP_CodeSniffer\Util\Standards;
use PHP_CodeSniffer\Autoload;

if (!defined('PHP_CODESNIFFER_IN_TESTS')) {
	define('PHP_CODESNIFFER_IN_TESTS', true);
}

$ds = DIRECTORY_SEPARATOR;

/*
 * Load the necessary PHPCS files.
 */
// Get the PHPCS dir from an environment variable.
$phpcsDir = getenv('PHPCS_DIR');
$composerPHPCSPath = dirname(__DIR__) . $ds . 'vendor' . $ds . 'squizlabs' . $ds . 'php_codesniffer';

if ($phpcsDir === false && is_dir($composerPHPCSPath)) {
	// PHPCS installed via Composer.
	$phpcsDir = $composerPHPCSPath;
} elseif ($phpcsDir !== false) {
	/*
	 * PHPCS in a custom directory.
	 * For this to work, the `PHPCS_DIR` needs to be set in a custom `phpunit.xml` file.
	 */
	$phpcsDir = realpath($phpcsDir);
}

// Try and load the PHPCS autoloader.
if (
	$phpcsDir !== false
	&& file_exists($phpcsDir . $ds . 'autoload.php')
	&& file_exists($phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php')
) {
	require_once $phpcsDir . $ds . 'autoload.php';
	require_once $phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php';

	// Initialize globals required by AbstractSniffUnitTest (previously set by AllSniffs.php).
	$GLOBALS['PHP_CODESNIFFER_SNIFF_CODES']      = [];
	$GLOBALS['PHP_CODESNIFFER_FIXABLE_CODES']    = [];
	$GLOBALS['PHP_CODESNIFFER_SNIFF_CASE_FILES'] = [];
	$GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS']    = [];
	$GLOBALS['PHP_CODESNIFFER_TEST_DIRS']        = [];

	// Register Eightshift test class paths so AbstractSniffUnitTest::setUpPrerequisites()
	// can resolve $standardsDir and $testsDir. This replicates what AllSniffs::suite() did
	// without the PHPUnit\TextUI\TestRunner dependency removed in PHPUnit 10.
	$installedStandards = Standards::getInstalledStandardDetails(true);

	foreach ($installedStandards as $standardDetails) {
		$standardPath = $standardDetails['path'];

		// Register ALL installed standards for autoloading so cross-standard sniff
		// dependencies (e.g. Eightshift extending WPCS sniff classes) can resolve.
		Autoload::addSearchPath($standardPath, $standardDetails['namespace']);

		$standardTestsDir = $standardPath . $ds . 'Tests' . $ds;
		if (!is_dir($standardTestsDir)) {
			continue;
		}

		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($standardTestsDir));
		foreach ($iterator as $testFile) {
			// Only register concrete *UnitTest.php files. Abstract base classes (whose
			// filenames start with "Abstract") are loaded on demand by the PHPCS autoloader
			// and must not be passed to PHPUnit or it will emit an "is abstract" warning.
			$filename = $testFile->getFilename();
			if (!str_ends_with($filename, 'UnitTest.php') || str_starts_with($filename, 'Abstract')) {
				continue;
			}
			$className = Autoload::loadFile($testFile->getPathname());
			if ($className !== false) {
				$GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS'][$className] = $standardPath;
				$GLOBALS['PHP_CODESNIFFER_TEST_DIRS'][$className]     = $standardTestsDir;
			}
		}

		unset($standardPath, $standardTestsDir, $iterator, $testFile, $className);
	}

	unset($installedStandards, $standardDetails);
} else {
	echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory and that PHPCSUtils is included in the `installed_paths`
for that PHPCS install.
';

	die(1);
}

// Check if phpstan is running.
$cliArgs = $GLOBALS['argv'];

if (!is_null($cliArgs)) {
	foreach ($cliArgs as $argument) {
		if (mb_strpos($argument, 'phpstan') !== false) {
			// Load the WordPress files.
			$WPCSFolder = dirname(__DIR__) . $ds . 'vendor' . $ds . 'wp-coding-standards' . $ds . 'wpcs' . $ds . 'WordPress';

			require_once $WPCSFolder . $ds . 'Sniff.php';
			require_once $WPCSFolder . $ds . 'AbstractFunctionRestrictionsSniff.php';
			require_once $WPCSFolder . $ds . 'AbstractFunctionParameterSniff.php';
			require_once $WPCSFolder . $ds . 'AbstractClassRestrictionsSniff.php';
			require_once $WPCSFolder . $ds . 'AbstractArrayAssignmentRestrictionsSniff.php';

			$helperFiles = glob($WPCSFolder . $ds . 'Helpers' . '/*.php');
			$arraySniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'Arrays' . '/*.php');
			$codeAnalysisSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'CodeAnalysis' . '/*.php');
			$dateTimeSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'DateTime' . '/*.php');
			$dBSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'DB' . '/*.php');
			$filesSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'Files' . '/*.php');
			$namingConventionsSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'NamingConventions' . '/*.php');
			$pHPSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'PHP' . '/*.php');
			$securitySniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'Security' . '/*.php');
			$utilsSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'Utils' . '/*.php');
			$whiteSpaceSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'WhiteSpace' . '/*.php');
			$wPSniffFiles = glob($WPCSFolder . $ds . 'Sniffs' . $ds . 'WP' . '/*.php');

			foreach ($helperFiles as $file) {
				require_once $file;
			}

			foreach ($arraySniffFiles as $file) {
				require_once $file;
			}

			foreach ($codeAnalysisSniffFiles as $file) {
				require_once $file;
			}

			foreach ($dateTimeSniffFiles as $file) {
				require_once $file;
			}

			foreach ($dBSniffFiles as $file) {
				require_once $file;
			}

			foreach ($filesSniffFiles as $file) {
				require_once $file;
			}

			foreach ($namingConventionsSniffFiles as $file) {
				require_once $file;
			}

			foreach ($pHPSniffFiles as $file) {
				require_once $file;
			}

			foreach ($securitySniffFiles as $file) {
				require_once $file;
			}

			foreach ($utilsSniffFiles as $file) {
				require_once $file;
			}

			foreach ($whiteSpaceSniffFiles as $file) {
				require_once $file;
			}

			foreach ($wPSniffFiles as $file) {
				require_once $file;
			}

			unset($file);
		}
	}
}

// Clean up.
unset($ds, $phpcsDir, $composerPHPCSPath);
