<?php

echo Components::render('heading', [ // Ok.
	'headingLevel' => 1,
	'headingContent' => $mainTitle
]);

echo Components::classnames([ // Bad. Should be escaped.
	'some-class',
	'other-class-name'
]);

echo Components::ensureString($layoutBottom); // Bad, should be escaped.

esc_html_e('Some string', 'project'); // Ok.
echo __('Another string', 'project'); // Bad, esc_html__ should be used.
echo $possibleEvilString; // Bad.
echo wp_kses_post($possibleEvilString); // Ok.
echo esc_html__('Translate me'); // Ok.
