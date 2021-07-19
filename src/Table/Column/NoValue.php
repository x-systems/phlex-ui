<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model;
use Phlex\Ui\Table;

/**
 * Class NoValue.
 *
 * sometime we need null values in db
 *
 * when we display values we have holes
 * with NoValue decorator we can show a display value for column null value
 *
 * @usage   :
 *
 * $this->addField('field', [
 *  [...]
 *  'options' => [
 *          [...]
 *          \Phlex\Ui\Table\Column::OPTION_SEED => [
 *              \Phlex\Ui\Table\Column\NoValue::class, ' if empty display this value '
 *          ]
 *      ]
 * ]);
 */
class NoValue extends Table\Column
{
    /** @var string */
    public $displayValue = ' --- ';

    public function getHtmlTags(Model $row, $field)
    {
        $actualValue = $field->get();

        if (empty($actualValue) || $actualValue === null) {
            return [$field->short_name => $this->displayValue];
        }

        return parent::getHtmlTags($row, $field);
    }
}
