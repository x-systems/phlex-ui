<?php

declare(strict_types=1);

namespace Phlex\Ui;

use Phlex\Data\Model;

/**
 * Card class displays a single record data.
 *
 * IMPORTANT: Although the purpose of the "Card" component will remain the same, we do plan to
 * improve implementation of a card to to use https://semantic-ui.com/views/card.html.
 */
class CardTable extends Table
{
    protected $_bypass = false;

    public function setModel(Model $model, $columndef = null)
    {
        if ($this->_bypass) {
            return parent::setModel($model);
        }

        if (!$model->isLoaded()) {
            throw (new Exception('Model must be loaded'))
                ->addMoreInfo('model', $model);
        }

        $data = [];

        $ui_values = $this->encodeRow($model, $model->get());

        foreach ($model->get() as $key => $value) {
            if (!$columndef || ($columndef && in_array($key, $columndef, true))) {
                $data[] = [
                    'id' => $key,
                    'field' => $model->getField($key)->getCaption(),
                    'value' => $ui_values[$key],
                ];
            }
        }

        $this->_bypass = true;
        $mm = parent::setSource($data);
        $this->addDecorator('value', [Table\Column\Multiformat::class, function (Model $row, $field) use ($model) {
            $column = Table\Column::factory($model->getField($row->getId()));

            if ($column instanceof Table\Column\Money) {
                $column->attr['all']['class'] = ['single line'];
            }

            return $this->_addUnchecked($column);
        }]);
        $this->_bypass = false;

        return $mm;
    }
}
