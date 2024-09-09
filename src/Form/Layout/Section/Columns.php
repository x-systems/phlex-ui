<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Layout\Section;

use Phlex\Ui\Form\Layout;

/**
 * Represents form controls in columns.
 */
class Columns extends \Phlex\Ui\Columns
{
    public $formLayout = Layout::class;
    public $form;

    /**
     * Add new vertical column.
     *
     * @param int|array $defaults specify width (1..16) or relative to $width
     *
     * @return Layout
     */
    public function addColumn($defaults = null)
    {
        $column = parent::addColumn($defaults);

        return $column->addView([$this->formLayout, 'form' => $this->form]);
    }
}
