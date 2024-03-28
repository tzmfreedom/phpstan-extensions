<?php

namespace Tests\Fixtures;

class Failure
{
    public function run(): void
    {
        $base = new Base;
        $base->visibleForTesting();
        $base->noVisibleForTesting();
        $base->noVisibleForTestingWithPHPDoc();
    }
}
