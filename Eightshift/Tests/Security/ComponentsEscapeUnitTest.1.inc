<?php

use ProjectVendor\EightshiftLibs\Helpers\Components;

echo Components::render( // OK (imported method)
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo ProjectVendor\EightshiftLibs\Helpers\Components::render( // OK (FQCN)
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo Components::outputCssVariables($attributes, $manifest, $unique, $globalManifest); // OK (import)

echo \Components::outputCssVariables($attributes, $manifest, $unique, $globalManifest); // Bad (global namespaced class)

echo Components::getManifest($attributes, $manifest, $unique, $globalManifest); // Bad (Not on the safe method list)
