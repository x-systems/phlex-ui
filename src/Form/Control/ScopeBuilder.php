<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Control;

use Phlex\Data\Model;
use Phlex\Data\Model\Field\Reference;
use Phlex\Data\Model\Field\Type;
use Phlex\Data\Model\Scope;
use Phlex\Data\Model\Scope\Condition;
use Phlex\Ui\Exception;
use Phlex\Ui\Form;
use Phlex\Ui\Form\Control;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

class ScopeBuilder extends Control
{
    use View\Field\TypeRegistryTrait;
    use VueLookupTrait;

    public const OPTION_PRESETS = self::class . '@presets';

    /** @var bool Do not render label for this input. */
    public $renderLabel = false;

    /**
     * General or field type specific options.
     *
     * @var array
     */
    public $options = [
        'enum' => [
            'limit' => 250,
        ],
        'debug' => false, // displays query output live on the page if set to true
    ];
    /**
     * Max depth of nested conditions allowed.
     * Corresponds to VueQueryBulder maxDepth.
     * Maximum support by js component is 10.
     *
     * @var int
     */
    public $maxDepth = 5;

    /**
     * Fields to use for creating the rules.
     *
     * @var array
     */
    public $fields = [];

    /**
     * The template needed for the scopebuilder view.
     *
     * @var HtmlTemplate
     */
    public $scopeBuilderTemplate;

    /**
     * List of delimiters for auto-detection in order of priority.
     *
     * @var array
     */
    public static $listDelimiters = [';', ','];

    /**
     * The date, time or datetime options:
     *     Any of flatpickr options;
     *    'flatpickr' => [].
     *
     *     When true, will init date, time or datetime to current.
     *    'useDefault'
     *
     * @var array
     */
    public $phlexDateOptions = [
        'useDefault' => false,
        'flatpickr' => [],
    ];

    /**
     * phlex-lookup and semantic-ui dropdown options.
     */
    public $phlexLookupOptions = [
        'ui' => 'small basic button',
    ];

    /**
     * The scopebuilder View. Assigned in doInitialize().
     *
     * @var View
     */
    protected $scopeBuilderView;

    /**
     * Definition of VueQueryBuilder rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Set Labels for Vue-Query-Builder
     * see https://dabernathy89.github.io/vue-query-builder/configuration.html#labels.
     *
     * @var array
     */
    public $labels = [];

    /**
     * Default VueQueryBuilder query.
     *
     * @var array
     */
    protected $query = [];

    protected const OPERATOR_TEXT_EQUALS = 'equals';
    protected const OPERATOR_TEXT_DOESNOT_EQUAL = 'does not equal';
    protected const OPERATOR_TEXT_GREATER = 'is alphabetically after';
    protected const OPERATOR_TEXT_GREATER_EQUAL = 'is alphabetically equal or after';
    protected const OPERATOR_TEXT_LESS = 'is alphabetically before';
    protected const OPERATOR_TEXT_LESS_EQUAL = 'is alphabetically equal or before';
    protected const OPERATOR_TEXT_CONTAINS = 'contains';
    protected const OPERATOR_TEXT_DOESNOT_CONTAIN = 'does not contain';
    protected const OPERATOR_TEXT_BEGINS_WITH = 'begins with';
    protected const OPERATOR_TEXT_DOESNOT_BEGIN_WITH = 'does not begin with';
    protected const OPERATOR_TEXT_ENDS_WITH = 'ends with';
    protected const OPERATOR_TEXT_DOESNOT_END_WITH = 'does not end with';
    protected const OPERATOR_TEXT_MATCHES_REGEX = 'matches regular expression';
    protected const OPERATOR_TEXT_DOESNOT_MATCH_REGEX = 'does not match regular expression';
    protected const OPERATOR_SIGN_EQUALS = '=';
    protected const OPERATOR_SIGN_DOESNOT_EQUAL = '<>';
    protected const OPERATOR_SIGN_GREATER = '>';
    protected const OPERATOR_SIGN_GREATER_EQUAL = '>=';
    protected const OPERATOR_SIGN_LESS = '<';
    protected const OPERATOR_SIGN_LESS_EQUAL = '<=';
    protected const OPERATOR_TIME_EQUALS = 'is on';
    protected const OPERATOR_TIME_DOESNOT_EQUAL = 'is not on';
    protected const OPERATOR_TIME_GREATER = 'is after';
    protected const OPERATOR_TIME_GREATER_EQUAL = 'is on or after';
    protected const OPERATOR_TIME_LESS = 'is before';
    protected const OPERATOR_TIME_LESS_EQUAL = 'is on or before';
    protected const OPERATOR_EQUALS = 'equals';
    protected const OPERATOR_DOESNOT_EQUAL = 'does not equal';
    protected const OPERATOR_IN = 'is in';
    protected const OPERATOR_NOT_IN = 'is not in';
    protected const OPERATOR_EMPTY = 'is empty';
    protected const OPERATOR_NOT_EMPTY = 'is not empty';

