<?php declare(strict_types = 1);

namespace Tzmfreedom\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tzmfreedom\PHPStan\Rules\UnusedReturnRule;

final class UnusedReturnRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new UnusedReturnRule();
    }

    public function test_it_succeeds_with_base_class(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/UnusedReturnRule/Base.php'], [
            [
                'Return value on Method Tzmfreedom\Tests\Fixtures\UnusedReturnRule\Base::string() is unused',
                27,
            ],
            [
                'Return value on Method Tzmfreedom\Tests\Fixtures\UnusedReturnRule\Base::stringWithPhpDoc() is unused',
                28,
            ]
        ]);
    }
}
