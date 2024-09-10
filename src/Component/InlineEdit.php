<?php

declare(strict_types=1);
/**
 * A Simple inline editable text Vue component.
 */

namespace Phlex\Ui\Component;

use Phlex\Data\Model;
use Phlex\Ui\Exception;
use Phlex\Ui\JsCallback;
use Phlex\Ui\JsToast;
use Phlex\Ui\View;

class InlineEdit extends View
{
    public $defaultTemplate = 'inline-edit.html';

    /**
     * JsCallback for saving data.
     *
     * @var JsCallback
     */
    public $cb;

    /**
     * Input initial value.
     *
     * @var mixed
     */
    public $initValue;

    /**
     * Whether callback should save value to db automatically or not.
     * Default to using onChange handler.
     * If set to true, then saving to db will be done when model get set
     * and if model is loaded already.
     *
     * @var bool
     */
    public $autoSave = false;

    /**
     * The actual db field name that need to be saved.
     * Default to title field when model is set.
     *
     * @var string|null the name of the field
     */
    public $field;

    /**
     * Whether component should save it's value when input get blur.
     * Using this option will trigger callback when user is moving out of the
     * inline edit field, like pressing tab for example.
     *
     *  Otherwise, callback is fire when pressing Enter key,
     *  while inside the inline input field, only.
     *
     * @var bool
     */
    public $saveOnBlur = true;

    /**
     * Default css for the input div.
     *
     * @var string
     */
    public $inputCss = 'ui right icon input';

    /**
     * The validation error msg function.
     * This function is call when a validation error occur and
     * give you a chance to format the error msg display inside
     * errorNotifier.
     *
     * A default one is supply if this is null.
     * It receive the error ($e) as parameter.
     *
     * @var \Closure|null
     */
    public $formatErrorMsg;

    /**
     * Initialization.
     */
    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->cb = JsCallback::addTo($this);

        // Set default validation error handler.
        if (!$this->formatErrorMsg || !($this->formatErrorMsg instanceof \Closure)) {
            $this->formatErrorMsg = function ($e, $value) {
                $caption = $this->model->getField($this->field)->getCaption();

                return $caption . ' - ' . $e->getMessage() . '. <br>Trying to set this value: "' . $value . '"';
            };
        }
    }

    /**
     * Set Model of this View.
     *
     * @return Model
     */
    public function setModel(Model $model)
    {
        parent::setModel($model);
        $this->field = $this->field ?: $this->model->titleKey;
        if ($this->autoSave && $this->model->isLoaded()) {
            $value = $_POST['value'] ?? null;
            $this->cb->set(function () use ($value) {
                try {
                    $this->model->set($this->field, $this->getCodec($this->model->getField($this->field))->decode($value));
                    $this->model->save();

                    return $this->jsSuccess('Update successfully');
                } catch (Model\Field\ValidationException $e) {
                    $this->getApp()->terminateJson([
                        'success' => true,
                        'hasValidationError' => true,
                        'script' => $this->jsError(($this->formatErrorMsg)($e, $value))->jsRender(),
                    ]);
                }
            });
        }

        return $this->model;
    }

    /**
     * onChange handler.
     * You may supply your own function to handle update.
     * The function will receive one param:
     *  value: the new input value.
     */
    public function onChange(\Closure $fx)
    {
        if (!$this->autoSave) {
            $value = $_POST['value'] ?? null;
            $this->cb->set(function () use ($fx, $value) {
                return $fx($value);
            });
        }
    }

    /**
     * On success notifier.
     *
     * @param string $message
     *
     * @return JsToast
     */
    public function jsSuccess($message)
    {
        return new JsToast([
            'title' => 'Success',
            'message' => $message,
            'class' => 'success',
        ]);
    }

    /**
     * On validation error notifier.
     *
     * @param string $message
     *
     * @return JsToast
     */
    public function jsError($message)
    {
        return new JsToast([
            'title' => 'Validation error:',
            'displayTime' => 8000,
            'showIcon' => 'exclamation',
            'message' => $message,
            'class' => 'error',
        ]);
    }

    /**
     * Renders View.
     */
    protected function doRender(): void
    {
        parent::doRender();

        $type = ($this->model && $this->field) ? get_class($this->model->getField($this->field)->getValueType()) : 'text';
        switch ($type) {
            case Model\Field\Type\Float_::class:
            case Model\Field\Type\Integer::class:
                $type = 'number';

                break;
            case Model\Field\Type\String_::class:
            case Model\Field\Type\Text::class:
                $type = 'text';

                break;
            default:
                break;
        }

        if ($type !== 'text' && $type !== 'number') {
            throw new Exception('Only string or number field can be edited inline. Field Type = ' . $type);
        }

        if ($this->model && $this->model->isLoaded()) {
            $initValue = $this->model->get($this->field);
        } else {
            $initValue = $this->initValue;
        }

        $fieldName = $this->field ?: 'name';

        $this->vue('phlex-inline-edit', [
            'initValue' => $initValue,
            'url' => $this->cb->getJsUrl(),
            'saveOnBlur' => $this->saveOnBlur,
            'options' => ['fieldName' => $fieldName, 'fieldType' => $type, 'inputCss' => $this->inputCss],
        ]);
    }
}
