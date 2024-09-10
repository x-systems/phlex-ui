<?php

declare(strict_types=1);

namespace Phlex\Ui\View\Field;

trait TypeRegistryTrait
{
    /**
     * Holds the resolution registry.
     *
     * @var array
     */
    //     protected $fieldTypesRegistry = [];

    public static function registerFieldType($fieldType, $resolveValue = null): void
    {
        if (is_array($fieldTypes = $fieldType)) {
            foreach ($fieldTypes as $fieldType => $resolveValue) {
                static::registerFieldType($fieldType, $resolveValue);
            }
        }

        static::$fieldTypesRegistry[$fieldType] = $resolveValue;
    }
}
