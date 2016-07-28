#vperyod.simple-lock
Simple *low security* access restriction/password protection PSR7 middleware

[![Latest version][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

## Installation
```bash
composer require vperyod/simple-lock
```

## Usage

```php
use Vperyod\SimpleLock\LockHandler;
use Vperyod\SimpleLock\Verify\Password;

if ($_ENV['LOCK']) {
    $adr->middle(new LockHandler(new Password($_ENV['LOCK_PW'])));
}
```


[ico-version]: https://img.shields.io/packagist/v/vperyod/simple-lock.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/vperyod/vperyod.simple-lock/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/vperyod/vperyod.simple-lock.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/vperyod/vperyod.simple-lock.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/vperyod/simple-lock
[link-travis]: https://travis-ci.org/vperyod/vperyod.simple-lock
[link-scrutinizer]: https://scrutinizer-ci.com/g/vperyod/vperyod.simple-lock
[link-code-quality]: https://scrutinizer-ci.com/g/vperyod/vperyod.simple-lock
