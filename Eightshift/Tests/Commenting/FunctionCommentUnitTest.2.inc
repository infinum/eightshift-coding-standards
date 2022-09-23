<?php

// phpcs:set Eightshift.Commenting.FunctionComment extendedClass EightshiftLibs\\Blocks\\AbstractBlocksCli

class ClassWithPhpStanCommentOnInvoke extends AbstractBlocksCli
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


// phpcs:set Eightshift.Commenting.FunctionComment extendedClass
