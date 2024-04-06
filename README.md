# PHPStan Extensions

## VisibleForTestingRule

PHPStan custom rules to ensure that [VisibleForTesting](https://github.com/tzmfreedom/phpstan-visible-for-testing/blob/main/src/Attributes/VisibleForTesting.php) annotated public methods are called in private/protected scopes outside of the test environment, inspired by @VisibleForTesting annotation on [Flutter](https://api.flutter.dev/flutter/meta/visibleForTesting-constant.html), Java ([Guava](https://github.com/google/guava))

In following code, this extension report error outside of the test environment.
```php
<?php

use Tzmfreedom\Attributes\VisibleForTesting;

class Foo
{
    #[VisibleForTesting]
    public function exampleWithAttribute()
    {}

    /**
     * @visibleForTesting
     */
    public function exampleWithPhpdoc()
    {}
}

(new Foo)->exampleWithAttribute();
// error: VisibleForTesting annotated method Foo::visibleForTestingWithAttribute should be called in private scope outside of the test environment
```

## UnusedReturnRule

```php
<?php

class Foo
{
    public function getString(): string
    {
        return '';
    }
}

(new Foo)->getString(); // error: Return value on Method Foo::getString() is unused
$_ = (new Foo)->getString(); // OK
```

## OverwriteVariableRule 

```php
<?php

$var = null;
$var = 'hoge'; // OK, changing from null to any
$var = 'fuga'; // NG
```

## OverwriteDifferentTypeVariableRule

```php
<?php

$var = null;
$var = 'hoge'; // OK, changing from null to any
$var = 1; // NG, changing from string to integer
$var = 1.0; // OK, changing from integer to float
$var = 1; // OK, changeing from float to integer
$var = new \stdClass(); // NG
$var = new class extends \stdClass{}; // OK
$var = new \stdClass(); // OK
```


## Installation

```bash
$ composer require --dev tzmfreedom/phpstan-extensions
```

phpstan.neon
```neon
rules:
	- Tzmfreedom\PHPStan\Rules\VisibleForTestingRule
	- Tzmfreedom\PHPStan\Rules\UnusedReturnRule
```

