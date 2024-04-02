<?php declare(strict_types = 1);

namespace Tzmfreedom\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tzmfreedom\PHPStan\Rules\VisibleForTestingRule;

final class VisibleForTestingRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new VisibleForTestingRule(self::getContainer()->getByType(FileTypeMapper::class));
    }

    public function test_it_succeeds_with_base_class(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/VisibleForTestingRule/Base.php'], []);
    }

    public function test_it_succeeds_with_phpunit_test_case(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/VisibleForTestingRule/PHPUnitTestCase.php'], []);
    }

    public function test_it_raises_error_when_called_on_extended_class(): void
    {
        $this->analyse(
            [__DIR__ . '/../Fixtures/VisibleForTestingRule/Extend.php'],
            [
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\VisibleForTestingRule\Base::visibleForTestingWithPhpDoc should be called in private scope outside of the test environment',
                    9
                ],
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\VisibleForTestingRule\Base::visibleForTestingWithAttribute should be called in private scope outside of the test environment',
                    10
                ]
            ]
        );
    }

    public function test_it_raises_error_when_called_on_other_class(): void
    {
        $this->analyse(
            [__DIR__ . '/../Fixtures/VisibleForTestingRule/Failure.php'],
            [
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\VisibleForTestingRule\Base::visibleForTestingWithPhpDoc should be called in private scope outside of the test environment',
                    10
                ],
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\VisibleForTestingRule\Base::visibleForTestingWithAttribute should be called in private scope outside of the test environment',
                    11
                ],
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\VisibleForTestingRule\Base::visibleForTestingWithPhpDoc should be called in private scope outside of the test environment',
                    18
                ],
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\VisibleForTestingRule\Base::visibleForTestingWithAttribute should be called in private scope outside of the test environment',
                    19
                ],
            ]
        );
    }
}
