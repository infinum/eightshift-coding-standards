<?php

/**
 * Template for the Wrapping Advanced block.
 *
 * @package EightshiftBoilerplate
 */

use EightshiftBoilerplateVendor\EightshiftLibs\Helpers\Helpers;

$manifest = Helpers::getManifest(__DIR__);

// Used to add or remove wrapper.
$wrapperUse = Helpers::checkAttr('wrapperUse', $attributes, $manifest);
$wrapperUseSimple = Helpers::checkAttr('wrapperUseSimple', $attributes, $manifest);
$wrapperDisable = Helpers::checkAttr('wrapperDisable', $attributes, $manifest);
$wrapperParentClass = Helpers::checkAttr('wrapperParentClass', $attributes, $manifest);
$className = Helpers::checkAttr('className', $attributes, $manifest);

$wrapperParentClassItemClass = Helpers::selector($wrapperParentClass, $wrapperParentClass, 'item');
$wrapperParentClassItemInnerClass = Helpers::selector($wrapperParentClass, $wrapperParentClass, 'item-inner');

if (!$wrapperUse || $wrapperDisable) {
	if ($wrapperParentClass) {
		?>
			<div class="<?php echo esc_attr($wrapperParentClassItemClass); ?>">
				<div class="<?php echo esc_attr($wrapperParentClassItemInnerClass); ?>">
		<?php
	}

	$this->renderWrapperView(
		$templatePath,
		$attributes,
		$innerBlockContent
	);

	if ($wrapperParentClass) {
		?>
			</div>
		</div>
		<?php
	}

	return;
}

$wrapperId = Helpers::checkAttr('wrapperId', $attributes, $manifest);
$wrapperAnchorId = Helpers::checkAttr('wrapperAnchorId', $attributes, $manifest);
$wrapperBackgroundColor = Helpers::checkAttr('wrapperBackgroundColor', $attributes, $manifest);

$wrapperHide = Helpers::checkAttrResponsive('wrapperHide', $attributes, $manifest);
$wrapperSpacingTop = Helpers::checkAttrResponsive('wrapperSpacingTop', $attributes, $manifest);
$wrapperSpacingBottom = Helpers::checkAttrResponsive('wrapperSpacingBottom', $attributes, $manifest);
$wrapperSpacingTopIn = Helpers::checkAttrResponsive('wrapperSpacingTopIn', $attributes, $manifest);
$wrapperSpacingBottomIn = Helpers::checkAttrResponsive('wrapperSpacingBottomIn', $attributes, $manifest);
$wrapperDividerTop = Helpers::checkAttrResponsive('wrapperDividerTop', $attributes, $manifest);
$wrapperDividerBottom = Helpers::checkAttrResponsive('wrapperDividerBottom', $attributes, $manifest);
$wrapperContainerWidth = Helpers::checkAttrResponsive('wrapperContainerWidth', $attributes, $manifest);
$wrapperGutter = Helpers::checkAttrResponsive('wrapperGutter', $attributes, $manifest);
$wrapperWidth = Helpers::checkAttrResponsive('wrapperWidth', $attributes, $manifest);
$wrapperOffset = Helpers::checkAttrResponsive('wrapperOffset', $attributes, $manifest);

$componentClass = 'wrapper';

$wrapperClass = Helpers::classnames([
	$componentClass,
	Helpers::selector($componentClass, $componentClass, 'bg-color', $wrapperBackgroundColor), // @phpstan-ignore-line
	Helpers::responsiveSelectors($wrapperSpacingTop, 'spacing-top', $componentClass),
	Helpers::responsiveSelectors($wrapperSpacingBottom, 'spacing-bottom', $componentClass),
	Helpers::responsiveSelectors($wrapperSpacingTopIn, 'spacing-top-in', $componentClass),
	Helpers::responsiveSelectors($wrapperSpacingBottomIn, 'spacing-bottom-in', $componentClass),
	Helpers::responsiveSelectors($wrapperDividerTop, 'divider-top', $componentClass, false),
	Helpers::responsiveSelectors($wrapperDividerBottom, 'divider-bottom', $componentClass, false),
	Helpers::responsiveSelectors($wrapperHide, 'hide', $componentClass, false),
	$className,
]);

$wrapperContainerClass = Helpers::classnames([
	Helpers::selector($componentClass, $componentClass, 'container'), // @phpstan-ignore-line
	Helpers::responsiveSelectors($wrapperContainerWidth, 'container-width', $componentClass),
	Helpers::responsiveSelectors($wrapperGutter, 'gutter', $componentClass),
]);

$wrapperInnerClass = Helpers::classnames([
	Helpers::selector($componentClass, $componentClass, 'inner'), // @phpstan-ignore-line
	Helpers::responsiveSelectors($wrapperWidth, 'width', $componentClass),
	Helpers::responsiveSelectors($wrapperOffset, 'offset', $componentClass),
]);

$wrapperMainAnchorClass = Helpers::selector($componentClass, $componentClass, 'anchor'); // @phpstan-ignore-line

$idOutput = '';

if ($wrapperId) {
	$escapedId = esc_attr($wrapperId);
	$idOutput = "id='{$escapedId}'";
}

?>
<div
	class="<?php echo esc_attr($wrapperClass); ?>"
	<?php echo $idOutput; // Bad ?>
>
	<?php if ($wrapperAnchorId) { ?>
		<div class="<?php echo esc_attr($wrapperMainAnchorClass); ?>" id="<?php echo esc_attr($wrapperAnchorId); ?>"></div>
	<?php } ?>

	<?php if ($wrapperUseSimple) { ?>
		<?php
		$this->renderWrapperView(
			$templatePath,
			$attributes,
			$innerBlockContent
		);
		?>
	<?php } else { ?>
		<div class="<?php echo esc_attr($wrapperContainerClass); ?>">
			<div class="<?php echo esc_attr($wrapperInnerClass); ?>">
				<?php
				$this->renderWrapperView(
					$templatePath,
					$attributes,
					$innerBlockContent
				);
				?>
			</div>
		</div>
	<?php } ?>
</div>
