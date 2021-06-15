<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Layout\Section;

/**
 * Represents form controls in tabs.
 */
class Tabs extends \Phlex\Ui\Tabs
{
    public $formLayout = \Phlex\Ui\Form\Layout::class;
    public $form;

    /**
     * Adds tab in tabs widget.
     *
     * @param string|\Phlex\Ui\Tab $name     Name of tab or Tab object
     * @param \Closure             $callback Callback action or URL (or array with url + parameters)
     * @param array                $settings tab settings
     *
     * @return \Phlex\Ui\Form\Layout
     */
    public function addTab($name, \Closure $callback = null, $settings = [])
    {
        $tab = parent::addTab($name, $callback, $settings);

        return $tab->add([$this->formLayout, 'form' => $this->form]);
    }
}
