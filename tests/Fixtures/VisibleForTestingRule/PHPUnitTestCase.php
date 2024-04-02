<?php

namespace Tzmfreedom\Tests\Fixtures\VisibleForTestingRule;

use PHPUnit\Framework\TestCase;

class PHPUnitTestCase extends TestCase
{
    public function test(): void
    {
        $base = new Base();
        $base->visibleForTestingWithPhpDoc();
        $base->visibleForTestingWithAttribute();
        $base->noVisibleForTesting();
        $base->noVisibleForTestingWithPHPDoc();
    }
}

class PHPUnitTestCase2 extends PHPUnitTestCase
{
    public function test(): void
    {
        $base = new Base();
        $base->visibleForTestingWithPhpDoc();
        $base->visibleForTestingWithAttribute();
        $base->noVisibleForTesting();
        $base->noVisibleForTestingWithPHPDoc();
    }
}
