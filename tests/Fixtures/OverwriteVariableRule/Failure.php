<?php

namespace Tzmfreedom\Tests\Fixtures\OverwriteDifferentTypeVariableRule;

function hoge()
{
    $var = 'hoge';
    $var = 1;
    $var = null;
    $var = 1;
    [,$var] = [1, 2];
}
