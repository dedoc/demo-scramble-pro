<?php

namespace App\Support\Documentation;

use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;
use Dedoc\ScramblePro\Extensions\LaravelData\Generator\DataSchemaExtension;

class GoToDefinitionSchemaExtension extends DataSchemaExtension
{
    use DefinitionLink;

    public function toSchema(Type $type)
    {
        $schema = parent::toSchema($type);

        $schema->setDescription(
            $schema->description . ' ' . $this->getGoToDefinitionLink($type),
        );

        return $schema;
    }

    private function getClassName(ObjectType $type): string
    {
        return $type->name;
    }
}
