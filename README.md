Jasny Meta cast
===

[![Build Status](https://travis-ci.org/jasny/meta-cast.svg?branch=master)](https://travis-ci.org/jasny/meta-cast)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/meta-cast/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/meta-cast/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/meta-cast/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/meta-cast/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/meta-cast.svg)](https://packagist.org/packages/jasny/meta-cast)
[![Packagist License](https://img.shields.io/packagist/l/jasny/meta-cast.svg)](https://packagist.org/packages/jasny/meta-cast)

Cast values based on meta data.

Installation
---

    composer require jasny/meta-cast

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

The following dependencies are used:

* `Jasny\Meta\Factory` - factory for fetching meta data from class definition ([Jasny Meta](https://github.com/jasny/meta))
* `Jasny\TypeCast` - for casting various types of data ([Jasny TypeCast](https://github.com/jasny/typecast))

