<?php declare(strict_types = 1);

namespace VisibleForTesting;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Rules\Methods\MethodCallCheck;
use PHPStan\Rules\Rule;
use PHPStan\Type\FileTypeMapper;

class VisibleForTestingRule implements Rule
{
    public function __construct(
        private MethodCallCheck $methodCallCheck,
        private FileTypeMapper $fileTypeMapper,
    )
    {
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($this->isInRunningTest()) {
            return [];
        }
        $methodName = (string) $node->name;

        [$errors, $methodReflection] = $this->methodCallCheck->check($scope, $methodName, $node->var);
        if ($methodReflection === null) {
            return $errors;
        }
        $classReflection = $methodReflection->getDeclaringClass();
        if (!$this->hasVisibleForTestingAttribute($classReflection, $methodReflection)) {
            return [];
        }

        $message = sprintf('VisibleForTesting annotated method %s::%s should be called in private scope on no testing environment', $classReflection->getName(), $methodReflection->getName());
        if (!$scope->isInClass()) {
            return [$message];
        }

        if (!$scope->getClassReflection()->is($methodReflection->getDeclaringClass()->getName())) {
            return [$message];
        }
        return [];
    }

    private function hasVisibleForTestingAttribute(ClassReflection $classReflection, ExtendedMethodReflection $methodReflection): bool
    {
        $attributes = $classReflection->getNativeReflection()
            ->getMethod($methodReflection->getName())
            ->getAttributes(VisibleForTesting::class);
        if (count($attributes) > 0) {
            return true;
        }
        if ($methodReflection->getDocComment() === null) {
            return false;
        }
        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $classReflection->getFileName(),
            $classReflection->getName(),
            null,
            null,
            $methodReflection->getDocComment(),
        );

        if (count($resolvedPhpDoc->getPhpDocNodes()) === 0) {
            return false;
        }
        $tags = $resolvedPhpDoc->getPhpDocNodes()[0]->getTagsByName('@visibleForTesting');
        if (count($tags) === 0) {
            return false;
        }
        return true;
    }

    private function isInRunningTest()
    {
        if (getenv('__IS_TESTING_VISIBLE_FOR_TESTING')) {
            return false;
        }

        if (PHP_SAPI !== 'cli') {
            return false;
        }

        if (defined('PHPUNIT_COMPOSER_INSTALL') && defined('__PHPUNIT_PHAR__')) {
            return true;
        }

        if (str_contains($_SERVER['argv'][0], 'phpunit')) {
            return true;
        }

        return false;
    }
}
