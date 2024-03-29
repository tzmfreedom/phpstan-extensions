<?php declare(strict_types = 1);

namespace Tzmfreedom\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Rules\Methods\MethodCallCheck;
use PHPStan\Rules\Rule;
use PHPStan\Type\FileTypeMapper;
use PHPUnit\Framework\TestCase;
use Tzmfreedom\Attributes\VisibleForTesting;

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
        $methodName = (string)$node->name;

        [$errors, $methodReflection] = $this->methodCallCheck->check($scope, $methodName, $node->var);
        if ($methodReflection === null) {
            return $errors;
        }
        if ($methodReflection->isPrivate()) {
            return [];
        }
        $classReflection = $methodReflection->getDeclaringClass();
        if (!$this->hasVisibleForTestingAttribute($classReflection, $methodReflection)) {
            return [];
        }

        $message = sprintf('VisibleForTesting annotated method %s::%s should be called in private scope outside of the test environment', $classReflection->getName(), $methodReflection->getName());
        if (!$scope->isInClass()) {
            return [$message];
        }
        if ($scope->getClassReflection()->is(TestCase::class)) {
            return [];
        }
        if ($scope->getClassReflection()->getName() !== $classReflection->getName()) {
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
}
