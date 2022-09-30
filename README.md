![image](https://repository-images.githubusercontent.com/96307889/1ff5645a-63b3-476f-925a-5f37c2599f6e)

[![Packagist downloads](https://img.shields.io/packagist/dt/infinum/eightshift-coding-standards.svg?style=for-the-badge
)](https://packagist.org/packages/infinum/eightshift-coding-standards)
[![Travis Build Status](https://img.shields.io/travis/:user/:repo.svg?style=for-the-badge)](https://travis-ci.org/infinum/eightshift-coding-standards)
[![GitHub tag](https://img.shields.io/github/tag/infinum/eightshift-coding-standards.svg?style=for-the-badge)](https://github.com/infinum/eightshift-coding-standards)
[![GitHub stars](https://img.shields.io/github/stars/infinum/eightshift-coding-standards.svg?style=for-the-badge&label=Stars)](https://github.com/infinum/eightshift-coding-standards/)
[![License](https://img.shields.io/github/license/infinum/eightshift-coding-standards.svg?style=for-the-badge)](https://github.com/infinum/eightshift-coding-standards)

# Eightshift Coding Standards for WordPress

This package contains [Eightshift Coding Standards for WordPress](https://handbook.infinum.co/books/wordpress) for
 [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/). The intention of this package is to have a unified
  code across the WordPress projects we do at Eightshift, and to help with the code review.

## Installation

### Composer

Composer install is simple. Just run

`composer require infinum/eightshift-coding-standards`

or add to your `composer.json`

```json
"require-dev": {
  "infinum/eightshift-coding-standards": "^1.4"
}
```

Copy and modify the configuration file into your project (from the project root)

```bash
cp vendor/infinum/eightshift-coding-standards/phpcs.xml.dist.sample phpcs.xml.dist 
``` 

Then, run the following command to run the standards checks in your project:

```bash
vendor/squizlabs/php_codesniffer/bin/phpcs
```

You can also selectively check files or directories by specifying them.

### Recommendation

It's recommended that you install a Composer plugin that will handle the registration of standards with PHP_CodeSniffer. The two actively maintained are

* [composer-phpcodesniffer-standards-plugin](https://github.com/higidi/composer-phpcodesniffer-standards-plugin)
* [phpcodesniffer-composer-installer](https://github.com/DealerDirect/phpcodesniffer-composer-installer)

We suggest using the `dealerdirect/phpcodesniffer-composer-installer` when including our standards, so that you avoid any possible issues.

## Working in IDE

### Sublime Text 3

To make the sniff work in Sublime Text 3, you need to have sublime linter set up, and add [phpcs linter](https://github.com/SublimeLinter/SublimeLinter-phpcs).

Then in your settings you need to reference the path to the coding standards. It should look something like this

```json
"paths": {
    "linux": [],
    "osx": [
        "${project}/vendor/bin/",
        "/Users/user_name/wpcs/vendor/bin"
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
    "standard": "Eightshift"
},
```

Or set the `standard` to point to the phpcs.xml.dist in your root folder (**preferred method**)

```json
"phpcs": {
    "@disable": false,
    "args": [],
    "excludes": [],
    "standard": "$folder/phpcs.xml.dist"
},
```

#### Note about global installation

In your `wpcs` folder, when adding Eightshift folder. You can clone this repository there, be sure to comment out the
 `<config
 name="installed_paths" value="vendor/wp-coding-standards/wpcs"/>` part, so that your sniffer won't go looking for that folder.

### Visual Studio Code

To set up phpcs in your VSCode, use [vscode-phpcs](https://github.com/ikappas/vscode-phpcs/) extension. Once installed in the user settings set

```json
"phpcs.enable": true,
"phpcs.standard": "Eightshift",
```

This will look in your project's vendor folder for the Eightshift's WordPress Coding Standards, and run the sniffs on
 every
 save. You can see the issues in the Problems tab at the bottom.

### Atom

To set up phpcs in the Atom editor, you need to install a couple of packages. First install the base linter package for Atom: [linter](https://atom.io/packages/linter). Upon completion you will be prompted to install its dependency [linter-ui-default](https://atom.io/packages/linter-ui-default). After that install [linter-phpcs](https://atom.io/packages/linter-phpcs).

In `linter-phpcs` package settings you can set the path to previously installed `phpcs` or allow the package to
 search for `phpcs` executable inside your project. Also, you must set the name of the standard: `"Eightshift"` or
  path to
  the
  `ruleset.xml` of Eightshift's WordPress Coding Standards. We recommend you to disable searching for configuration
   files
   because that can cause some other standards to be used instead.

In config.cson linter-phpcs settings can look like this:

```coffee
# If you want to use phpcs executable available in project
"linter-phpcs":
  autoConfigSearch: false
  codeStandardOrConfigFile: "Eightshift"
  disableWhenNoConfigFile: true

# If you want to use specific phpcs executable
"linter-phpcs":
  autoConfigSearch: false
  autoExecutableSearch: false
  codeStandardOrConfigFile: "Eightshift"
  disableWhenNoConfigFile: true
  executablePath: "/Users/user_name/wpcs/vendor/bin/phpcs" #For Mac users
  executablePath: "path_to_composer/vendor/bin/phpcs.bat" # For Windows users

# If you want to use specific standard
"linter-phpcs":
  autoConfigSearch: false
  codeStandardOrConfigFile: "vendor/infinum/eightshift-coding-standards/Eightshift/ruleset.xml"
  disableWhenNoConfigFile: true
```

## Credits

Eightshift WordPress Coding Standards are maintained and sponsored by [Infinum](https://www.infinum.com).

## License

Eightshift WordPress Coding Standards are Copyright © 2022 Infinum.
This is free software, and may be redistributed under the terms specified in the LICENSE file.
