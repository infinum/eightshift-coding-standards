<?php

/**
 * View file for bulk email sending
 *
 * @package Project
 */

namespace Project;

use Project\Callback\BulkEmailFormOptions;
use ProjectVendor\EightshiftLibs\Helpers\Components;

$manifest = Components::getManifest(__DIR__);
$componentName = $attributes['componentName'] ?? $manifest['componentName'];

$mainTitle = Components::checkAttr('title', $attributes, $manifest, $componentName);
$nonceField = Components::checkAttr('nonceField', $attributes, $manifest, $componentName);

?>

<div class="wrap bulk-email js-bulk-mail-send">
	<div class="bulk-email__wrapper">
		<?php
		echo Components::render('heading', [
			'headingLevel' => 1,
			'headingContent' => $mainTitle
		]),
		Components::render('paragraph', [
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
