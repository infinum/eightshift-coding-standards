# Infinum WordPress Coding Standards

[![Latest Stable Version](https://poser.pugx.org/infinum/coding-standards-wp/v/stable)](https://packagist.org/packages/infinum/coding-standards-wp)
[![License](https://poser.pugx.org/infinum/coding-standards-wp/license)](https://packagist.org/packages/infinum/coding-standards-wp)

This package is a version of [Infinum WordPress Coding Standards](https://handbook.infinum.co/books/wordpress) for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/). The intention is to have a unified code across the WordPress projects, and to help with the code review.

## Installation

First, make sure that you have [WordPress Coding Standards for PHP_CodeSniffer](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) installed.

Then you can install the additional coding standards using Composer:

`composer require infinum/coding-standards-wp`

Run the following command to run the standards checks:

```
vendor/bin/phpcs --standard=vendor/infinum/coding-standards-wp/Infinum .
```

The final `.` here specifies the files you want to test; this is typically the current directory (`.`), but you can also selectively check files or directories by specifying them instead.

## Working in IDE

### Sublime Text 3

To make the sniff work in Sublime Text 3, you need to have sublime linter set up, and add [phpcs linter](https://github.com/SublimeLinter/SublimeLinter-phpcs).

Then in your settings you need to reference the path to the coding standards. It should look something like this

```json
"paths": {
    "linux": [],
    "osx": [
        "/Users/<user name>/wpcs/vendor/bin"
    ],
    "windows": []
},
```

The path depends on where you've installed your standards. Then in the linters user settings you'll need to add in the `linters` key

```json
"phpcs": {
    "@disable": false,
    "args": [],
    "excludes": [],
    "standard": "Infinum"
},
```

In your `wpcs` folder, add Infinum folder. You can clone this repository there, and then just use `Infinum` folder (the one containing the sniffs and the ruleset.xml), and be sure to comment out the `<config name="installed_paths" value="vendor/wp-coding-standards/wpcs"/>` part, so that your sniffer won't go looking for that folder (since you are in it already).

*Note*

This will set up the coding standards globally. The per project case still needs to be tested.

### Visual Studio Code

To set up phpcs in your VSCode, use [vscode-phpcs](https://github.com/ikappas/vscode-phpcs/) extension. Once installed in the user settings set

```json
"phpcs.enable": true,
"phpcs.standard": "Infinum",
```

This will look in your project's vendor folder for the Infinum's WordPress Coding Standards, and run the sniffs on every save. You can see the issues in the Problems tab at the bottom.
