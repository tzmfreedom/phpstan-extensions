# PHPStan-VisibleForTesting

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
// VisibleForTesting annotated method Foo::visibleForTestingWithAttribute should be called in private scope outside of the test environment
```

## Installation

```bash
$ composer require --dev tzmfreedom/phpstan-visible-for-testing
```

phpstan.neon
```neon
rules:
	- Tzmfreedom\PHPStan\VisibleForTestingRule
```

