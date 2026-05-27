<?php

/**
 * Rector bootstrap.
 *
 * @package EightshiftCS
 */

// phpcs:disable Universal.Arrays.MixedKeyedUnkeyedArray.Found

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;

return RectorConfig::configure()
	->withPaths([
		__DIR__,
	])
	->withBootstrapFiles([__DIR__ . '/vendor/autoload.php'])
	->withPhpSets(php84: true)
	->withSets([LevelSetList::UP_TO_PHP_84, SetList::CODE_QUALITY, SetList::DEAD_CODE, SetList::TYPE_DECLARATION, SetList::EARLY_RETURN])
	->withSkip([
		RemoveUselessVarTagRector::class,
		__DIR__ . '/vendor-prefixed/*',
		__DIR__ . '/vendor/*',
		__DIR__ . '/node_modules/*',
		__DIR__ . '/eightshift/*',
		SafeDeclareStrictTypesRector::class => [__DIR__ . '/Eightshift/Tests'],
	])
	->withIndent("\t", indentSize: 1)
	->withImportNames(importShortClasses: false, removeUnusedImports: true);
