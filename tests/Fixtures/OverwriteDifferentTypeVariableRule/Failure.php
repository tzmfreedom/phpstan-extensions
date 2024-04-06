<?php

namespace Tzmfreedom\Tests\Fixtures\OverwriteDifferentTypeVariableRule;

class Failure
{}

class Foo extends Failure
{}

function hoge()
{
    $var = 'hoge';
    $var = 1;
    $var = true;
    $var = new \stdClass();
    $class = new Failure(); // assign different object type
}
