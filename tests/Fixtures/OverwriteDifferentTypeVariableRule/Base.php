<?php

namespace Tzmfreedom\Tests\Fixtures\OverwriteDifferentTypeVariableRule;

class Base
{}

class Foo extends Base
{}

function hoge()
{
    $string = null;
    if (random_int(0, 1)) {
        $string = 1;
    } else {
        $string = 'hoge';
    }
    $string = 'hoge';
    $string = 'fuga';
    $string = random_bytes(10);

    $number = 1;
    $number = 2.0;
    $number = 3;
    $number = random_int(0, 10);

    $boolean = true;
    $boolean = false;
    $boolean = true;

    $class = new Base();
    $class = new Base(); // assign same object type
    $class = new Foo(); // assign extended object type
    $class = new Base(); // assign subtype object type
}
