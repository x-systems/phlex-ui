<?php

declare(strict_types=1);

namespace Phlex\Ui\View\Codec;

use Phlex\Data\Model;

/**
 * @method \Phlex\Data\Model\Field\Type\Boolean getValueType()
 */
class Array_ extends Model\Field\Codec
{
	public $separator = ', ';

    protected function doEncode($value)
    {
        return implode($this->separator, $value);
    }
}
