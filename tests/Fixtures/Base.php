<?php

namespace Tests\Fixtures;

use VisibleForTesting\VisibleForTesting;

class Base
{
    /**
     * @return void
     * @visibleForTesting
     */
    #[VisibleForTesting]
    public function visibleForTesting(): void
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
        $this->visibleForTesting();
        $this->noVisibleForTesting();
        $this->noVisibleForTestingWithPHPDoc();
    }
}
