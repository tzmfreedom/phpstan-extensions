<?php declare(strict_types = 1);

namespace Tzmfreedom\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

class ClassPropertyDeclarationRule implements Rule
{
    public function __construct(private array $config)
    {
    }

    public function getNodeType(): string
    {
        return Property::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        assert(is_a($node, Property::class));
        foreach ($this->config as $key => $config) {
            if (str_contains($scope->getFile(), $key) && in_array($node->props[0]->name->name, $config, true)) {
                return [sprintf('Property declaration $%s is prohibited', $node->props[0]->name)];
            }
        }

        return [];
    }
}
