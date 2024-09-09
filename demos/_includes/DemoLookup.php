<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Core\Factory;
use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\Form\Control\Lookup;
use Phlex\Ui\Jquery;
use Phlex\Ui\JsModal;
use Phlex\Ui\JsToast;
use Phlex\Ui\VirtualPage;

/**
 * Setup file - do not test.
 * Lookup that can not saved data.
 */
class DemoLookup extends Lookup
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

        $defaultSeed = [Button::class, 'disabled' => ($this->disabled || $this->readonly)];

        $this->action = Factory::factory(array_merge($defaultSeed, (array) $buttonSeed));

        if ($this->form) {
            $vp = VirtualPage::addTo($this->form);
        } else {
            $vp = VirtualPage::addTo($this->getOwner());
        }

        $vp->set(function ($page) {
            $form = Form::addTo($page);

            $model = clone $this->model;

            $form->setModel($model->onlyFields($this->plus['fields'] ?? []));

            $form->onSubmit(function (Form $form) {
                // Prevent from saving
                // $form->model->save();

                $ret = [
                    new JsToast('Form submit!. Demo can not save data.'),
                    (new Jquery('.phlex-modal'))->modal('hide'),
                ];

                if ($row = $this->renderRow($form->model)) {
                    $chain = new Jquery('#' . $this->elementName . '-ac');
                    $chain->dropdown('set value', $row['value'])->dropdown('set text', $row['title']);

                    $ret[] = $chain;
                }

                return $ret;
            });
        });

        $caption = $this->plus['caption'] ?? 'Add New ' . $this->model->getCaption();

        $this->action->js('click', new JsModal($caption, $vp));
    }
}
