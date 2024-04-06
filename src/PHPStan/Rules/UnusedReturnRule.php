<?php declare(strict_types = 1);

namespace Tzmfreedom\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Rules\Rule;

class UnusedReturnRule implements Rule
{
    public function getNodeType(): string
    {
        return Expression::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof Expression);

        if (!$node->expr instanceof Node\Expr\MethodCall) {
            return [];
        }
        $methodCall = $node->expr;
        $methodName = $methodCall->name->name;
        try {
            $methodReflection = $scope->getType($node->expr->var)
                ->getMethod($methodName, $scope);
        } catch (MissingMethodFromReflectionException $e) {
            return [];
        }
        $variant = ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $methodCall->getArgs(),
            $methodReflection->getVariants()
        );
        $returnType = $variant->getReturnType();
        if ($returnType->isVoid()->no()) {
            return [sprintf('Return value on Method %s::%s() is unused', $methodReflection->getDeclaringClass()->getName(), $methodName)];
        }
        return [];
    }
}
