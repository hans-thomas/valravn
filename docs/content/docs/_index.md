---
title: "Getting started"
weight: 1
---

<p><img alt="valravn banner" src="/images/valravn-banner.png"></p>

[![codecov](https://codecov.io/gh/hans-thomas/valravn/branch/master/graph/badge.svg?token=X1D6I0JLSZ)](https://codecov.io/gh/hans-thomas/valravn)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/hans-thomas/valravn/php.yml)
![GitHub top language](https://img.shields.io/github/languages/top/hans-thomas/valravn)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/hans-thomas/valravn)
![StyleCi](https://github.styleci.io/repos/631226923/shield?style=flat&branch=v1)

## What is it

Valravn is a set of predefined and feature-rich classes on top of laravel
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

To start testing clone the repository and then run below commands step by step:

```bash
> docker compose up -d
> docker compose exec app bash
> composer install
> composer run-script test
```

## Contributing

1. Fork it!
2. Create your feature branch: git checkout -b my-new-feature
3. Commit your changes: git commit -am 'Add some feature'
4. Push to the branch: git push origin my-new-feature
5. Submit a pull request ❤️


Support
-------

- [Documentation](https://valravn.vercel.app/)
- [Report bugs](https://github.com/hans-thomas/valravn/issues)

{{< column "quick-start-container" >}}
{{< column >}}
Let's build something amazing.
{{< /column >}}
{{< button "/docs/basics" "Quickstart guide →" "" >}}
{{< /column >}}