    protected const DATE_OPERATORS = [
        self::OPERATOR_TIME_EQUALS,
        self::OPERATOR_TIME_DOESNOT_EQUAL,
        self::OPERATOR_TIME_GREATER,
        self::OPERATOR_TIME_GREATER_EQUAL,
        self::OPERATOR_TIME_LESS,
        self::OPERATOR_TIME_LESS_EQUAL,
        self::OPERATOR_EMPTY,
        self::OPERATOR_NOT_EMPTY,
    ];

    protected const ENUM_OPERATORS = [
        self::OPERATOR_EQUALS,
        self::OPERATOR_DOESNOT_EQUAL,
        self::OPERATOR_EMPTY,
        self::OPERATOR_NOT_EMPTY,
    ];

    protected const DATE_OPERATORS_MAP = [
        self::OPERATOR_TIME_EQUALS => Condition::OPERATOR_EQUALS,
        self::OPERATOR_TIME_DOESNOT_EQUAL => Condition::OPERATOR_DOESNOT_EQUAL,
        self::OPERATOR_TIME_GREATER => Condition::OPERATOR_GREATER,
        self::OPERATOR_TIME_GREATER_EQUAL => Condition::OPERATOR_GREATER_EQUAL,
        self::OPERATOR_TIME_LESS => Condition::OPERATOR_LESS,
        self::OPERATOR_TIME_LESS_EQUAL => Condition::OPERATOR_LESS_EQUAL,
    ];

