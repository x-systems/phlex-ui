<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Control;

/**
 * Input element for a form control.
 */
class Hidden extends Input
{
    public $ui = '';
    public $layoutWrap = false;
    public $inputType = 'hidden';
}
