<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Core\Factory;

/**
 * Setup file - do not test.
 * Lookup that can not saved data.
 */
class DemoLookup extends \Phlex\Ui\Form\Control\Lookup
{
    /**
     * Add button for new record.
     */
    protected function initQuickNewRecord()
    {
        if (!$this->plus) {
            return;
        }

        $this->plus = is_bool($this->plus) ? 'Add New' : $this->plus;

        $this->plus = is_string($this->plus) ? ['button' => $this->plus] : $this->plus;

        $buttonSeed = $this->plus['button'] ?? [];

        $buttonSeed = is_string($buttonSeed) ? ['content' => $buttonSeed] : $buttonSeed;

        $defaultSeed = [\Phlex\Ui\Button::class, 'disabled' => ($this->disabled || $this->readonly)];

        $this->action = Factory::factory(array_merge($defaultSeed, (array) $buttonSeed));

        if ($this->form) {
            $vp = \Phlex\Ui\VirtualPage::addTo($this->form);
        } else {
            $vp = \Phlex\Ui\VirtualPage::addTo($this->getOwner());
        }

        $vp->set(function ($page) {
            $form = \Phlex\Ui\Form::addTo($page);

            $model = clone $this->model;

            $form->setModel($model->onlyFields($this->plus['fields'] ?? []));

            $form->onSubmit(function (\Phlex\Ui\Form $form) {
                // Prevent from saving
                // $form->model->save();

                $ret = [
                    new \Phlex\Ui\JsToast('Form submit!. Demo can not save data.'),
                    (new \Phlex\Ui\Jquery('.phlex-modal'))->modal('hide'),
                ];

                if ($row = $this->renderRow($form->model)) {
                    $chain = new \Phlex\Ui\Jquery('#' . $this->elementName . '-ac');
                    $chain->dropdown('set value', $row['value'])->dropdown('set text', $row['title']);

                    $ret[] = $chain;
                }

                return $ret;
            });
        });

        $caption = $this->plus['caption'] ?? 'Add New ' . $this->model->getCaption();

        $this->action->js('click', new \Phlex\Ui\JsModal($caption, $vp));
    }
}
