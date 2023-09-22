<?php

// phpcs:set Eightshift.Security.ComponentsEscape allowedMethods[] props

echo MyClass\Components::render( // Bad. Not the lib's helper.
	'accordion',
	Components::props('accordion', $attributes, [ // Ok. Allowed. And the sniff will skip checking the inside of the static method.
		'accordionContent' => $innerBlockContent
	])
);

Components::props('accordion', $attributes, [ // Ok. Allowed.
	'accordionContent' => $innerBlockContent
]);

// phpcs:set Eightshift.Security.ComponentsEscape allowedMethods[] render, outputCssVariables
