<?php declare(strict_types = 1);

namespace Tzmfreedom\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\UndefinedVariableException;
use PHPStan\Rules\Rule;
use PHPStan\Type\GeneralizePrecision;

class OverwriteVariableRule implements Rule
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
            if ($variableType->isNull()->yes()) {
                return [];
            }
            return [sprintf('Variable $%s should be assigned just once.', $node->var->name)];
        } catch (UndefinedVariableException $e) {
            return [];
        }
    }
}
