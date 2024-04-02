<?php

namespace Tzmfreedom\Tests\Fixtures\UnusedReturnRule;

class Base
{
    public function string(): string
    {}

    /**
     * @return string
     */
    public function stringWithPhpDoc()
    {}

    public function void(): void
    {}

    /**
     * @return void
     */
    public function voidWithPhpDoc()
    {}

    public function run(): void
    {
        $this->string();
        $this->stringWithPhpDoc();
        $_ = $this->string();
        $_ = $this->stringWithPhpDoc();
        $this->void();
        $this->voidWithPhpDoc();
    }
}
