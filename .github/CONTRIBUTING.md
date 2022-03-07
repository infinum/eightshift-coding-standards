# Contributing Guidelines

Welcome to our Eightshift Coding Standards project! Thanks for helping us improve them.

## How can I contribute?

There are multiple ways in which you can help us make this project even better.

- Writing code improvements
- Adding tests
- Writing documentation
- Taking part in the [discussions](https://github.com/infinum/eightshift-coding-standards/discussions)

## Contributing patches and new features

If you found a bug and want to fix it, or you want to add some new and cool feature, [fork](https://github.com/infinum/eightshift-coding-standards) our repository, then create a `feature` branch from the `main` branch. For instance `feature/some-bug-fix` or `feature/some-cool-new-feature`.

Once you've coded things up, be sure you check that your code is following our coding standards. You can do that by running

```bash
composer standards:check
```

Also, test that your code isn't breaking anything by running

```bash
composer tests:run
```

If you want to add a new sniff, please add a documentation for it as well. If you forget to add it the 

```bash
composer check:complete-strict
```

will warn you about it.

Then submit a pull request to `develop` branch. Once we check everything we'll merge the changes into the `main` branch with correct version correction (noted by the milestone flag and `future release` tag).

## Guidelines

- We want to ensure a welcoming environment for everyone. With that in mind, all contributors are expected to follow our [Code of Conduct](/CODE_OF_CONDUCT.md).

- You maintain copyright over any contribution you make. By submitting a pull request you agree to release that code under the project's [License](/LICENSE).

## Reporting Security Issues

Please see [SECURITY.md](/SECURITY.md).
