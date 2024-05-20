<?php

use ProjectVendor\EightshiftLibs\Helpers;

echo Helpers\Helpers::render( // OK (imported method, even though not correctly, it's still valid)
	'accordion',
	Helpers::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo Helpers::render( // Bad (Not correctly imported)
	'accordion',
	Helpers::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo (new Helpers())->render( // Bad
	'accordion',
	[]
);

echo $nonceField; // Bad
