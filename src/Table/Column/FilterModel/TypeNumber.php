<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column\FilterModel;

use Phlex\Ui\Table\Column;

class TypeNumber extends Column\FilterModel
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->op->values = [
            '=' => '=',
            '!=' => '!=',
            '<' => '<',
            '<=' => '< or equal',
            '>' => '>',
            '>=' => '> or equal',
            'between' => 'Between',
        ];
        $this->op->default = '=';

        $this->value->ui['form'] = [\Phlex\Ui\Form\Control\Line::class, 'inputType' => 'number'];
        $this->addField('range', ['ui' => ['caption' => '', 'form' => [\Phlex\Ui\Form\Control\Line::class, 'inputType' => 'number']]]);
    }

    public function setConditionForModel($model)
    {
        $filter = $this->recallData();
        if (isset($filter['id'])) {
            switch ($filter['op']) {
                case 'between':
                    $model->addCondition(
                        $model->expr('[field] between [value] and [range]', ['field' => $model->getField($filter['name']), 'value' => $filter['value'], 'range' => $filter['range']])
                    );

                    break;
                default:
                    $model->addCondition($filter['name'], $filter['op'], $filter['value']);
            }
        }

        return $model;
    }

    public function getFormDisplayRules()
    {
        return [
            'range' => ['op' => 'isExactly[between]'],
        ];
    }
}
