<?php

/**
 * Class that registers WPCLI command initial setup of theme project.
 *
 * @package EightshiftLibs\Cli
 */

declare(strict_types=1);

namespace EightshiftLibs\Cli;

use EightshiftLibs\Blocks\BlocksCli;
use EightshiftLibs\Cli\AbstractCli;
use EightshiftLibs\Config\ConfigCli;
use EightshiftLibs\Enqueue\Admin\EnqueueAdminCli;
use EightshiftLibs\Enqueue\Blocks\EnqueueBlocksCli;
use EightshiftLibs\Enqueue\Theme\EnqueueThemeCli;
use EightshiftLibs\Main\MainCli;
use EightshiftLibs\Manifest\ManifestCli;
use EightshiftLibs\Menu\MenuCli;
use WP_CLI;
use ReflectionClass;

/**
 * Class CliInitTheme
 */
class CliInitTheme extends AbstractCli
{
	/**
	 * All classes for initial theme setup for project.
	 */
	public const INIT_THEME_CLASSES = [
		BlocksCli::class,
		EnqueueAdminCli::class,
		EnqueueBlocksCli::class,
		EnqueueThemeCli::class,
		ConfigCli::class,
		MainCli::class,
		ManifestCli::class,
		MenuCli::class,
	];

	/**
	 * Get WPCLI command name
	 *
	 * @return string
	 */
	public function getCommandName(): string
	{
		return 'init_theme';
	}

	/**
	 * Get WPCLI command doc.
	 *
	 * @return string
	 */
	public function getDoc(): array
	{
		return [
			'shortdesc' => 'Generates initial setup for WordPress theme project.',
		];
	}

	public function __invoke(array $args, array $assocArgs) // phpcs:ignore Squiz.Commenting.FunctionComment.Missing
	{
		if (!\function_exists('add_action')) {
			$this->run_reset();
			WP_CLI::log('--------------------------------------------------');
		}

		foreach (static::INIT_THEME_CLASSES as $item) {
			$reflectionClass = new ReflectionClass($item);
			$class = $reflectionClass->newInstanceArgs([null]);

			if (\function_exists('add_action')) {
				WP_CLI::runcommand("{$this->commandParentName} {$class->getCommandName()}");
			} else {
				WP_CLI::runcommand("eval-file bin/cli.php {$class->getCommandName()} --skip-wordpress");
			}
		}

		WP_CLI::log('--------------------------------------------------');

		WP_CLI::success('All commands are finished.');
	}
}
