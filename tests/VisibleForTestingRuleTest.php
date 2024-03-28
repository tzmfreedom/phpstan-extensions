<?php declare(strict_types = 1);

namespace Tzmfreedom\Tests;

use PHPStan\Rules\Methods\MethodCallCheck;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tzmfreedom\PHPStan\VisibleForTestingRule;

final class VisibleForTestingRuleTest extends RuleTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        putenv('__IS_TESTING_VISIBLE_FOR_TESTING=1');
    }

    protected function getRule(): Rule
    {
        $reflectionProvider = $this->createReflectionProvider();
        $ruleLevelHelper = new RuleLevelHelper($reflectionProvider, true, false, true, true, true, true, false);
        $callback = new MethodCallCheck($reflectionProvider, $ruleLevelHelper, true, true);


        return new VisibleForTestingRule($callback, self::getContainer()->getByType(FileTypeMapper::class));
    }

    public function test_it_succeeds_with_base_class(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/Base.php'], []);
    }

    public function test_it_succeeds_with_extended_class(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/Extend.php'], []);
    }

    public function test_it_raises_error_when_using_calling_from_other_class(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/Failure.php'],
            [
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\Base::visibleForTestingWithPhpDoc should be called in private scope outside of the test environment',
                    10
                ],
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\Base::visibleForTestingWithAttribute should be called in private scope outside of the test environment',
                    11
                ],
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\Base::visibleForTestingWithPhpDoc should be called in private scope outside of the test environment',
                    18
                ],
                [
                    'VisibleForTesting annotated method Tzmfreedom\Tests\Fixtures\Base::visibleForTestingWithAttribute should be called in private scope outside of the test environment',
                    19
                ],
            ]
        );
    }

    public function test_it_succeeds_when_testing(): void
    {
        putenv('__IS_TESTING_VISIBLE_FOR_TESTING=');

        $this->analyse([__DIR__ . '/Fixtures/Failure.php'], []);
    }
}
