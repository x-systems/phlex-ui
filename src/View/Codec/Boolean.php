<?php

declare(strict_types=1);

namespace Phlex\Ui\View\Codec;

use Phlex\Data\Model;

/**
 * @method \Phlex\Data\Model\Field\Type\Boolean getValueType()
 */
class Boolean extends Model\Field\Codec
{
    public $valueTrue = 'Yes';

    public $valueFalse = 'No';

    protected function doEncode($value)
    {
        if ($value === $this->getValueType()->valueTrue) {
            $value = $this->valueTrue;
        } elseif ($value === $this->getValueType()->valueFalse) {
            $value = $this->valueFalse;
        }

        return $value;
    }
}
