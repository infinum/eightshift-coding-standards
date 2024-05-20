# Change Log for the Eightshift WordPress Coding Standards

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/).

The semantic versioning started from version 0.2.1.

## [Unreleased]

_No documentation available about unreleased changes yet._

## [3.0.0](https://github.com/infinum/eightshift-coding-standards/compare/2.0.0...3.0.0)

### Changed
- Components helpers in the new eightshift-libs@8.0.0 is deprecated and removed. Instead `Helpers` is used. The `Eightshift.Security.ComponentsEscape` sniff is updated and renamed to `Eightshift.Security.HelpersEscape` to reflect this change.

## [2.0.0](https://github.com/infinum/eightshift-coding-standards/compare/1.6.0...2.0.0) - 2023-09-XX

### Added

- Add a public property `$allowedMethods`, which contains a list of safe methods to the `Eightshift.Security.ComponentsEscape` sniff. Developers can now extend this list in their rulesets.
- Add a test bootstrap file for easier testing, both for PHPUnit, and PHPStan checks.
- Add PHPStan as a part of the CI check.

### Changed

- Update the dependencies to WordPressCS 3.1.0, which will allow the standards to be used with PHP8+.
- Removed old CI checks into one, consolidated CI check.
- Updated the documentation of the sniffs to pass the [validation](https://phpcsstandards.github.io/PHPCSDevTools/#documentation-xsd-validation) according to the [schema](https://phpcsstandards.github.io/PHPCSDevTools/phpcsdocs.xsd).
- Updated sniffs to use more [PHPCSUtils](https://github.com/PHPCSStandards/PHPCSUtils) helpers.
- Update ruleset with all the WordPressCS v3.0.0 changes.

### Fixed

- Update the sniffs to work with newer WordPressCS helpers.
- Update docblocks to be more acurate and pass the PHPStan checks.
- Update tests - the `overriddenClass` wasn't being re-set after one test case which caused failures.


## [1.6.0](https://github.com/infinum/eightshift-coding-standards/compare/1.5.1...1.6.0) - 2022-07-05

### Changed
- Add a parameter `$allowedExtendedClasses` in the FunctionComment sniff
  - This way we can add a list of specific extending CLI classes which won't trigger
    phpcs error on the __invoke() method.


## [1.5.1](https://github.com/infinum/eightshift-coding-standards/compare/1.5.0...1.5.1) - 2022-05-10

### Fixed
- CI/CD check fixes


## [1.5.0](https://github.com/infinum/eightshift-coding-standards/compare/1.4.2...1.5.0) - 2022-03-16

### Added
- Add `overriddenClass` parameter for the EightShift ComponentsEscape sniff
  - This parameter will catch the cases where the libs Components class has been overridden.
- Add ignoreComments property for the line length sniff

### Fixed
- Fixed the edge case with overwriting libs classes. 

### Changed
- Code cleanup in EightShift ComponentsEscape sniff


## [1.4.2](https://github.com/infinum/eightshift-coding-standards/compare/1.4.1...1.4.2) - 2022-03-10

### Fixed
- Exclude the native `WordPress.Security.EscapeOutput.OutputNotEscaped` sniff, because we are overloading it


## [1.4.1](https://github.com/infinum/eightshift-coding-standards/compare/1.4.0...1.4.1) - 2022-03-10

### Fixed
- Fixed `Eightshift.Security.ComponentsEscape` sniff
  - There was a case where the next string token caused issue because there was no guard clause 
    to check if the string is actually a Components class or not.


## [1.4.0](https://github.com/infinum/eightshift-coding-standards/compare/1.3.0...1.4.0) - 2022-03-09

### Added
- EightShift ruleset: add rules for use statements 
  - Adds a new dependency on the Slevomat Coding Standard library.
  - Adds four sniffs from this coding standard to the ruleset:
      1. Forbidding unused `use` statements.
      2. Enforcing fully qualified global functions and constants.
      3. Enforcing import `use` statements for everything else.
  - Includes fixing up the EightShift coding standards code base for these new rules.
  - Ref: https://github.com/slevomat/coding-standard
- Add new EightShift FunctionComment sniff 
  - This sniff overloads the `Squiz.Commenting.FunctionComment` sniff which normally comes included via the `WordPress-Docs` ruleset and makes an allowance for the `__invoke` method in the CLI classes.
  - Includes:
    - Unit tests.
    - Adjusting the ruleset to maintain the same excludes as WPCS had from the `Squiz` ruleset.
    - Adjusting the ruleset to maintain the previous exclude for parameter alignment.
- Add new EightShift ComponentsEscape sniff
  - This sniff overloads the `WordPress.Security.EscapeOutput.OutputNotEscaped` sniff which comes included with `WordPress-Extra` ruleset and makes an allowance for a certain `EightshiftLibs\Helpers\Components` static methods (`render` and `outputCssVariables`).
- Add @covers tag for all the tests
- Add the documentation for the new sniffs

### Changed
- PHPCS ruleset: tabs not spaces
- PHPCS: simplify ruleset
- Composer
  - Update the PHP Parallel Lint version constraint for improved compatibility with PHP 8.1.
    - Ref: https://github.com/php-parallel-lint/PHP-Parallel-Lint/releases
- Update composer to add the allow-plugins
- Update disallow do_shortcode sniff
  - The sniff is updated so that it extends the WPCS Sniff class. This class has two super useful helpers: is_token_namespaced and is_class_object_call. They allow super quick checks for namespaced functions and static functions named do_shortcode which would otherwise trigger a false positive result.
- Update GitHub Actions Workflow update
  - Update composer install action to v2.
  - Add concurrency check to save resources.
- Update minimum PHP version to 7.4 and tab width in the ruleset to 4

### Removed
- Composer
  - Remove the Composer PHPCS plugin dependency as it already is a dependency of both PHPCSExtra as well as SlevomatCodingStandards, so we'll inherit it anyway.

### Fixed
- Fix the wrong namespace in the ruleset
  - The ruleset namespace was wrongly set. For correct usage refer to this link: https://github.com/squizlabs/PHP_CodeSniffer/wiki/Version-3.0-Upgrade-Guide, and for the full explanation watch the workshop video :).
- PHPCS: improve PHPCompatibility check for this repo
  - Remove the dependency as it is already a dependency of PHPCompatibilityWP and having both may cause version conflicts later.
  - Reset the severity of all PHPCompatibility sniffs to `5` for scanning the EightShiftCS code itself as that code is run outside the context of WordPress.

A huge thanks to Juliette Reinders Folmer (@jrfnl) for amazing help in fixing tons of issues with the ruleset and sniffs.


## [1.3.0](https://github.com/infinum/eightshift-coding-standards/compare/1.2.0...1.3.0) - 2020-05-04

### Removed
- Modified escaping sniff
    - The sniff wasn't working correctly, and we'll wait for the upstream to fix the issue
- Exclude doc comment align sniff

### Fixed
- Improve disallow do shortcode sniff (better regex and tests)
- Fixed the namespace in the ruleset.xml

### Changed
- Update minimum supported WP version to 5.4


## [1.2.0](https://github.com/infinum/eightshift-coding-standards/compare/1.1.0...1.2.0) - 2020-04-15

### Added
- Workflows for GH Actions
- Docs for custom sniffs
- Modified escaping sniff
    - will exclude the custom `Components::render()` method from the eightshift-libs
- Added phpcs extra ruleset for array spacing and array best practices
- Update samples for ruleset

### Changed
- Updated sniffs namespace
  
### Fixed
- Fix docblocks in the sniffs


## [1.1.0](https://github.com/infinum/eightshift-coding-standards/compare/1.0.1...1.1.0) - 2020-11-30

### Package renaming

We renamed the package from `infinum/coding-standards-wp` to `infinum/eightshift-coding-standards`.


## [1.1.0](https://github.com/infinum/eightshift-coding-standards/compare/1.0.0...1.0.1) - 2020-09-24

### Added
- Added a rule to prevent underscores denoting the private methods/properties


## [1.0.0](https://github.com/infinum/eightshift-coding-standards/compare/0.4.1...1.0.0) - 2020-09-18

### Official release of the Eightshift coding standards for WordPress projects

This is the official release of the Eightshift coding standards for WordPress. It contains breaking changes, mostly in
 regard
 of the naming scheme. 
To equate the way we write our PHP and JS we opted to follow a modified PSR standards.
What this means is that we will remove liberal spacing, add some PSR12 modifications regarding arguments placing in closures, change snake_case with CamelCase for classes (for autoload puropses) and some other minor changes that will be documented below.
If you wish to use the old standards, be sure to modify your projects `composer.json` file with the appropriate version.

### Added
- Added PSR-12 standards
- Added more tests
- Updated the WPCS to 2.3.0
- Updated scripts

### Removed
- Removed the Test bootstrap
- Removed PHP 5.6 support and raised the recommended PHP version to 7.2 or higher
- Removed WordPress-Core standards

### Changed
- Replaced WordPress naming standards to modified PSR standards
- Changed the namespace of the WPCS core classes


## [0.4.1](https://github.com/infinum/eightshift-coding-standards/compare/0.3.1...0.4.1) - 2018-11-15

### Added
- Silenced previously excluded sniffs to avoid loading the entire `WordPress` ruleset
- Silenced `WordPress.Arrays.ArrayIndentation` to avoid it clashing with Generic indentation sniff

### Removed
- Fixed multiple alignment sniff issue

### Changed
- Reorganized sniff rules
- Raised the minimum supported PHP version to PHP 7.1


## [0.4.0](https://github.com/infinum/eightshift-coding-standards/compare/0.3.1...0.4.0) - 2018-10-24

### Added
- Unit tests - the basic setup is taken from https://github.com/WPTRT/WPThemeReview/
- Ignore rule about enqueueing scripts in the footer
- .gitattributes file for release purposes
- Added internal ruleset for writing additional sniffs
- Added rule about alignment of assignment operators (see this [customizable property](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Customisable-Sniff-Properties#genericformattingmultiplestatementalignment))
- Added a rule about function parameters and indentation
- Ignore unused parameters in functions on account on hooks
- Added a rule about private methods and properties - they MUST NOT be prefixed with an underscore
- Added PHPCompatibilityWP ruleset for checking a cross-version PHP compatibility (from PHP 7.0 onwards)
- Added detailed CHANGELOG
- Updated readme
- Added autoload for loading the standards
- Updated composer.json
  - added different requirements
  - added unit test packages
  - moved phpcodesniffer-composer-installer to suggested package
  - updated scripts
- Added .travis.yml for automatic checks on new sniff addition

### Removed
- Removed ruleset.xml from the root of the standards
- Removed WordPress.Arrays.MultipleStatementAlignment exclusion rule

### Fixed
- Minor coding standard fix in the DisallowDoShortcode sniff
- Fix the array indentation issue


## [0.3.1](https://github.com/infinum/eightshift-coding-standards/compare/0.3.0...0.3.1) - 2018-07-27

### Changed
- Set the WPCS dependency to >= 1.0.0


## [0.3.0](https://github.com/infinum/eightshift-coding-standards/compare/0.2.8...0.3.0) - 2018-07-26

### Added
- Added minimum_supported_wp_version check - v4.7 is set as the minimum

### Changed
- Added additional rules to ruleset
- Fix the exclude patterns
- Change the VIP ruleset to WP native one since VIP is being deprecated upstream

### Removed
- Removed OnlyClassInFile sniff as it's pulled from the upstream

### Fixed
- Updated the DisallowDoShortcodeSniff to match phpcs 3.0.0 and latest WPCS


## [0.2.8](https://github.com/infinum/eightshift-coding-standards/compare/0.2.6...0.2.8) - 2018-06-21

### Added
- More files to the .gitignore file
- Added WordPress.VIP.PostsPerPage rule with 1000 posts limit
- Added WordPress.WhiteSpace.PrecisionAlignment to the exclude list
- Added WordPress.Arrays.MultipleStatementAlignment to the exclude list
- Added WordPress.Arrays.ArrayIndentation to the ruleset
- License file
- Update WPCS dependency to 0.14.0

### Changed
- Changed from GPLv2 to MIT license
- Updated readme - minor improvements and added credits

### Removed
- Removed PSR1.Classes.ClassDeclaration exclusion


## [0.2.6](https://github.com/infinum/eightshift-coding-standards/compare/0.2.5...0.2.6) - 2017-10-28

### Fixed
- Composer installed paths


## [0.2.5](https://github.com/infinum/eightshift-coding-standards/compare/0.2.4...0.2.5) - 2017-10-28

### Changed
- Updated readme and added more explanations for usage in IDE's
- Updated the WPCS dependency to 0.13.0

### Removed
- Removed default report since it conflicted with VSCode


## [0.2.4](https://github.com/infinum/eightshift-coding-standards/compare/0.2.3...0.2.4) - 2017-10-28

### Fixed
- Composer scripts - fixed installed paths set


## [0.2.3](https://github.com/infinum/eightshift-coding-standards/compare/0.2.2...0.2.3) - 2017-09-19

### Added
- Added `tab-width` rule (2 spaces default)

### Removed
- Removed prefix check, since we started using namespaces and OOP instead of procedural php and global namespace

### Changed
- Corrected the indentation in the ruleset


## [0.2.2](https://github.com/infinum/eightshift-coding-standards/compare/0.2.1...0.2.2) - 2017-07-25

### Changed
- Updated DisallowDoShortcodeSniff regex - from multiline check to just case insensitive check
- Increased dependencu on WPCS 0.12.0


## [0.2.1](https://github.com/infinum/eightshift-coding-standards/compare/0.2.1...main) - 2017-07-18

Initial release
