# Infinum WordPress Coding Standards

<img src="https://avatars0.githubusercontent.com/u/97652?v=3&s=200" alt="Infinum logo">

This is a version of [Infinum WordPress Coding Standards](https://handbook.infinum.co/books/wordpress) for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/).

## Setup

1. `composer require infinum/wp-coding-standards`
2. Run the following command to run the standards checks:

```
vendor/bin/phpcs --standard=vendor/infinum/wp-coding-standards .
```

The final `.` here specifies the files you want to test; this is typically the current directory (`.`), but you can also selectively check files or directories by specifying them instead.

### Advanced/Extending

If you want to add further rules (such as WordPress.com VIP-specific rules), you can create your own custom standard file (e.g. `phpcs.ruleset.xml`):

```xml
<?xml version="1.0"?>
<ruleset>
  <!-- Use Infinum Coding Standards -->
  <rule ref="vendor/infinum/wp-coding-standards" />

  <!-- Add VIP-specific rules -->
  <rule ref="WordPress-VIP" />
</ruleset>
```

You can then reference this file when running phpcs:

```
vendor/bin/phpcs --standard=phpcs.ruleset.xml .
```

