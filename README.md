Jasny Data cast
===

[![Build Status](https://travis-ci.org/jasny/data-cast.svg?branch=master)](https://travis-ci.org/jasny/meta-cast)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/data-cast/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/meta-cast/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/data-cast/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/data-cast/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/data-cast.svg)](https://packagist.org/packages/jasny/data-cast)
[![Packagist License](https://img.shields.io/packagist/l/jasny/data-cast.svg)](https://packagist.org/packages/jasny/data-cast)

Cast all properties of an object or items of an associative array using
[Jasny\TypeCast](https://github.com/jasny/typecast).

Installation
---

    composer require jasny/data-cast

Usage
---

Here's an example of casting data to class:

```php
use Jasny\Meta\Factory as MetaFactory;
use Jasny\TypeCast;
use Jasny\MetaCast\MetaCast;

$factory = new MetaFactory(...);
$typecast = new TypeCast();

$caster = new MetaCast($factory, $typecast);

// Elsewhere in the code
$data = $caster->cast(User::class, $input);
```
