## PHPStan-VisibleForTesting

PHPStan extension to ensure that [VisibleForTesting](https://github.com/tzmfreedom/phpstan-visible-for-testing/blob/main/src/Attributes/VisibleForTesting.php) annotated public methods are called in private/protected scopes outside of the test environment, inspired by @VisibleForTesting annotation on [Flutter](https://api.flutter.dev/flutter/meta/visibleForTesting-constant.html), Java ([Guava](https://github.com/google/guava))

On no testing environment, there is an error.
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
// VisibleForTesting attributed method Foo::visibleForTestingWithAttribute should be called in private scope on no testing environment
```

## Installation

```bash
$ composer require --dev tzmfreedom/phpstan-visible-for-testing
```

```neon
rules:
	- Tzmfreedom\PHPStan\VisibleForTestingRule
```

## Example

