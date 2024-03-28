<?php

namespace Tests\Fixtures;

class Extend extends Base
{
    public function run(): void
    {
        $this->visibleForTesting();
        $this->noVisibleForTesting();
        $this->noVisibleForTestingWithPHPDoc();
    }
}
