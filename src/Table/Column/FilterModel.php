<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Core\SessionTrait;
use Phlex\Data\Model;
use Phlex\Data\Persistence;
use Phlex\Ui\Form;
use Phlex\Ui\View;

/**
 * Implement a generic filter model for filtering column data.
 */
class FilterModel extends Model
{
    use SessionTrait;
    use View\Field\TypeRegistryTrait;

    public const OPTION_TYPE = self::class . '@type';

    /**
     * The operator for defining a condition on a field.
     *
     * @var Model\Field
     */
    public $op;

    /**
     * The value for defining a condition on a field.
     *
     * @var Model\Field
     */
    public $value;

    /**
     * Determines if this field shouldn't have a value field, and use only op field.
     *
     * @var bool
     */
    public $noValueField = false;

    /**
     * The field where this filter need to query data.
     *
     * @var Model\Field
     */
    public $lookupField;

    protected static $fieldTypesRegistry = [
        FilterModel\TypeString::class,
        Model\Field\Type\Boolean::class => FilterModel\TypeBoolean::class,
        Model\Field\Type\Float_::class => FilterModel\TypeNumber::class,
        Model\Field\Type\Integer::class => FilterModel\TypeNumber::class,
        Model\Field\Type\Money::class => FilterModel\TypeNumber::class,
        Model\Field\Type\DateTime::class => FilterModel\TypeDateTime::class,
        Model\Field\Type\Date::class => FilterModel\TypeDate::class,
        Model\Field\Type\Time::class => FilterModel\TypeTime::class,
        Model\Field\Type\Selectable::class => FilterModel\TypeEnum::class,
        // Model\Field\Type\ReferenceData::class => 'lookup',
    ];

    /**
     * Factory method that will return a FilterModel Type class.
     */
    public static function factoryType(Model\Field $field): self
    {
        $class = $field->getValueType()->resolveFromRegistry(self::$fieldTypes);

        // You can set your own filter model condition by adding the FilterModel::OPTION_TYPE in the field options
        if ($customType = $field->getOption(self::OPTION_TYPE)) {
            if ($customType instanceof self) {
                return $customType;
            }
            $class = $customType;
        }

        return new $class(new Persistence\Array_(), ['lookupField' => $field]);
    }

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->op = $this->addField('op', [
            'type' => 'list',
            'options' => [
                Form\Control::OPTION_SEED => ['caption' => ''],
            ],
        ]);

        if (!$this->noValueField) {
            $this->value = $this->addField('value', [
                'options' => [
                    Form\Control::OPTION_SEED => ['caption' => ''],
                ],
            ]);
        }

        $this->afterInit();
    }

    /**
     * Perform further initialisation.
     */
    public function afterInit()
    {
        $this->addField('name', ['default' => $this->lookupField->elementId, 'system' => true]);

        // create a name for our filter model to save as session data.
        $this->elementName = 'filter_model_' . $this->lookupField->elementId;

        if ($_GET['phlex_clear_filter'] ?? false) {
            $this->forget();
        }

        // Add hook in order to persist data in session.
        $this->onHook(self::HOOK_AFTER_SAVE, function ($model) {
            $this->memorize('data', $model->get());
        });
    }

    /**
     * Recall filter model data.
     */
    public function recallData(): array
    {
        return $this->recall('data', []);
    }

    /**
     * Method that will set conditions on a model base on $op and $value value.
     * Each FilterModel\TypeModel should override this method.
     *
     * @return mixed
     */
    public function setConditionForModel($model)
    {
        return $model;
    }

    /**
     * Method that will set Field display condition in a form.
     * If form filter need to have a field display at certain condition, then
     * override this method in your FilterModel\TypeModel.
     */
    public function getFormDisplayRules() {}

    /**
     * Check if this model is using session or not.
     */
    public function clearData(): void
    {
        $this->forget();
    }
}
