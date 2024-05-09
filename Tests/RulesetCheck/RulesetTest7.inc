<?php

/**
 * View file example
 *
 * @package Project
 */

namespace Project;

use ProjectVendor\EightshiftLibs\Helpers\Helpers;

$manifest = Helpers::getManifest(__DIR__);
$componentName = $attributes['componentName'] ?? $manifest['componentName'];

$mainTitle = Helpers::checkAttr('title', $attributes, $manifest, $componentName);
$nonceField = Helpers::checkAttr('nonceField', $attributes, $manifest, $componentName);

$classname = Helpers::classnames([
	'some-class',
	'other-class-name'
]);

echo Helpers::classnames([ // phpcs:ignore
	'some-class',
	'other-class-name'
]);
?>

<div class="wrap bulk-email js-bulk-mail-send">
	<div class="bulk-email__wrapper">
		<?php
		echo Helpers::render('heading', [ // phpcs:ignore
			'headingLevel' => 1,
			'headingContent' => $mainTitle
		]),
		Helpers::render('paragraph', [ // phpcs:ignore
			'paragraphContent' => \esc_html__(
				'Use the form below to send the bulk emails to conference attendees.',
				'project'
			),
			'componentClass' => 'description'
		]); ?>
	</div>
	<div class="bulk-email__notice js-form-notice"></div>
</div>

<?php
echo $nonceField; // phpcs:ignore
