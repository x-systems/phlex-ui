<?php

declare(strict_types=1);

namespace Phlex\Ui\View\Codec;

use Phlex\Data\Model;

class Money extends Model\Field\Codec
{
    public $currency = '€';

    /**
     * Default decimal count for type 'money'
     *  Used directly in number_format() second parameter.
     *
     * @var int
     */
    public $decimals = 2;

    protected function doEncode($value)
    {
        if ($value === '') {
            return '';
        }

        return ($this->currency ? $this->currency . ' ' : '') . number_format((float) $value, $this->decimals);
    }
}