    /**
     * VueQueryBulder => Condition map of operators.
     *
     * Operator map supports also inputType specific operators in sub maps
     *
     * @var array
     */
    protected static $operatorsMap = [
        'number' => [
            self::OPERATOR_SIGN_EQUALS => Condition::OPERATOR_EQUALS,
            self::OPERATOR_SIGN_DOESNOT_EQUAL => Condition::OPERATOR_DOESNOT_EQUAL,
            self::OPERATOR_SIGN_GREATER => Condition::OPERATOR_GREATER,
            self::OPERATOR_SIGN_GREATER_EQUAL => Condition::OPERATOR_GREATER_EQUAL,
            self::OPERATOR_SIGN_LESS => Condition::OPERATOR_LESS,
            self::OPERATOR_SIGN_LESS_EQUAL => Condition::OPERATOR_LESS_EQUAL,
        ],
        'date' => self::DATE_OPERATORS_MAP,
        'time' => self::DATE_OPERATORS_MAP,
        'datetime' => self::DATE_OPERATORS_MAP,
        'text' => [
            self::OPERATOR_TEXT_EQUALS => Condition::OPERATOR_EQUALS,
            self::OPERATOR_TEXT_DOESNOT_EQUAL => Condition::OPERATOR_DOESNOT_EQUAL,
            self::OPERATOR_TEXT_GREATER => Condition::OPERATOR_GREATER,
            self::OPERATOR_TEXT_GREATER_EQUAL => Condition::OPERATOR_GREATER_EQUAL,
            self::OPERATOR_TEXT_LESS => Condition::OPERATOR_LESS,
            self::OPERATOR_TEXT_LESS_EQUAL => Condition::OPERATOR_LESS_EQUAL,
            self::OPERATOR_TEXT_CONTAINS => Condition::OPERATOR_LIKE,
            self::OPERATOR_TEXT_DOESNOT_CONTAIN => Condition::OPERATOR_NOT_LIKE,
            self::OPERATOR_TEXT_BEGINS_WITH => Condition::OPERATOR_LIKE,
            self::OPERATOR_TEXT_DOESNOT_BEGIN_WITH => Condition::OPERATOR_NOT_LIKE,
            self::OPERATOR_TEXT_ENDS_WITH => Condition::OPERATOR_LIKE,
            self::OPERATOR_TEXT_DOESNOT_END_WITH => Condition::OPERATOR_NOT_LIKE,
            self::OPERATOR_IN => Condition::OPERATOR_IN,
            self::OPERATOR_NOT_IN => Condition::OPERATOR_NOT_IN,
            self::OPERATOR_TEXT_MATCHES_REGEX => Condition::OPERATOR_REGEXP,
            self::OPERATOR_TEXT_DOESNOT_MATCH_REGEX => Condition::OPERATOR_NOT_REGEXP,
            self::OPERATOR_EMPTY => Condition::OPERATOR_EQUALS,
            self::OPERATOR_NOT_EMPTY => Condition::OPERATOR_DOESNOT_EQUAL,
        ],
        'select' => [
            self::OPERATOR_EQUALS => Condition::OPERATOR_EQUALS,
            self::OPERATOR_DOESNOT_EQUAL => Condition::OPERATOR_DOESNOT_EQUAL,
        ],
        'lookup' => [
            self::OPERATOR_EQUALS => Condition::OPERATOR_EQUALS,
            self::OPERATOR_DOESNOT_EQUAL => Condition::OPERATOR_DOESNOT_EQUAL,
        ],
    ];

