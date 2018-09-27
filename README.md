Jasny Recursive Typecast
===

[![Build Status](https://travis-ci.org/jasny/recursive-typecast.svg?branch=master)](https://travis-ci.org/jasny/meta-cast)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/recursive-typecast/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/meta-cast/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/recursive-typecast/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/recursive-typecast/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/recursive-typecast.svg)](https://packagist.org/packages/jasny/recursive-typecast)
[![Packagist License](https://img.shields.io/packagist/l/jasny/recursive-typecast.svg)](https://packagist.org/packages/jasny/recursive-typecast)

Cast all properties of an object or items of an associative array using
[Jasny\TypeCast](https://github.com/jasny/typecast).

Installation
---

    composer require jasny/recursive-typecast

Usage
---

Here's an example of casting data to class:

```php
use Jasny\Meta\Factory as MetaFactory;
use Jasny\TypeCast;
use Jasny\RecursiveTypeCast\MetaCast;
use Jasny\RecursiveTypeCast\Handler\DataHandler;

$factory = new MetaFactory(...);
$typecast = new TypeCast();

$caster = new MetaCast($factory, $typecast);

// Elsewhere in the code
$data = $caster->to(User::class)->cast($input);
```
