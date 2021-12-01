<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model;
use Phlex\Ui\Table;

/**
 * Class KeyValue.
 *
 * if field have values without a relation
 * like a status or a coded state of a process
 * Ex :
 * Machine state :
 *  0 => off
 *  1 => powerup
 *  2 => on
 *  3 => resetting
 *  4 => error
 *
 * we don't need a table to define this, cause are defined in project
 *
 * using KeyValue Column you can show this values without using DB Relations
 * need to be defined in field like this :
 *
 * $this->addField('course_payment_status', [
 *  	'caption' => __('Payment Status'),
 *  	'default' => 0,
 *  	'type' => [
 *  		'enum',
 *  		'values' => [
 *      		0 => __('not invoiceable'),
 *      		1 => __('ready to invoice'),
 *      		2 => __('invoiced'),
 *      		3 => __('paid'),
 *  		],
 *  	],
 *  	'options' => [
 *      	Form\Control::OPTION_SEED => [\Phlex\Ui\Form\Control\Dropdown::class],
 *      	Table\Column::OPTION_SEED => [\Phlex\Ui\Table\Column\KeyValue::class],
 *  	],
 * ]);
 */
class KeyValue extends Table\Column
{
    public $values = [];

    /**
     * @param Model\Field|null $field
     *
     * @return array
     */
    public function getHtmlTags(Model $row, $field)
    {
        return [$field->elementId => $field->getValueType()->getLabel($field->get())];
    }
}
