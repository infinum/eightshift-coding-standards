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
vendor/bin/phpcs --standard=vendor/infinum/coding-standards-wp .
```

The final `.` here specifies the files you want to test; this is typically the current directory (`.`), but you can also selectively check files or directories by specifying them instead.