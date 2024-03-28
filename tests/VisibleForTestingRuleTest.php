<?php declare(strict_types = 1);

namespace Tests;

use VisibleForTesting\VisibleForTestingRule;
use PHPStan\Rules\Methods\MethodCallCheck;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;

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
                    sprintf('VisibleForTesting annotated method Tests\Fixtures\Base::visibleForTesting should be called in private scope on no testing environment'),
                    10
                ]
            ]
        );
    }
}
