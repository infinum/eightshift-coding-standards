<?php

class ClassWithPhpStanCommentOnInvoke extends AbstractCli
{
	/* @phpstan-ignore-next-line */
	public function __invoke(array $args, array $assocArgs)
	{
		// This should be silently ignored.
	}

	/* @phpstan-ignore-next-line */
	public function foo(array $assocArgs)
	{
		// This method should throw an error because it's not an __invoke magic method.
	}
}

$anonIs = new class extends AbstractCli
{
	public function __invoke(array $args, array $assocArgs)
	{
		// This should be silently ignored.
	}

	public function foo(array $assocArgs)
	{
		// This method should throw an error because it's not an __invoke magic method.
	}

	/**
	 * @param array $assocArgs Associated arguments. Method argument.
	 *
	 * @return void
	 */
	public function bar(array $assocArgs)
	{
		// This code complies with the standards.
	}
};

class RegularClass extends NotAbstractCli
{
	public function __invoke(array $args, array $assocArgs)
	{
		// This method should throw an error because it doesn't extend AbstractCli class.
	}

	/**
	 * @param array $args      Arguments.
	 * @param array $assocArgs Associated arguments.
	 *
	 * @return void
	 */
	public function __invoke(array $args, array $assocArgs)
	{
		// This code complies with the standards.
	}
}

class NotExtendingClass
{
	public function __invoke(array $args, array $assocArgs)
	{
		// This method should throw an error because it doesn't extend AbstractCli class.
	}

	/**
	 * @param array $args      Arguments.
	 * @param array $assocArgs Associated arguments.
	 *
	 * @return void
	 */
	public function __invoke(array $args, array $assocArgs)
	{
		// This code complies with the standards.
	}
}

function __invoke() {
	// This global function should trigger an error.
}

/**
 * @param string $name Name of the person.
 *
 * @return void
 */
function __invoke(string $name) {
	// This code complies with the standards.
}

// phpcs:set Eightshift.Commenting.FunctionComment allowedExtendedClasses[] SomeOtherCliClass, AbstractCli
class RegularClass extends SomeOtherCliClass
{
	public function __invoke(array $args, array $assocArgs)
	{
		// This method should not throw an error because we added
        // the extending class to the list of allowed classes.
	}

	/**
	 * @param array $args      Arguments.
	 * @param array $assocArgs Associated arguments.
	 *
	 * @return void
	 */
	public function __invoke(array $args, array $assocArgs)
	{
		// This code complies with the standards.
	}
}

// phpcs:set Eightshift.Commenting.FunctionComment allowedExtendedClasses[] AbstractCli
class RegularClass extends SomeOtherCliClass
{
	public function __invoke(array $args, array $assocArgs)
	{
		// This method should throw an error because it doesn't extend AbstractCli class.
	}

	/**
	 * @param array $args      Arguments.
	 * @param array $assocArgs Associated arguments.
	 *
	 * @return void
	 */
	public function __invoke(array $args, array $assocArgs)
	{
		// This code complies with the standards.
	}
}