    /**
     * Definition of rule types.
     *
     * @var array
     */
    protected static $ruleTypes = [
        'default' => 'text',
        'text' => [
            'type' => 'text',
            'operators' => [
                self::OPERATOR_TEXT_EQUALS,
                self::OPERATOR_TEXT_DOESNOT_EQUAL,
                self::OPERATOR_TEXT_GREATER,
                self::OPERATOR_TEXT_GREATER_EQUAL,
                self::OPERATOR_TEXT_LESS,
                self::OPERATOR_TEXT_LESS_EQUAL,
                self::OPERATOR_TEXT_CONTAINS,
                self::OPERATOR_TEXT_DOESNOT_CONTAIN,
                self::OPERATOR_TEXT_BEGINS_WITH,
                self::OPERATOR_TEXT_DOESNOT_BEGIN_WITH,
                self::OPERATOR_TEXT_ENDS_WITH,
                self::OPERATOR_TEXT_DOESNOT_END_WITH,
                self::OPERATOR_TEXT_MATCHES_REGEX,
                self::OPERATOR_TEXT_DOESNOT_MATCH_REGEX,
                self::OPERATOR_IN,
                self::OPERATOR_NOT_IN,
                self::OPERATOR_EMPTY,
                self::OPERATOR_NOT_EMPTY,
            ],
        ],
        'lookup' => [
            'type' => 'custom-component',
            'inputType' => 'lookup',
            'component' => 'phlex-lookup',
            'operators' => self::ENUM_OPERATORS,
            'componentProps' => [__CLASS__, 'getLookupProps'],
        ],
        'enum' => [
            'type' => 'select',
            'inputType' => 'select',
            'operators' => self::ENUM_OPERATORS,
            'choices' => [__CLASS__, 'getChoices'],
        ],
        'numeric' => [
            'type' => 'text',
            'inputType' => 'number',
            'operators' => [
                self::OPERATOR_SIGN_EQUALS,
                self::OPERATOR_SIGN_DOESNOT_EQUAL,
                self::OPERATOR_SIGN_GREATER,
                self::OPERATOR_SIGN_GREATER_EQUAL,
                self::OPERATOR_SIGN_LESS,
                self::OPERATOR_SIGN_LESS_EQUAL,
                self::OPERATOR_EMPTY,
                self::OPERATOR_NOT_EMPTY,
            ],
        ],
        'boolean' => [
            'type' => 'radio',
            'operators' => [],
            'choices' => [
                ['label' => 'Yes', 'value' => '1'],
                ['label' => 'No', 'value' => '0'],
            ],
        ],
        'date' => [
            'type' => 'custom-component',
            'component' => 'phlex-date-picker',
            'inputType' => 'date',
            'operators' => self::DATE_OPERATORS,
            'componentProps' => [__CLASS__, 'getDatePickerProps'],
        ],
        'datetime' => [
            'type' => 'custom-component',
            'component' => 'phlex-date-picker',
            'inputType' => 'datetime',
            'operators' => self::DATE_OPERATORS,
            'componentProps' => [__CLASS__, 'getDatePickerProps'],
        ],
        'time' => [
            'type' => 'custom-component',
            'component' => 'phlex-date-picker',
            'inputType' => 'time',
            'operators' => self::DATE_OPERATORS,
            'componentProps' => [__CLASS__, 'getDatePickerProps'],
        ],
        'integer' => 'numeric',
        'float' => 'numeric',
        'money' => 'numeric',
        'checkbox' => 'boolean',
    ];

    protected static $fieldTypesRegistry = [
        'default',
        Type\Boolean::class => 'boolean',
        Type\Float_::class => 'float',
        Type\Integer::class => 'integer',
        Type\Money::class => 'money',
        Type\DateTime::class => 'datetime',
        Type\Date::class => 'date',
        Type\Time::class => 'time',
        Type\Selectable::class => 'enum',
        Type\ReferenceData::class => 'lookup',
    ];

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->initVueLookupCallback();

        $this->buildQuery();

        if (!$this->scopeBuilderTemplate) {
            $this->scopeBuilderTemplate = new HtmlTemplate('<div id="{$_id}" class="ui"><phlex-query-builder v-bind="initData"></phlex-query-builder></div>');
        }

        $this->scopeBuilderView = View::addTo($this, ['template' => $this->scopeBuilderTemplate]);

        if ($this->form) {
            $this->form->onHook(Form::HOOK_LOAD_POST, function ($form, &$post) {
                $key = $this->field->elementId;
                $post[$key] = $this->queryToScope(Webpage::decodeJson($post[$key] ?? '{}'));
            });
        }
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Build query from model scope.
     */
    protected function buildQuery()
    {
        $this->fields = $this->fields ?: array_keys($this->model->getFields());

        foreach ($this->fields as $fieldName) {
            $field = $this->model->getField($fieldName);

            $this->addFieldRule($field);

            $this->addReferenceRules($field);
        }

        // build a ruleId => inputType map
        // this is used when selecting proper operator for the inputType (see self::$operatorsMap)
        $inputsMap = array_column($this->rules, 'inputType', 'id');

        if ($this->field && $this->field->get() !== null) {
            $scope = $this->field->get();
        } else {
            $scope = $this->model->scope();
        }

        $this->query = $this->scopeToQuery($scope, $inputsMap)['query'];
    }

