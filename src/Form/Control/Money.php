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

        return number_format($v, $this->getCodec($this->field)->decimals ?? 2);
    }

    protected function doRender(): void
    {
        $this->label ??= $this->getCodec($this->field)->currency ?? null;

        parent::doRender();
    }
}
