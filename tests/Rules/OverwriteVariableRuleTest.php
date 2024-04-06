<?php declare(strict_types = 1);

namespace Tzmfreedom\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tzmfreedom\PHPStan\Rules\OverwriteVariableRule;

final class OverwriteVariableRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new OverwriteVariableRule();
    }

    public function test_it_succeeds_with_base_class(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/OverwriteVariableRule/Base.php'], []);
    }

    public function test_it_fails(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/OverwriteVariableRule/Failure.php'], [
            [
                'Variable $var should be assigned just once.',
                8,
            ],
            [
                'Variable $var should be assigned just once.',
                9,
            ],
        ]);
    }
}
