<?php

declare(strict_types=1);

namespace Phlex\Ui\UserAction;

use Phlex\Core\Factory;
use Phlex\Data\Model;
use Phlex\Ui\Exception;
use Phlex\Ui\Form;
use Phlex\Ui\Header;

/**
 * BasicExecutor executor will typically fail if supplied arguments are not sufficient.
 *
 * ArgumentFormExecutor will ask user to fill in the blanks
 */
class ArgumentFormExecutor extends BasicExecutor
{
    /**
     * @var Form
     */
    public $form;

    /**
     * Initialization.
     */
    public function initPreview()
    {
        Header::addTo($this, [$this->action->getCaption(), 'subHeader' => $this->description ?: $this->action->getDescription()]);
        $this->form = Form::addTo($this, ['buttonSave' => $this->executorButton]);

        foreach ($this->action->args as $key => $val) {
            if (is_numeric($key)) {
                throw (new Exception('Action arguments must be named'))
                    ->addMoreInfo('args', $this->action->args);
            }

            if ($val instanceof Model) {
                $val = ['model' => $val];
            }

            if (isset($val['model'])) {
                $val['model'] = Factory::factory($val['model']);
                $this->form->addControl($key, [Form\Control\Lookup::class])->setModel($val['model']);
            } else {
                $this->form->addControl($key, null, $val);
            }
        }

        $this->form->onSubmit(function (Form $form) {
            // set arguments from the model
            $this->setArguments($form->model->get());

            return $this->executeModelAction();
        });
    }
}
