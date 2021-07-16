<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Control;

use Phlex\Ui\Form;
use Phlex\Ui\Lister;

/**
 * Input element for a form control.
 */
class Radio extends Form\Control
{
    public $ui = false;

    public $defaultTemplate = 'form/control/radio.html';

    /**
     * Contains a lister that will render individual radio buttons.
     *
     * @var Lister
     */
    public $lister;

    /**
     * List of values.
     *
     * @var array
     */
    public $values = [];

    /**
     * Initialization.
     */
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->lister = Lister::addTo($this, [], ['Radio']);
        $this->lister->templateRow->set('_name', $this->short_name);
    }

    protected function doRender(): void
    {
        if (!$this->model) {
            $this->setSource($this->values);
        }

        $value = $this->field ? $this->field->get() : $this->content;

        $this->lister->setModel($this->model);

        // take care of readonly and disabled statuses
        if ($this->disabled) {
            $this->addClass('disabled');
        }

        $this->lister->onHook(Lister::HOOK_BEFORE_ROW, function (Lister $lister) use ($value) {
            if ($this->readonly) {
                $lister->templateRow->set('disabled', $value !== (string) $lister->model->getId() ? 'disabled="disabled"' : '');
            } elseif ($this->disabled) {
                $lister->templateRow->set('disabled', 'disabled="disabled"');
            }

            $lister->templateRow->set('checked', $value === (string) $lister->model->getId() ? 'checked' : '');
        });

        parent::doRender();
    }

    /**
     * Shorthand method for on('change') event.
     * Some input fields, like Calendar, could call this differently.
     *
     * If $expr is string or JsExpression, then it will execute it instantly.
     * If $expr is callback method, then it'll make additional request to webserver.
     *
     * Examples:
     * $control->onChange('console.log("changed")');
     * $control->onChange(new \Phlex\Ui\JsExpression('console.log("changed")'));
     * $control->onChange('$(this).parents(".form").form("submit")');
     *
     * @param string|\Phlex\Ui\JsExpression|array|\Closure $expr
     * @param array|bool                                   $default
     */
    public function onChange($expr, $default = [])
    {
        if (is_string($expr)) {
            $expr = new \Phlex\Ui\JsExpression($expr);
        }

        if (is_bool($default)) {
            $default['preventDefault'] = $default;
            $default['stopPropagation'] = $default;
        }

        $this->on('change', 'input', $expr, $default);
    }
}
