<?php

use ProjectVendor\EightshiftLibs\Helpers\Helpers;

echo Helpers::render( // OK (imported method)
	'accordion',
	Helpers::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo ProjectVendor\EightshiftLibs\Helpers\Helpers::render( // OK (FQCN)
	'accordion',
	Helpers::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo Helpers::outputCssVariables($attributes, $manifest, $unique, $globalManifest); // OK (import)

echo \Helpers::outputCssVariables($attributes, $manifest, $unique, $globalManifest); // Bad (global namespaced class)

echo Helpers::getManifest($attributes, $manifest, $unique, $globalManifest); // Bad (Not on the safe method list)
