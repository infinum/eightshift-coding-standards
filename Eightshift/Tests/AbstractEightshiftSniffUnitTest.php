<?php

/**
 * Abstract base class for Eightshift sniff unit tests.
 *
 * Bridges PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest (which uses the
 * deprecated @before annotation) with PHPUnit 10+, which requires the #[Before]
 * attribute instead. Extending this class instead of AbstractSniffUnitTest directly
 * ensures setUpPrerequisites() is invoked on every PHP version.
 *
 * @package EightshiftCS
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/infinum/eightshift-coding-standards
 */

namespace EightshiftCS\Eightshift\Tests;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;

/**
 * Suppresses PHPUnit 12 "risky" marking for tests that pass with zero assertions.
 *
 * TestSniff() calls $this->fail() on sniff violations rather than $this->assert*(), so
 * PHPUnit 12 marks passing tests as risky. This class-level attribute suppresses that.
 */
#[DoesNotPerformAssertions]
abstract class AbstractEightshiftSniffUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Invokes the parent setUp so that $standardsDir and $testsDir are populated.
	 *
	 * PHPUnit 10+ requires the #[Before] attribute; the @before annotation on the
	 * parent is silently ignored in PHPUnit 10+.
	 */
	#[Before]
	protected function setUpPrerequisites(): void
	{
		parent::setUpPrerequisites();
	}
}
