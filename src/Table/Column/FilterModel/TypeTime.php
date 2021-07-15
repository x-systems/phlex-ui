<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column\FilterModel;

use Phlex\Ui\Table\Column;

class TypeTime extends Column\FilterModel
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
        $this->value->type = 'time';
        $this->addField('range', ['ui' => ['caption' => ''], 'type' => 'time']);
    }

    public function setConditionForModel($model)
    {
        $filter = $this->recalData();
        if (isset($filter['id'])) {
            switch ($filter['op']) {
                case 'between':
                    $d1 = $filter['value'];
                    $d2 = $filter['range'];
                    if ($d2 >= $d1) {
                        $value = $model->getField($filter['name'])->encode($d1);
                        $value2 = $model->getField($filter['name'])->encode($d2);
                    } else {
                    	$value = $model->getField($filter['name'])->encode($d2);
                    	$value2 = $model->getField($filter['name'])->encode($d1);
                    }
                    $model->addCondition($model->expr('[field] between [value] and [value2]', ['field' => $model->getField($filter['name']), 'value' => $value, 'value2' => $value2]));

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
