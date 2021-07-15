<?php

declare(strict_types=1);

namespace Phlex\Ui\Form;

use Phlex\Ui\Exception;
use Phlex\Ui\Form;
use Phlex\Ui\View;
use Phlex\Data\Model;
use Phlex\Core\Factory;

/**
 * Provides generic functionality for a form control.
 */
class Control extends View implements Model\Field\CodecInterface
{
	protected static $fieldControls = [
			[Form\Control\Line::class],
			Model\Field\Type\Selectable::class => [Form\Control\Dropdown::class],
			Model\Field\Type\Boolean::class => [Form\Control\Checkbox::class],
			Model\Field\Type\Date::class => [Form\Control\Calendar::class, ['type' => 'date']],
			Model\Field\Type\DateTime::class => [Form\Control\Calendar::class, ['type' => 'datetime']],
			Model\Field\Type\Time::class => [Form\Control\Calendar::class, ['type' => 'time']],
			Model\Field\Type\String_::class => [Form\Control\Line::class],
			Model\Field\Type\Text::class => [Form\Control\Textarea::class],
	];
	
	public const OPTION_PLACEHOLDER = self::class . '@placeholder';
	
	public const OPTION_HINT = self::class . '@hint';
	
	public const OPTION_SEED = self::class . '@seed';
	
    /**
     * @var Form - to which this field belongs
     */
    public $form;

    /**
     * @var \Phlex\Data\Model\Field - points to model field
     */
    public $field;

    /** @var string control class */
    public $controlClass = '';

    /**
     * @var bool - Whether you need this field to be rendered wrap in a form layout or as his
     */
    public $layoutWrap = true;

    /** @var bool rendered or not input label in generic Form\Layout template. */
    public $renderLabel = true;

    public $width;

    /**
     * Caption is a text that must appear somewhere nearby the field. For a form with layout, this
     * will typically place caption above the input field, but for checkbox this may appear next to the
     * checkbox itself. If Form Layout does not have captions above the input field, then caption
     * will appear as a placeholder of the input fields and it may also appear as a tooltip.
     *
     * Caption is usually specified by a model.
     *
     * @var string
     */
    public $caption;

    /**
     * Placed as a pointing label below the field. This only works when Form\Control appears in a form. You can also
     * set this to object, such as \Phlex\Ui\Text otherwise HTML characters are escaped.
     *
     * @var string|\Phlex\Ui\View|array
     */
    public $hint;

    /**
     * Is input field disabled?
     * Disabled input fields are not editable and will not be submitted.
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * Is input field read only?
     * Read only input fields are not editable, but will be submitted.
     *
     * @var bool
     */
    public $readonly = false;

    /**
     * Initialization.
     */
    protected function doInitialize(): void
    {
        parent::doInitialize();

        if ($this->form && $this->field) {
            if (isset($this->form->controls[$this->field->short_name])) {
                throw (new Exception('Form already has a control with the same name'))
                    ->addMoreInfo('name', $this->field->short_name);
            }
            $this->form->controls[$this->field->short_name] = $this;
        }
    }
    
    public function setField(Model\Field $field)
    {
    	$this->field = $field;
    	
    	return $this;
    }

    /**
     * Sets the value of this field. If field is a part of the form and is associated with
     * the model, then the model's value will also be affected.
     *
     * @param mixed $value
     * @param mixed $junk
     *
     * @return $this
     */
    public function set($value = null, $junk = null)
    {
        if ($this->field) {
            $value = $this->getCodec($this->field)->decode($value);
            $this->field->set($value);

            return $this;
        }

        $this->content = $value;

        return $this;
    }

    /**
     * It only makes sense to have "name" property inside a field if
     * it was used inside a form.
     */
    protected function doRender(): void
    {
        if ($this->form) {
            $this->template->trySet('name', $this->short_name);
        }

        parent::doRender();
    }

    protected function renderTemplateToHtml(string $region = null): string
    {
        $output = parent::renderTemplateToHtml($region);

        /** @var Form|null $form */
        $form = $this->getClosestOwner($this, Form::class);

        return $form !== null ? $form->fixFormInRenderedHtml($output) : $output;
    }

    /**
     * Shorthand method for on('change') event.
     * Some input fields, like Calendar, could call this differently.
     *
     * If $expr is string or JsExpression, then it will execute it instantly.
     * If $expr is callback method, then it'll make additional request to webserver.
     *
     * Could be preferable to set useDefault to false. For example when
     * needing to clear form error or when form canLeave property is false.
     * Otherwise, change handler will not be propagate to all handlers.
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

        $this->on('change', '#' . $this->id . '_input', $expr, $default);
    }

    /**
     * Method similar to View::js() however will adjust selector
     * to target the "input" element.
     *
     * $field->jsInput(true)->val(123);
     *
     * @return \Phlex\Ui\Jquery
     */
    public function jsInput($when = null, $action = null)
    {
        return $this->js($when, $action, '#' . $this->id . '_input');
    }

    /**
     * @return string
     */
    public function getControlClass()
    {
        return $this->controlClass;
    }
    
    /**
     * Provided with a Agile Data Model Field, this method have to decide
     * and create instance of a View that will act as a form-control. It takes
     * various input and looks for hints as to which class to use:.
     *
     * 1. The $seed argument is evaluated
     * 2. $f->ui['form'] is evaluated if present
     * 3. $f->type is converted into seed and evaluated
     * 4. lastly, falling back to Line, Dropdown (based on $reference and $enum)
     *
     * @param array                   $seed  Defaults to pass to Factory::factory() when control object is initialized
     */
    public static function factory(Model\Field $field, array $seed = []): Form\Control
    {
    	$fallbackSeed = [Form\Control\Line::class];
    	
    	if ($field->type === 'array' && $field->getReference() !== null) {
    		$limit = ($field->getReference() instanceof Model\Reference\ContainsMany) ? 0 : 1;
    		$model = $field->getReference()->refModel();
    		$fallbackSeed = [Form\Control\Multiline::class, 'model' => $model, 'rowLimit' => $limit, 'caption' => $model->getCaption()];
    	} elseif ($field->type !== 'boolean') {
    		if ($field->getValueType() instanceof Model\Field\Type\Selectable) {
    			$fallbackSeed = [Form\Control\Dropdown::class, 'values' => $field->getValueType()->values];
    		} elseif ($field->getReference() !== null) {
    			$fallbackSeed = [Form\Control\Lookup::class, 'model' => $field->getReference()->refModel()];
    		}
    	}
    	
    	if ($hint = $field->getOption(self::OPTION_HINT)) {
    		$fallbackSeed['hint'] = $hint;
    	}
    	
    	if ($placeholder = $field->getOption(self::OPTION_PLACEHOLDER)) {
    		$fallbackSeed['placeholder'] = $placeholder;
    	}
    	
    	$seed = Factory::mergeSeeds(
    			$seed,
    			$field->getOption(self::OPTION_SEED),
    			$this->typeToControl[$field->type] ?? null,
    			$fallbackSeed
    	);

    	return Factory::factory($seed, [
    			'field' => $field,
    			'short_name' => $field->short_name,
    	]);
    }
    
    public function encode($value)
    {
    	return $value;
    }
    
    public function decode($value)
    {
    	return $value;
    }
    
    public function getField(): ?Model\Field
    {
    	return $this->field;
    }
    
    public function getValueType(): ?Model\Field\Type
    {
    	return $this->field->getValueType();
    }
}
