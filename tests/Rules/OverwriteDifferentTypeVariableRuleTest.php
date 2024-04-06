<?php declare(strict_types = 1);

namespace Tzmfreedom\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tzmfreedom\PHPStan\Rules\OverwriteDifferentTypeVariableRule;

final class OverwriteDifferentTypeVariableRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new OverwriteDifferentTypeVariableRule();
    }

    public function test_it_succeeds_with_base_class(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/OverwriteDifferentTypeVariableRule/Base.php'], []);
    }

    public function test_it_fails(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/OverwriteDifferentTypeVariableRule/Failure.php'], [
            [
                'Assign type String is different from expression type Integer.',
                14,
            ],
            [
                'Assign type Integer is different from expression type Boolean.',
                15,
            ],
            [
                'Assign type Boolean is different from expression type Object.',
                16,
            ],
        ]);
    }
}
