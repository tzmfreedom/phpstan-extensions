<?php declare(strict_types = 1);

namespace Tzmfreedom\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tzmfreedom\PHPStan\Rules\ClassPropertyDeclarationRule;

final class ClassPropertyDeclarationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ClassPropertyDeclarationRule([
            'ClassPropertyDeclarationRule' => ['prohibit'],
            'Failure' => ['safe'],
        ]);
    }

    public function test_it_succeeds_with_base_class(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/ClassPropertyDeclarationRule/Base.php'], []);
    }

    public function test_it_fails(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/ClassPropertyDeclarationRule/Failure.php'], [
            [
                'Property declaration $prohibit is prohibited',
                7,
            ],
        ]);
    }
}
