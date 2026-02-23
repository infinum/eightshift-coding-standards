<?php

echo Helpers::render( // Bad. Not the lib's helper, no import.
	'accordion',
	Helpers::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo MyClass\Helpers::render( // Bad. Not the lib's helper.
	'accordion',
	Helpers::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo Helpers::props('accordion', $attributes, [ // Bad. Not safe method.
	'accordionContent' => $innerBlockContent
]);

echo ProjectVendor\EightshiftLibs\Helpers\Helpers::render( // OK (FQCN)
	'accordion',
	Helpers::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);
