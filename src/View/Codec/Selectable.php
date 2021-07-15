<?php

declare(strict_types=1);

namespace Phlex\Ui\View\Codec;

use Phlex\Data\Model;

class Selectable extends Model\Field\Codec
{
    protected $separator = '<br>';

    protected function doEncode($values)
    {
    	if ($values === '') {
            return '';
        }
        
        $valueType = $this->getField()->getValueType();

        if (!$this->displaysMultipleValues()) {
        	return $valueType->getLabel($values);
        }
        
        return implode($this->separator, array_map(fn($value) => $valueType->getLabel($value), $values));
    }

    protected function displaysMultipleValues(): bool
    {
        return $this->field->getValueType()->allowMultipleSelection; // @phpstan-ignore-line
    }
}
