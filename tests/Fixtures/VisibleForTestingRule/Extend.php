<?php

namespace Tzmfreedom\Tests\Fixtures\VisibleForTestingRule;

class Extend extends Base
{
    public function run(): void
    {
        $this->visibleForTestingWithPhpDoc();
        $this->visibleForTestingWithAttribute();
        $this->noVisibleForTesting();
        $this->noVisibleForTestingWithPHPDoc();
    }
}
