<?php

namespace Tzmfreedom\Tests\Fixtures;

class Failure
{
    public function run(): void
    {
        $base = new Base;
        $base->visibleForTestingWithPhpDoc();
        $base->visibleForTestingWithAttribute();
        $base->noVisibleForTesting();
        $base->noVisibleForTestingWithPHPDoc();
    }
}

$base = new Base;
$base->visibleForTestingWithPhpDoc();
$base->visibleForTestingWithAttribute();
$base->noVisibleForTesting();
$base->noVisibleForTestingWithPHPDoc();
