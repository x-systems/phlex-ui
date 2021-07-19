<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Layout\Section;

/**
 * Represents form controls in columns.
 */
class Columns extends \Phlex\Ui\Columns
{
    public $formLayout = \Phlex\Ui\Form\Layout::class;
    public $form;

    /**
     * Add new vertical column.
     *
     * @param int|array $defaults specify width (1..16) or relative to $width
     *
     * @return \Phlex\Ui\Form\Layout
     */
    public function addColumn($defaults = null)
    {
        $column = parent::addColumn($defaults);

        return $column->addView([$this->formLayout, 'form' => $this->form]);
    }
}
