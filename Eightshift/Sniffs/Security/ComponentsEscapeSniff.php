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
 * @since 1.4.0
 */

namespace EightshiftCS\Eightshift\Sniffs\Security;

use WordPressCS\WordPress\Sniffs\Security\EscapeOutputSniff;

/**
 * Override the WordPress.Security.EscapeOutput sniff
 *
 * This sniff will ignore escaping errors whenever it finds the
 * EightshiftLibs\Helpers\Components::render or
 * EightshiftLibs\Helpers\Components::outputCssVariables methods.
 *
 * These methods are considered safe because the components
 * should be properly escaped on the output.
 */
class ComponentsEscapeSniff extends EscapeOutputSniff
{
	// Check the class name.
	// If the class name is Components, check if there is an import at the top.
	// If there is an import, check that it contains the EightshiftLibs\Helpers\Components.
	// If it does, ignore the error for the escaped output.
	// If it doesn't (imported, or not) trigger error.
}
