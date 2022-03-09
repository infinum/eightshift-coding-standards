<?php

echo Components::render( // Bad. Not the lib's helper, no import.
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo MyClass\Components::render( // Bad. Not the lib's helper.
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo Components::props('accordion', $attributes, [ // Bad. Not safe method.
	'accordionContent' => $innerBlockContent
]);

echo ProjectVendor\EightshiftLibs\Helpers\Components::render( // OK (FQCN)
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);