    /**
     * Add the field rules to use in VueQueryBuilder.
     */
    protected function addFieldRule(Model\Field $field): self
    {
        $ruleType = $field->getValueType()->resolveFromRegistry(self::$fieldTypesRegistry);

        $this->rules[] = $this->getRule($ruleType, array_merge([
            'id' => $field->elementId,
            'label' => $field->getCaption(),
            'options' => $this->options[strtolower((string) $ruleType)] ?? [],
        ], $field->getOption(self::OPTION_PRESETS, [])), $field);

        return $this;
    }

    /**
     * Set property for phlex-lookup component.
     */
    protected function getLookupProps(Model\Field $field): array
    {
        // set any of sui-dropdown props via this property. Will be applied globally.
        $props = $this->phlexLookupOptions;
        $items = $this->getFieldItems($field, 10);
        foreach ($items as $value => $text) {
            $props['options'][] = ['key' => $value, 'text' => $text, 'value' => $value];
        }

        if ($field->getValueType() instanceof Type\ReferenceData) {
            $props['url'] = $this->dataCb->getUrl();
            $props['reference'] = $field->elementId;
            $props['search'] = true;
        }

        $props['placeholder'] ??= 'Select ' . $field->getCaption();

        return $props;
    }

    /**
     * Set property for phlex-date-picker component.
     */
    protected function getDatePickerProps(Model\Field $field): array
    {
        $calendar = new Calendar();
        $props = $this->phlexDateOptions['flatpickr'] ?? [];

        $format = $calendar->translateFormat($this->getCodec($field)->getFormat());
        $props['altFormat'] = $format;
        $props['dateFormat'] = 'Y-m-d';
        $props['altInput'] = true;

        if (!$field->getValueType() instanceof Type\Date) {
            $props['enableTime'] = true;
            $props['time_24hr'] = $calendar->use24hrTimeFormat($format);
            $props['noCalendar'] = $field->getValueType() instanceof Type\Time;
            $props['enableSeconds'] = $calendar->useSeconds($format);
            $props['dateFormat'] = $field->getValueType() instanceof Type\Time ? 'H:i:S' : 'Y-m-d H:i:S';
        }

        $props['useDefault'] = $this->phlexDateOptions['useDefault'];

        return $props;
    }

    /**
     * Add rules on the referenced model fields.
     */
    protected function addReferenceRules(Model\Field $field): self
    {
        if ($field instanceof Reference) {
            // add the number of records rule
            $this->rules[] = $this->getRule('numeric', [
                'id' => $field->getKey() . '/#',
                'label' => $field->getCaption() . ' number of records ',
            ]);

            $theirModel = $field->createTheirModel();

            // add rules on all fields of the referenced model
            foreach ($theirModel->getFields() as $theirField) {
                $theirField->setOption(self::OPTION_PRESETS, [
                    'id' => $field->getKey() . '/' . $theirField->elementId,
                    'label' => $field->getCaption() . ' is set to record where ' . $theirField->getCaption(),
                ]);

                $this->addFieldRule($theirField);
            }
        }

        return $this;
    }

    protected function getRule($type, array $defaults = [], Model\Field $field = null): array
    {
        $rule = self::$ruleTypes[strtolower((string) $type)] ?? self::$ruleTypes['default'];

        // when $rule is an alias
        if (is_string($rule)) {
            return $this->getRule($rule, $defaults, $field);
        }

        $options = $defaults['options'] ?? [];

        // 'options' is phlex specific so not necessary to pass it to VueQueryBuilder
        unset($defaults['options']);

        // when $rule is callable
        if (is_callable($rule)) {
            $rule = call_user_func($rule, $field, $options);
        }

        // map all values for callables and merge with defaults
        return array_merge(array_map(function ($value) use ($field, $options) {
            return is_array($value) && is_callable($value) ? call_user_func($value, $field, $options) : $value;
        }, $rule), $defaults);
    }

