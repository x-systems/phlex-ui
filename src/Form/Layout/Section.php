<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Layout;

use Phlex\Ui\Form\Layout;
use Phlex\Ui\View;

/**
 * Form generic layout section.
 */
class Section extends View
{
    public $formLayout = Layout::class;
    public $form;

    /**
     * Adds sub-layout in existing layout.
     *
     * @return Layout
     */
    public function addSection()
    {
        return $this->addView([$this->formLayout, 'form' => $this->form]);
    }
}
