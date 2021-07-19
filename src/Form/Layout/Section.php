<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Layout;

/**
 * Form generic layout section.
 */
class Section extends \Phlex\Ui\View
{
    public $formLayout = \Phlex\Ui\Form\Layout::class;
    public $form;

    /**
     * Adds sub-layout in existing layout.
     *
     * @return \Phlex\Ui\Form\Layout
     */
    public function addSection()
    {
        return $this->addView([$this->formLayout, 'form' => $this->form]);
    }
}
