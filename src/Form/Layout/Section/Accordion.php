<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Layout\Section;

use Phlex\Ui\AccordionSection;
use Phlex\Ui\Form;
use Phlex\Ui\Form\Layout;

/**
 * Represents form controls in accordion.
 */
class Accordion extends \Phlex\Ui\Accordion
{
    public $formLayout = Layout::class;
    public $form;

    /**
     * Initialization.
     *
     * Adds hook which in case of field error expands respective accordion sections.
     */
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->form->onHook(Form::HOOK_DISPLAY_ERROR, static function ($form, $fieldName, $str) {
            // default behavior
            $jsError = [$form->js()->form('add prompt', $fieldName, $str)];

            // if a form control is part of an accordion section, it will open that section.
            $section = $form->getClosestOwner($form->getControl($fieldName), AccordionSection::class);
            if ($section) {
                $jsError[] = $section->getOwner()->jsOpen($section);
            }

            return $jsError;
        });
    }

    /**
     * Return an accordion section with a form layout associate with a form.
     *
     * @param string $title
     * @param string $icon
     *
     * @return Layout
     */
    public function addSection($title, \Closure $callback = null, $icon = 'dropdown')
    {
        $section = parent::addSection($title, $callback, $icon);

        return $section->addView([$this->formLayout, 'form' => $this->form]);
    }

    /**
     * Return a section index.
     *
     * @param AccordionSection $section
     *
     * @return int
     */
    public function getSectionIdx($section)
    {
        if ($section instanceof AccordionSection) {
            return parent::getSectionIdx($section);
        }

        return parent::getSectionIdx($section->getOwner());
    }
}
