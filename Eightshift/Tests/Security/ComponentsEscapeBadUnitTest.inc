<?php

echo Components::render( // Bad. Not the libs helper, no import.
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);

echo MyClass\Components::render( // Bad. Not the libs helper.
	'accordion',
	Components::props('accordion', $attributes, [
		'accordionContent' => $innerBlockContent
	])
);
