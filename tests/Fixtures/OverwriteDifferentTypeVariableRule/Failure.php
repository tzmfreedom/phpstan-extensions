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
    $var = new Failure(); // assign different object type
    [,$class,] = [1,2,3];
//    [,$class,] = [1,'hoge',true];
}
