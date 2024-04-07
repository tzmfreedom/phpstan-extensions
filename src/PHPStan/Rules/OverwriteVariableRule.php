<?php declare(strict_types = 1);

namespace Tzmfreedom\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
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

        try {
            if ($node->var instanceof Variable) {
                $variableType = $scope->getVariableType($node->var->name)->generalize(GeneralizePrecision::lessSpecific());
                if ($variableType->isNull()->yes()) {
                    return [];
                }
                return [sprintf('Variable $%s should be assigned just once.', $node->var->name)];
            } else if ($node->var instanceof Array_) {
                $messages = [];
                foreach ($node->var->items as $item) {
                    if ($item === null) {
                        continue;
                    }
                    $variableType = $scope->getVariableType($item->value->name)->generalize(GeneralizePrecision::lessSpecific());
                    if ($variableType->isNull()->yes()) {
                        return [];
                    } else {
                        $messages[] = sprintf('Variable $%s should be assigned just once.', $item->value->name);
                    }
                }
                return $messages;
            }
            throw new \Exception(sprintf('Unexpected type: %s', get_class($node->var)));
        } catch (UndefinedVariableException $e) {
            return [];
        }
    }
}
