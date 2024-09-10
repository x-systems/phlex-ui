<?php

declare(strict_types=1);
/**
 * Creates a Multiline field within a table, which allows adding/editing multiple
 * data rows.
 *
 * Using hasMany reference will required to save reference data using Multiline::saveRows() method.
 *
 * $form = Form::addTo($webpage);
 * $form->setModel($invoice, false);
 *
 * // Add Multiline form control and set model for Invoice items.
 * $ml = $form->addControl('ml', ['Multiline::class']);
 * $ml->setReferenceModel('Items', null, ['item', 'cat', 'qty', 'price', 'total']);
 *
 * $form->onSubmit(function($form) use ($ml) {
 *     // Save Form model and then Multiline model
 *     $form->model->save(); // Saving Invoice record.
 *     $ml->saveRows(); // Saving invoice items record related to invoice.
 *     return new \Phlex\Ui\JsToast('Saved!');
 * });
 *
 * If Multiline's model contains expressions, these will be evaluated on the fly
 * whenever data gets entered.
 *
 * Multiline input also has an onChange callback that will return all data rows
 * in an array. It is also possible to fire onChange handler only for certain
 * fields by passing them as an array to the method.
 *
 * Note that deleting a row will always fire the onChange callback.
 *
 * You can use the returned data to update other related areas of the form.
 * For example, ypdating Grand Total field of all invoice items.
 *
 * $ml->onChange(function($rows) use ($form) {
 *     $grand_total = 0;
 *     foreach ($rows as $row => $cols) {
 *         foreach ($cols as $col) {
 *             $fieldName = key($col);
 *                 if ($fieldName === 'total') {
 *                     $grand_total = $grand_total + $col[$fieldName];
 *                 }
 *          }
 *     }
 *
 *   return $form->js(true, null, 'input[name="grand_total"]')->val(number_format($grand_total, 2));
 * }, ['qty', 'price']);
 *
 * Finally, it's also possible to use Multiline for quickly adding records to a
 * model. Be aware that in the example below all User records will be displayed.
 * If your model contains a lot of records, you should handle their limit somehow.
 *
 * $form = Form::addTo($webpage);
 * $ml = $form->addControl('ml', [Form\Control\Multiline::class]);
 * $ml->setModel($user, ['name','is_vip']);
 *
 * $form->onSubmit(function($form) use ($ml) {
 *     $ml->saveRows();
 *     return new JsToast('Saved!');
 * });
 */

namespace Phlex\Ui\Form\Control;

use Phlex\Data\Model;
use Phlex\Data\Model\Field\Type\Selectable;
use Phlex\Data\Model\Field\Type\Text;
use Phlex\Data\Persistence\Sql;
use Phlex\Ui\Exception;
use Phlex\Ui\Form;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\JsCallback;
use Phlex\Ui\JsFunction;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

class Multiline extends Form\Control
{
    use VueLookupTrait;

    public const OPTION_PRESETS = self::class . '@presets';

    /** @var HtmlTemplate The template needed for the multiline view. */
    public $multiLineTemplate;

    /** @var View The multiline View. Assigned in doInitialize(). */
    private $multiLine;

    // Components name
    public const INPUT = 'sui-input';
    public const READ_ONLY = 'phlex-multiline-readonly';
    public const TEXT_AREA = 'phlex-multiline-textarea';
    public const SELECT = 'sui-dropdown';
    public const DATE = 'phlex-date-picker';
    public const LOOKUP = 'phlex-lookup';
    public const TABLE_CELL = 'sui-table-cell';

    /**
     * Props to be apply globally for each component supported by field type.
     * For example setting 'sui-dropdown' property globally.
     *  $componentProps = [Multiline::SELECT => ['floating' => true]].
     *
     * @var array
     */
    public $componentProps = [];

    /** @var array sui-table component props */
    public $tableProps = [];

