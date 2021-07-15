<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Control;

/**
 * Input element for a form control.
 */
class Money extends Input
{
    public function getValue()
    {
        $v = $this->field ? $this->field->get() : ($this->content ?: null);

        if ($v === null) {
            return;
        }

        return number_format($v, $this->getApp()->ui_persistence->currency_decimals);
    }

    protected function doRender(): void
    {
        if ($this->label === null) {
            $this->label = $this->getApp()->ui_persistence->currency;
        }

        parent::doRender();
    }
}