    /**
     * Return an array of items id and name for a field.
     * Return field enum, values or reference values.
     */
    protected function getFieldItems(Model\Field $field, int $limit = 250): array
    {
        $items = [];
        $fieldValueType = $field->getValueType();
        switch (get_class($fieldValueType)) {
            case Type\Selectable::class:
                $items = array_chunk($fieldValueType->getValuesWithLabels(), $limit, true)[0];

                break;
            case Type\ReferenceData::class:
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
     * Returns the choices array for Select field rule.
     */
    protected function getChoices(Model\Field $field, $options = []): array
    {
        $choices = $this->getFieldItems($field, $options['limit'] ?? 250);

        $ret = [
            ['label' => '[empty]', 'value' => null],
        ];
        foreach ($choices as $value => $label) {
            $ret[] = ['label' => $label, 'value' => $value];
        }

        return $ret;
    }

    protected function doRender(): void
    {
        parent::doRender();

        $this->scopeBuilderView->vue(
            'phlex-query-builder',
            [
                'data' => [
                    'rules' => $this->rules,
                    'maxDepth' => $this->maxDepth,
                    'query' => $this->query,
                    'name' => $this->elementId,
                    'labels' => $this->labels ?? null,
                    'form' => $this->form->formElement->elementName,
                    'debug' => $this->options['debug'] ?? false,
                ],
            ]
        );
    }

    /**
     * Converts an VueQueryBuilder query array to Condition or Scope.
     */
    public static function queryToScope(array $query): Scope\AbstractScope
    {
        $type = $query['type'] ?? 'query-builder-group';
        $query = $query['query'] ?? $query;

        switch ($type) {
            case 'query-builder-group':
                $components = array_map([static::class, 'queryToScope'], (array) $query['children']);
                $scope = new Scope($components, $query['logicalOperator']);

                break;
            case 'query-builder-rule':
                $scope = self::queryToCondition($query);

                break;
            default:
                $scope = Scope::createAnd();

                break;
        }

        return $scope;
    }

    /**
     * Converts an VueQueryBuilder rule array to Condition or Scope.
     */
    public static function queryToCondition(array $query): Condition
    {
        $key = $query['rule'] ?? null;
        $operator = (string) ($query['operator'] ?? null);
        $value = $query['value'] ?? null;

        switch ($operator) {
            case self::OPERATOR_EMPTY:
            case self::OPERATOR_NOT_EMPTY:
                $value = null;

                break;
            case self::OPERATOR_TEXT_BEGINS_WITH:
            case self::OPERATOR_TEXT_DOESNOT_BEGIN_WITH:
                $value = $value . '%';

                break;
            case self::OPERATOR_TEXT_ENDS_WITH:
            case self::OPERATOR_TEXT_DOESNOT_END_WITH:
                $value = '%' . $value;

                break;
            case self::OPERATOR_TEXT_CONTAINS:
            case self::OPERATOR_TEXT_DOESNOT_CONTAIN:
                $value = '%' . $value . '%';

                break;
            case self::OPERATOR_IN:
            case self::OPERATOR_NOT_IN:
                $value = explode(self::detectDelimiter($value), (string) $value);

                break;
            default:
                break;
        }

        $operatorsMap = array_merge(...array_values(self::$operatorsMap));

        $operator = $operator ? ($operatorsMap[strtolower($operator)] ?? '=') : null;

        return new Condition($key, $operator, $value);
    }

    /**
     * Converts Scope or Condition to VueQueryBuilder query array.
     */
    public static function scopeToQuery(Scope\AbstractScope $scope, $inputsMap = []): array
    {
        $query = [];
        if ($scope instanceof Condition) {
            $query = [
                'type' => 'query-builder-rule',
                'query' => self::conditionToQuery($scope, $inputsMap),
            ];
        }

        if ($scope instanceof Scope) {
            $children = [];
            foreach ($scope->getNestedConditions() as $nestedCondition) {
                $children[] = self::scopeToQuery($nestedCondition, $inputsMap);
            }

            $query = [
                'type' => 'query-builder-group',
                'query' => [
                    'logicalOperator' => $scope->getJunction(),
                    'children' => $children,
                ],
            ];
        }

        return $query;
    }

    /**
     * Converts a Condition to VueQueryBuilder query array.
     */
    public static function conditionToQuery(Condition $condition, $inputsMap = []): array
    {
        if (is_string($condition->key)) {
            $rule = $condition->key;
        } elseif ($condition->key instanceof Field) {
            $rule = $condition->key->elementId;
        } else {
            throw new Exception('Unsupported scope key: ' . gettype($condition->key));
        }

        $operator = $condition->operator;
        $value = $condition->value;

        if (in_array($operator, [Condition::OPERATOR_LIKE, Condition::OPERATOR_NOT_LIKE], true)) {
            // no %
            $match = 0;
            // % at the beginning
            $match += substr($value, 0, 1) === '%' ? 1 : 0;
            // % at the end
            $match += substr($value, -1) === '%' ? 2 : 0;

            $map = [
                Condition::OPERATOR_LIKE => [
                    self::OPERATOR_TEXT_EQUALS,
                    self::OPERATOR_TEXT_BEGINS_WITH,
                    self::OPERATOR_TEXT_ENDS_WITH,
                    self::OPERATOR_TEXT_CONTAINS,
                ],
                Condition::OPERATOR_NOT_LIKE => [
                    self::OPERATOR_TEXT_DOESNOT_EQUAL,
                    self::OPERATOR_TEXT_DOESNOT_BEGIN_WITH,
                    self::OPERATOR_TEXT_DOESNOT_END_WITH,
                    self::OPERATOR_TEXT_DOESNOT_CONTAIN,
                ],
            ];

            $operator = $map[strtoupper($operator)][$match];

            $value = trim($value, '%');
        } else {
            if (is_array($value)) {
                $map = [
                    Condition::OPERATOR_EQUALS => Condition::OPERATOR_IN,
                    Condition::OPERATOR_DOESNOT_EQUAL => Condition::OPERATOR_NOT_IN,
                ];
                $value = implode(',', $value);
                $operator = $map[$operator] ?? Condition::OPERATOR_NOT_IN;
            }

            $inputType = $inputsMap[$rule] ?? 'text';

            $operatorsMap = array_merge(self::$operatorsMap[$inputType] ?? [], self::$operatorsMap['text']);

            $operator = array_search(strtoupper($operator), $operatorsMap, true) ?: self::OPERATOR_EQUALS;
        }

        return [
            'rule' => $rule,
            'operator' => $operator,
            'value' => $value,
            'option' => self::getOption($inputType, $value, $condition),
        ];
    }

    /**
     * return extra value option associate with certain inputType or null otherwise.
     */
    protected static function getOption(string $type, string $value, Condition $condition): ?array
    {
        $option = null;
        switch ($type) {
            case 'lookup':
                $field = $condition->getModel()->getField($condition->key);
                $fieldValueType = $field->getValueType();

                if ($fieldValueType instanceof Type\ReferenceData) {
                    $entity = $field->createTheirModel()->tryLoadBy($field->getTheirKey(), $value);
                    if ($entity->isLoaded()) {
                        $option = [
                            'key' => $value,
                            'text' => $entity->getTitle(),
                            'value' => $value,
                        ];
                    }
                }

                break;
        }

        return $option;
    }

    /**
     * Auto-detects a string delimiter based on list of predefined values in ScopeBuilder::$listDelimiters in order of priority.
     *
     * @param string $value
     *
     * @return string
     */
    public static function detectDelimiter($value)
    {
        $matches = [];
        foreach (self::$listDelimiters as $delimiter) {
            $matches[$delimiter] = substr_count((string) $value, $delimiter);
        }

        $max = array_keys($matches, max($matches), true);

        return reset($max) ?: reset(self::$listDelimiters);
    }
}
