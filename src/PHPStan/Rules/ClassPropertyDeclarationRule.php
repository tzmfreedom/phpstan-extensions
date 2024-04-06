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

        $messages = [];
        foreach ($node->props as $prop) {
            $propertyName = $prop->name->name;
            foreach ($this->config as $key => $config) {
                if (str_contains($scope->getFile(), $key) && in_array($propertyName, $config, true)) {
                    $messages[] = sprintf('Property declaration $%s is prohibited', $propertyName);
                }
            }
        }
        return $messages;
    }
}
