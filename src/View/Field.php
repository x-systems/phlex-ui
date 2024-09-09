<?php

declare(strict_types=1);

namespace Phlex\Ui\View;

use Phlex\Core\Utils;
use Phlex\Data\Model;

class Field
{
    public const OPTION_EDITABLE = self::class . '@editable';
    public const OPTION_VISIBLE = self::class . '@visible';
    public const OPTION_HIDDEN = self::class . '@hidden';
    public const OPTION_CAPTION = self::class . '@caption';

    public const FILTER_EDITABLE = self::class . '@filterEditable';
    public const FILTER_VISIBLE = self::class . '@filterVisible';
    public const FILTER_HIDDEN = self::class . '@filterHidden';

    /**
     * Returns if field should be editable in UI.
     */
    public static function isEditable(Model\Field $field): bool
    {
        return $field->getOption(self::OPTION_EDITABLE) ?? !$field->isReadOnly() && $field->interactsWithPersistence() && !$field->system;
    }

    /**
     * Returns if field should be visible in UI.
     */
    public static function isVisible(Model\Field $field): bool
    {
        return $field->getOption(self::OPTION_VISIBLE) ?? !$field->system;
    }

    /**
     * Returns if field should be hidden in UI.
     */
    public static function isHidden(Model\Field $field): bool
    {
        return $field->getOption(self::OPTION_HIDDEN) ?? false;
    }

    /**
     * Returns field caption for use in UI.
     */
    public static function getCaption(Model\Field $field): string
    {
        return $field->caption ?? $field->getOption(self::OPTION_CAPTION) ?? Utils::getReadableCaption($field->getKey());
    }

    public static function getActiveFieldKeys(Model $model, $filter = null): array
    {
        return [];
    }
}
