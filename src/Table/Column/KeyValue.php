<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model;
use Phlex\Ui\Exception;
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
 *  'caption' => __('Payment Status'),
 *  'default' => 0,
 *  'values' => [
 *      0 => __('not invoiceable'),
 *      1 => __('ready to invoice'),
 *      2 => __('invoiced'),
 *      3 => __('paid'),
 *  ],
 *  'ui'      => [
 *      'form' => [\Phlex\Ui\Form\Control\Dropdown::class],
 *      'table' => ['KeyValue'],
 *  ],
 * ]);
 */
class KeyValue extends Table\Column
{
    public $values = [];

    protected function doInitialize(): void
    {
        parent::doInitialize();
    }

    /**
     * @param Model\Field|null $field
     *
     * @return array
     */
    public function getHtmlTags(Model $row, $field)
    {
        $values = $field->values;

        if (!is_array($values)) {
            throw new Exception('KeyValues Column need values in field definition');
        }

        if (count($values) === 0) {
            throw new Exception('KeyValues Column values must have elements');
        }

        $key = $field->get();
        $value = $values[$key] ?? '';

        return [$field->short_name => $value];
    }
}