    /** @var array[] Set Vue component to use per field type. */
    protected $fieldMapToComponent = [
        'default' => [
            'component' => self::INPUT,
            'componentProps' => [__CLASS__, 'getSuiInputProps'],
        ],
        'readonly' => [
            'component' => self::READ_ONLY,
            'componentProps' => [],
        ],
        'textarea' => [
            'component' => self::TEXT_AREA,
            'componentProps' => [],
        ],
        'select' => [
            'component' => self::SELECT,
            'componentProps' => [__CLASS__, 'getDropdownProps'],
        ],
        'date' => [
            'component' => self::DATE,
            'componentProps' => [__CLASS__, 'getDatePickerProps'],
        ],
        'lookup' => [
            'component' => self::LOOKUP,
            'componentProps' => [__CLASS__, 'getLookupProps'],
        ],
    ];

    /** @var bool Add row when tabbing out of last column in last row. */
    public $addOnTab = false;

    /** @var array The definition of each field used in every multiline row. */
    private $fieldDefs;

    /** @var JsCallback */
    private $renderCallback;

    /** @var \Closure Function to execute when field change or row is delete. */
    protected $onChangeFunction;

    /** @var array Set fields that will trigger onChange function. */
    protected $eventFields;

    /** @var array Collection of field errors. */
    private $rowErrors;

    /** @var array The fields names used in each row. */
    public $rowFields;

    /** @var array The data sent for each row. */
    public $rowData;

    /** @var int The max number of records (rows) that can be added to Multiline. 0 means no limit. */
    public $rowLimit = 0;

    /** @var int The maximum number of items for select type field. */
    public $itemLimit = 25;

    /** @var string Multiline's caption. */
    public $caption;

    /**
     * Container for component that need Props set based on their field value as Lookup component.
     * Set during fieldDefinition and apply during doRender() after getValue().
     * Must contains callable function and function will receive $model field and value as paremeter.
     *
     * @var array
     */
    private $valuePropsBinding = [];

    /**
     * A JsFunction to execute when Multiline add(+) button is clicked.
     * The function is execute after multiline component finish adding a row of fields.
     * The function also receive the row value as an array.
     * ex: $jsAfterAdd = new JsFunction(['value'],[new JsExpression('console.log(value)')]);.
     *
     * @var JsFunction
     */
    public $jsAfterAdd;

    /**
     * A JsFunction to execute when Multiline delete button is clicked.
     * The function is execute after multiline component finish deleting rows.
     * The function also receive the row value as an array.
     * ex: $jsAfterDelete = new JsFunction(['value'],[new JsExpression('console.log(value)')]);.
     *
     * @var JsFunction
     */
    public $jsAfterDelete;

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->initVueLookupCallback();

        if (!$this->multiLineTemplate) {
            $this->multiLineTemplate = new HtmlTemplate('<div id="{$_id}" class=""><phlex-multiline v-bind="initData"></phlex-multiline></div>');
        }

        $this->multiLine = View::addTo($this, ['template' => $this->multiLineTemplate]);

        $this->renderCallback = JsCallback::addTo($this);

        // load the data associated with this input and validate it.
        $this->form->onHook(Form::HOOK_LOAD_POST, function ($form, &$post) {
            $this->rowData = $this->typeCastLoadValues(Webpage::decodeJson($_POST[$this->elementId]));
            if ($this->rowData) {
                $this->rowErrors = $this->validate($this->rowData);
                if ($this->rowErrors) {
                    throw new Model\Field\ValidationException([$this->elementId => 'multiline error']);
                }
            }

            // remove __atml id from array field.
            if ($this->form->model->getField($this->elementId)->type === 'array') {
                $rows = [];
                foreach ($this->rowData as $cols) {
                    unset($cols['__phlex_multiline']);
                    $rows[] = $cols;
                }
                $post[$this->elementId] = json_encode($rows);
            }
        });

