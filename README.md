# Valravn

<p align="center"><img alt="Directus Logo" src="https://repository-images.githubusercontent.com/631226923/d7e8397f-0450-4a19-a03e-5e7057f29363"></p>

[![codecov](https://codecov.io/gh/hans-thomas/valravn/branch/master/graph/badge.svg?token=X1D6I0JLSZ)](https://codecov.io/gh/hans-thomas/valravn)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/hans-thomas/valravn/php.yml)
![GitHub top language](https://img.shields.io/github/languages/top/hans-thomas/valravn)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/hans-thomas/valravn)

## What is it

Valravn is a set of predefined and feature reach classes on top of laravel
that brings many features to your app.
The classes are very open to overrides and developments. so feel free to
override or create and use classes on top of valravn classes.

## Installation

To add `Valravn` package to your project, you can install it using Composer.

```bash
composer require hans-thomas/valravn
```

Then, install Valravn resources using this command.

```bash
php artisan valravn:install
```

That's it.

## Tests

To start testing run below commands step by step:

```bash
> docker compose up -d
> docker compose exec app bash
> composer install
> composer run-script test
```

Support
-------

- [Documentation](https://valravn.vercel.app/)
- [Report bugs](https://github.com/hans-thomas/valravn/issues)

