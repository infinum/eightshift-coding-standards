<?php

// phpcs:set Eightshift.Security.HelpersEscape allowedMethods[] props

echo MyClass\Helpers::render( // Bad. Not the lib's helper.
	'accordion',
	Helpers::props('accordion', $attributes, [ // Ok. Allowed. And the sniff will skip checking the inside of the static method.
		'accordionContent' => $innerBlockContent
	])
);

Helpers::props('accordion', $attributes, [ // Ok. Allowed.
	'accordionContent' => $innerBlockContent
]);

// phpcs:set Eightshift.Security.HelpersEscape allowedMethods[] render, outputCssVariables