        // Change form error handling.
        $this->form->onHook(Form::HOOK_DISPLAY_ERROR, function ($form, $fieldName, $str) {
            // When errors are coming from this Multiline field, then notify Multiline component about them.
            // Otherwise use normal field error.
            if ($fieldName === $this->elementId) {
                // multiline.js component listen to 'multiline-rows-error' event.
                $jsError = [$this->jsEmitEvent($this->multiLine->elementName . '-multiline-rows-error', ['errors' => $this->rowErrors])];
            } else {
                $jsError = [$form->js()->form('add prompt', $fieldName, $str)];
            }

            return $jsError;
        });
    }

    public function setField(Model\Field $field)
    {
        $fieldType = $field->getValueType();

        if ($fieldType instanceof Model\Field\Type\ReferenceData\ContainedRecords) {
            $this->setModel($fieldType->getReference()->getTheirEntity());
            $this->caption = $this->getModel()->getCaption();
            $this->rowLimit = ($fieldType instanceof Model\Field\Type\ReferenceData\SingleRecord) ? 1 : 0;
        }

        return parent::setField($field);
    }

    /**
     * Typecast each loaded value.
     */
    protected function typeCastLoadValues(array $values): array
    {
        $dataRows = [];

        foreach ($values as $k => $row) {
            foreach ($row as $fieldName => $value) {
                if ($fieldName === '__phlex_multiline') {
                    $dataRows[$k][$fieldName] = $value;
                } else {
                    $dataRows[$k][$fieldName] = $this->getModel()->getField($fieldName)->decode($value, $this);
                }
            }
        }

        return $dataRows;
    }

    /**
     * Add a callback when fields are changed. You must supply array of fields
     * that will trigger the callback when changed.
     */
    public function onLineChange(\Closure $fx, array $fields): void
    {
        $this->eventFields = $fields;

        $this->onChangeFunction = $fx;
    }

    /**
     * Get Multiline initial field value. Value is based on model set and will
     * output data rows as json string value.
     */
    public function getValue(): string
    {
        if ($this->field->type === 'array') {
            $jsonValues = $this->field->encode($this->field->get(), $this);
        } else {
            // set data according to hasMany ref. or using model.
            $model = $this->getModel();
            $rows = [];
            foreach ($model as $row) {
                $cols = [];
                foreach ($this->rowFields as $fieldName) {
                    $field = $model->getField($fieldName);
                    $value = $field->encode($row->get($field->elementId), $this);
                    $cols[$fieldName] = $value;
                }
                $rows[] = $cols;
            }
            $jsonValues = json_encode($rows);
        }

        return $jsonValues;
    }

    /**
     * Validate each row and return errors if found.
     */
    public function validate(array $rows): array
    {
        $rowErrors = [];
        $model = $this->getModel();

        foreach ($rows as $cols) {
            $rowId = $this->getMlRowId($cols);
            foreach ($cols as $fieldName => $value) {
                if ($fieldName === '__phlex_multiline' || $fieldName === $model->primaryKey) {
                    continue;
                }

                try {
                    $field = $model->getField($fieldName);
                    // Save field value only if the field was editable
                    if (!$field->isReadOnly()) {
                        $model->createEntity()->set($fieldName, $value);
                    }
                } catch (\Phlex\Core\Exception $e) {
                    $rowErrors[$rowId][] = ['name' => $fieldName, 'msg' => $e->getMessage()];
                }
            }
            $rowErrors = $this->addModelValidateErrors($rowErrors, $rowId, $model);
        }

        return $rowErrors;
    }

    /**
     * Save rows.
     */
    public function saveRows(): self
    {
        $model = $this->getModel();

        // collects existing ids.
        $currentIds = array_column($model->export(), $model->primaryKey);

        foreach ($this->rowData as $row) {
            $entity = $model->tryLoad($row[$model->primaryKey] ?? null);
            foreach ($row as $key => $value) {
                if ($key === '__phlex_multiline') {
                    continue;
                }

                if (View\Field::isEditable($model->getField($key))) {
                    $entity->set($key, $value);
                }
            }
            $id = $entity->save()->getId();

            $k = array_search($id, $currentIds, true);
            if ($k !== false) {
                unset($currentIds[$k]);
            }
        }

        // Delete remaining currentIds
        foreach ($currentIds as $id) {
            $model->delete($id);
        }

        return $this;
    }

    /**
     * Check for model validate error.
     */
    protected function addModelValidateErrors(array $errors, string $rowId, Model $model): array
    {
        $e = $model->validate();
        if ($e) {
            foreach ($e as $field => $msg) {
                $errors[$rowId][] = ['field' => $field, 'msg' => $msg];
            }
        }

        return $errors;
    }

    /**
     * for javascript use - changing this method may brake JS functionality.
     *
     * Finds and returns Multiline row id.
     */
    private function getMlRowId(array $row): ?string
    {
        $rowId = null;
        foreach ($row as $col => $value) {
            if ($col === '__phlex_multiline') {
                $rowId = $value;

                break;
            }
        }

        return $rowId;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(Model $model, array $fieldNames = []): Model
    {
        $model = parent::setModel($model);

        if (!$fieldNames) {
            $fieldNames = array_keys($model->getActiveFields(Model::FIELD_FILTER_NOT_SYSTEM));
        }
        $this->rowFields = array_merge([$model->primaryKey], $fieldNames);

        foreach ($this->rowFields as $fieldName) {
            $this->fieldDefs[] = $this->getFieldDef($model->getField($fieldName));
        }

        return $model;
    }

    /**
     * Set hasMany reference model to use with multiline.
     *
     * Note: When using setReferenceModel you might need to set this corresponding field to never_persist to true.
     * Otherwise, form will try to save 'multiline' field value as an array when form is save.
     * $multiline = $form->addControl('multiline', [Multiline::class], ['never_persist' => true])
     */
    public function setReferenceModel(string $refModelName, Model $modelEntity = null, array $fieldNames = []): Model
    {
        if ($modelEntity === null) {
            if (!$this->form->model->isEntity()) {
                throw new Exception('Model entity is not set.');
            }

            $modelEntity = $this->form->model;
        }

        return $this->setModel($modelEntity->ref($refModelName), $fieldNames);
    }

    /**
     * Return field definition in order to properly render them in Multiline.
     *
     * Multiline uses Vue components in order to manage input type based on field type.
     * Component name and props are determine via the getComponentDefinition function.
     */
    public function getFieldDef(Model\Field $field): array
    {
        return [
            'name' => $field->elementId,
            'definition' => $this->getComponentDefinition($field),
            'cellProps' => $this->getSuiTableCellProps($field),
            'caption' => View\Field::getCaption($field),
            'default' => $field->default,
            'isExpr' => isset($field->expr),
            'isEditable' => View\Field::isEditable($field),
            'isHidden' => View\Field::isHidden($field),
            'isVisible' => View\Field::isVisible($field),
        ];
    }

    /**
     * Each field input, represent by a Vue component, is place within a table cell.
     * This table cell is also a Vue component that can use Props: Multiline::TABLE_CELL.
     *
     * Cell properties can be applied globally via $options[Multiline::TABLE_CELL] or per field
     * via  $field->setOption(Multiline::OPTION_PRESETS, Multiline::TABLE_CELL)
     */
    protected function getSuiTableCellProps(Model\Field $field): array
    {
        $props = [];

        if ($field->type === 'money' || $field->type === 'number' || $field->type === 'integer') {
            $props['text-align'] = 'right';
        }

        return array_merge($props, $this->componentProps[self::TABLE_CELL] ?? [], $field->getOption(self::OPTION_PRESETS, [])[self::TABLE_CELL] ?? []);
    }

    /**
     * Return props for input component.
     */
    protected static function getSuiInputProps(self $multiline, Model\Field $field): array
    {
        $props = $multiline->componentProps[self::INPUT] ?? [];

        $props['type'] = ($field->type === 'integer' || $field->type === 'float' || $field->type === 'money' || $field->type === 'number') ? 'number' : 'text';

        return array_merge($props, $field->getOption(self::OPTION_PRESETS, [])[self::INPUT] ?? []);
    }

    /**
     * Return props for phlex-date-picker component.
     */
    protected static function getDatePickerProps(self $multiline, Model\Field $field): array
    {
        $calendar = new Calendar();
        $props['config'] = $multiline->componentProps[self::DATE] ?? [];
        $format = $calendar->translateFormat($field->getCodec($multiline)->getFormat());
        $props['config']['dateFormat'] = $format;

        if (!$field->getValueType() instanceof Field\Type\Date) {
            $props['config']['enableTime'] = true;
            $props['config']['time_24hr'] = $calendar->use24hrTimeFormat($format);
            $props['config']['noCalendar'] = $field->getValueType() instanceof Field\Type\Time;
            $props['config']['enableSeconds'] = $calendar->useSeconds($format);
        }

        return $props;
    }

    /**
     * Return props for Dropdown components.
     */
    protected static function getDropdownProps(self $multiline, Model\Field $field): array
    {
        $props = array_merge(
            ['floating' => false, 'closeOnBlur' => true, 'selection' => true],
            $multiline->componentProps[self::SELECT] ?? []
        );

        $items = $multiline->getFieldItems($field, $multiline->itemLimit);
        foreach ($items as $value => $text) {
            $props['options'][] = ['key' => $value, 'text' => $text, 'value' => $value];
        }

        return $props;
    }

    /**
     * Set property for phlex-lookup component.
     */
    protected static function getLookupProps(self $multiline, Model\Field $field): array
    {
        // set any of sui-dropdown props via this property. Will be applied globally.
        $props['config'] = $multiline->componentProps[self::LOOKUP] ?? [];
        $items = $multiline->getFieldItems($field, 10);
        foreach ($items as $value => $text) {
            $props['config']['options'][] = ['key' => $value, 'text' => $text, 'value' => $value];
        }

        if ($field->getReference() !== null) {
            $props['config']['url'] = $multiline->dataCb->getUrl();
            $props['config']['reference'] = $field->elementId;
            $props['config']['search'] = true;
        }

        $props['config']['placeholder'] ??= 'Select ' . $field->getCaption();

        $multiline->valuePropsBinding[$field->elementId] = [__CLASS__, 'setLookupOptionValue'];

        return $props;
    }

    /**
     * Lookup Props set based on field value.
     */
    public static function setLookupOptionValue(self $multiline, Model\Field $field, string $value)
    {
        $reference = $field->getValueType()->getReference();
        $model = $reference->createTheirModel();
        $entity = $model->tryLoadBy($reference->getTheirKey(), $value);
        if ($entity->isLoaded()) {
            $option = [
                'key' => $value,
                'text' => $entity->getTitle(),
                'value' => $value,
            ];
            foreach ($multiline->fieldDefs as $key => $component) {
                if ($component['name'] === $field->elementId) {
                    $multiline->fieldDefs[$key]['definition']['componentProps']['optionalValue'] =
                        isset($multiline->fieldDefs[$key]['definition']['componentProps']['optionalValue'])
                        ? array_merge($multiline->fieldDefs[$key]['definition']['componentProps']['optionalValue'], [$option])
                        : [$option];
                }
            }
        }
    }

    /**
     * Return a component definition.
     * Component definition require at least a name and a props array.
     */
    protected function getComponentDefinition(Model\Field $field): array
    {
        if ($required = $field->getOption(self::OPTION_PRESETS, [])['component'] ?? null) {
            $component = $this->fieldMapToComponent[$required];
        } elseif (!$field->isEditable()) {
            $component = $this->fieldMapToComponent['readonly'];
        } elseif ($field->getValueType() instanceof Selectable) {
            $component = $this->fieldMapToComponent['select'];
        } elseif ($field->getValueType() instanceof DateTime) {
            $component = $this->fieldMapToComponent['date'];
        } elseif ($field->getValueType() instanceof Text) {
            $component = $this->fieldMapToComponent['textarea'];
        } elseif ($field->getValueType() instanceof ReferenceData) {
            $component = $this->fieldMapToComponent['lookup'];
        } else {
            $component = $this->fieldMapToComponent['default'];
        }

        $multiline = $this;

        $definition = array_map(function ($value) use ($multiline, $field) {
            return is_array($value) && is_callable($value) ? call_user_func($value, $multiline, $field) : $value;
        }, $component);

        return $definition;
    }

    /**
     * Return array of possible items set for a select or lookup field.
     */
    protected function getFieldItems(Model\Field $field, $limit = 10): array
    {
        $items = [];
        $fieldValueType = $field->getValueType();
        switch (get_class($fieldValueType)) {
            case Selectable::class:
                $items = array_chunk($fieldValueType->getValuesWithLabels(), $limit, true)[0];

                break;
            case Model\Field\Type\ReferenceData::class:
                $model = $fieldValueType->getReference()->createTheirModel();
                $theirKey = $fieldValueType->getReference()->getTheirKey();

                foreach ($model->setLimit($limit) as $entity) {
                    $items[$entity->get($theirKey)] = $entity->getTitle();
                }

                break;
            default:
                break;
        }

        return $items;
    }

    /**
     * Apply Props to component that require props based on field value.
     */
    protected function valuePropsBinding(string $values)
    {
        $fieldValues = Webpage::decodeJson($values);

        foreach ($fieldValues as $rows) {
            foreach ($rows as $fieldName => $value) {
                if (array_key_exists($fieldName, $this->valuePropsBinding)) {
                    call_user_func($this->valuePropsBinding[$fieldName], $this->getModel()->getField($fieldName), $value);
                }
            }
        }
    }

    protected function doRender(): void
    {
        if (!$this->getModel()) {
            throw new Exception('Multiline field needs to have it\'s model setup.');
        }

        $this->renderCallback->set(function () {
            $this->outputJson();
        });

        parent::doRender();

        $inputValue = $this->getValue();
        $this->valuePropsBinding($inputValue);

        $this->multiLine->vue(
            'phlex-multiline',
            [
                'data' => [
                    'formName' => $this->form->formElement->elementName,
                    'inputValue' => $inputValue,
                    'inputName' => $this->elementId,
                    'fields' => $this->fieldDefs,
                    'url' => $this->renderCallback->getJsUrl(),
                    'eventFields' => $this->eventFields,
                    'hasChangeCb' => $this->onChangeFunction ? true : false,
                    'tableProps' => $this->tableProps,
                    'rowLimit' => $this->rowLimit,
                    'caption' => $this->caption,
                    'afterAdd' => $this->jsAfterAdd,
                    'afterDelete' => $this->jsAfterDelete,
                    'addOnTab' => $this->addOnTab,
                ],
            ]
        );
    }

    /**
     * For javascript use - changing these methods may brake JS functionality.
     *
     * Render callback according to multi line action.
     * 'update-row' need special formatting.
     */
    private function outputJson(): void
    {
        $action = $_POST['__phlex_multiline_action'] ?? null;

        switch ($action) {
            case 'update-row':
                $model = $this->setDummyModelValue($this->getModel()->createEntity());
                $expressionValues = array_merge($this->getExpressionValues($model), $this->getCallbackValues($model));
                $this->getApp()->terminateJson(['success' => true, 'message' => 'Success', 'expressions' => $expressionValues]);
                // no break - expression above always terminate
            case 'on-change':
                $response = call_user_func($this->onChangeFunction, $this->typeCastLoadValues(Webpage::decodeJson($_POST['rows'])), $this->form);
                $this->renderCallback->terminateAjax($this->renderCallback->getAjaxec($response));

                break;
        }
    }

    /**
     * For javascript use - changing this method may brake JS functionality.
     *
     * Return values associated with callback field.
     */
    private function getCallbackValues(Model $model): array
    {
        $values = [];
        foreach ($this->fieldDefs as $def) {
            $fieldName = $def['name'];
            if ($fieldName === $model->primaryKey) {
                continue;
            }
            $field = $model->getField($fieldName);
            if ($field instanceof Model\Field\Callback) {
                $value = ($field->expr)($model);
                $values[$fieldName] = $field->encode($value, $this);
            }
        }

        return $values;
    }

    /**
     * For javascript use - changing this method may brake JS functionality.
     *
     * Looks inside the POST of the request and loads data into model.
     * Allow to Run expression base on post row value.
     */
    private function setDummyModelValue(Model $model): Model
    {
        $row = $_POST;

        foreach ($this->fieldDefs as $def) {
            $key = $def['name'];
            if ($key === $model->primaryKey) {
                continue;
            }

            $value = $row[$key] ?? null;
            if (View\Field::isEditable($model->getField($key))) {
                try {
                    $model->set($key, $value);
                } catch (Model\Field\ValidationException $e) {
                    // Bypass validation at this point.
                }
            }
        }

        return $model;
    }

    /**
     * For javascript use - changing this method may brake JS functionality.
     *
     * Return values associated to field expression.
     */
    private function getExpressionValues(Model $model): array
    {
        $dummyFields = [];
        $formatValues = [];

        foreach ($this->getExpressionFields($model) as $k => $field) {
            if (!is_callable($field->expr)) {
                $dummyFields[$k]['name'] = $field->elementId;
                $dummyFields[$k]['expr'] = $this->getDummyExpression($field, $model);
            }
        }

        if (!empty($dummyFields)) {
            $dummyModel = new Model($model->persistence, ['table' => $model->table]);
            foreach ($dummyFields as $field) {
                $dummyModel->addExpression($field['name'], ['expr' => $field['expr'], 'type' => $model->getField($field['name'])->type]);
            }
            $values = $dummyModel->tryLoadAny()->get();
            unset($values[$model->primaryKey]);

            $formatValues = $this->encodeRow($model, $values);

            //             foreach ($values as $key => $value) {
            //                 if ($value) {
            //                     $formatValues[$key] = $model->getField($key)->encode($value, $this);
            //                 }
            //             }
        }

        return $formatValues;
    }

    /**
     * For javascript use - changing this method may brake js functionality.
     *
     * Get all field expression in model, but only evaluate expression used in
     * rowFields.
     */
    private function getExpressionFields(Model $model): array
    {
        $fields = [];
        foreach ($model->getFields() as $field) {
            if (!$field instanceof Sql\Field\Expression || !in_array($field->elementId, $this->rowFields, true)) {
                continue;
            }

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * For javascript use - changing this method may brake JS functionality.
     *
     * Return expression where fields are replace with their current or default value.
     * Ex: total field expression = [qty] * [price] will return 4 * 100
     * where qty and price current value are 4 and 100 respectively.
     *
     * @return mixed
     */
    private function getDummyExpression(Sql\Field\Expression $exprField, $model)
    {
        $expr = $exprField->expr;
        $matches = [];

        preg_match_all('/\[[a-z0-9_]*\]|{[a-z0-9_]*}/i', $expr, $matches);

        foreach ($matches[0] as $match) {
            $fieldName = substr($match, 1, -1);
            $field = $model->getField($fieldName);
            if ($field instanceof Sql\Field\Expression) {
                $expr = str_replace($match, $this->getDummyExpression($field, $model), $expr);
            } else {
                $expr = str_replace($match, $this->getValueForExpression($exprField, $fieldName, $model), $expr);
            }
        }

        return $expr;
    }

    /**
     * For javascript use - changing this method may brake JS functionality.
     *
     * Return a value according to field used in expression and the expression type.
     * If field used in expression is null, the default value is returned.
     *
     * @return int|mixed|string
     */
    private function getValueForExpression(Model\Field $exprField, string $fieldName, Model $model)
    {
        switch ($exprField->type) {
            case 'money':
            case 'integer':
            case 'float':
                // Value is 0 or the field value.
                $value = (string) $model->get($fieldName) ?: 0;

                break;
            default:
                $value = '"' . $model->get($fieldName) . '"';
        }

        return $value;
    }
}
