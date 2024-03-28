<?php

namespace Tests\Fixtures;

use VisibleForTesting\VisibleForTesting;

class Base
{
    /**
     * @return void
     * @visibleForTesting
     */
    public function visibleForTestingWithPhpDoc(): void
    {}

    #[VisibleForTesting]
    public function visibleForTestingWithAttribute(): void
    {}

    public function noVisibleForTesting(): void
    {}

    /**
     * @return void
     */
    public function noVisibleForTestingWithPHPDoc(): void
    {}

    public function run(): void
    {
        $this->visibleForTestingWithPhpDoc();
        $this->visibleForTestingWithAttribute();
        $this->noVisibleForTesting();
        $this->noVisibleForTestingWithPHPDoc();
    }
}
