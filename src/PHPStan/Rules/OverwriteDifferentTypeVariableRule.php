<?php declare(strict_types = 1);

namespace Tzmfreedom\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\UndefinedVariableException;
use PHPStan\Rules\Rule;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\GeneralizePrecision;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

class OverwriteDifferentTypeVariableRule implements Rule
{
    public function getNodeType(): string
    {
        return Assign::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof Assign);
        assert($node->var instanceof Variable);

        try {
            $variableType = $scope->getVariableType($node->var->name)->generalize(GeneralizePrecision::lessSpecific());
            $exprType = $scope->getType($node->expr)->generalize(GeneralizePrecision::lessSpecific());
        } catch (UndefinedVariableException $e) {
            return [];
        }
        if ($variableType instanceof UnionType && !$variableType->isNull()->no()) {
            $variableType = $variableType->tryRemove(new NullType())->generalize(GeneralizePrecision::lessSpecific());
        }
        if ($variableType instanceof UnionType) {
            return [];
        }
        if ($variableType instanceof NullType) {
            return [];
        }
        if ($variableType instanceof StringType && $exprType instanceof StringType) {
            return [];
        }
        if ($this->isNumberType($variableType) && $this->isNumberType($exprType)) {
            return [];
        }
        if ($variableType instanceof BooleanType && $exprType instanceof BooleanType) {
            return [];
        }
        if ($variableType instanceof ObjectType && $exprType instanceof ObjectType) {
            if ($variableType->isSuperTypeOf($exprType)->no() && $exprType->isSuperTypeOf($variableType)->no()) {
                return [sprintf('Assigned object type %s is different from expression object type %s.', $variableType->getClassName(), $exprType->getClassName())];
            }
            return [];
        }
        $variableTypeClassName = $this->getClassName($variableType);
        $expressionTypeClassName = $this->getClassName($exprType);
        return [sprintf('Assign type %s is different from expression type %s.', $variableTypeClassName, $expressionTypeClassName)];
    }

    private function isNumberType(Type $type): bool
    {
        return $type instanceof IntegerType || $type instanceof FloatType;
    }

    private function getClassName(Type $type): string
    {
        $class = explode('\\', get_class($type));
        return str_replace('Type', '', $class[count($class) - 1]);
    }
}
