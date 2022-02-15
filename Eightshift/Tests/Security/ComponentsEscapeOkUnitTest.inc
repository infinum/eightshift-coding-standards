<?php

use ProjectVendor\EightshiftLibs\Helpers\Components;

echo Components::render( // OK
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo ProjectVendor\EightshiftLibs\Helpers\Components::render( // OK
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);
