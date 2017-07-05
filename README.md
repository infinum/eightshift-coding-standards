# Infinum WordPress Coding Standards

This is a version of [Infinum WordPress Coding Standards](https://handbook.infinum.co/books/wordpress) for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/).

## Setup

1. `composer require infinum/wp-coding-standards`
2. Run the following command to run the standards checks:

```
vendor/bin/phpcs --standard=vendor/infinum/wp-coding-standards .
```

The final `.` here specifies the files you want to test; this is typically the current directory (`.`), but you can also selectively check files or directories by specifying them instead.
