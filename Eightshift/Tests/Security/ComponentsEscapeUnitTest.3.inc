<?php

use ProjectVendor\EightshiftLibs\Helpers;

echo Helpers\Components::render( // OK (imported method, even though not correctly, it's still valid)
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo Components::render( // Bad (Not correctly imported)
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo (new Components())->render( // Bad
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

