<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column\FilterModel;

use Phlex\Ui\Table\Column;

class TypeString extends Column\FilterModel
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->op->values = ['is' => 'Is', 'contains' => 'Contains', 'start' => 'Start with', 'end' => 'End with'];
        $this->op->default = 'is';
    }

    public function setConditionForModel($model)
    {
        $filter = $this->recallData();
        if (isset($filter['id'])) {
            switch ($filter['op']) {
                case 'is':
                    $model->addCondition($filter['name'], $filter['value']);

                    break;
                case 'is not':
                    $model->addCondition($filter['name'], '!=', $filter['value']);

                    break;
                case 'contains':
                    $model->addCondition($filter['name'], 'LIKE', '%' . $filter['value'] . '%');

                    break;
                case 'start':
                    $model->addCondition($filter['name'], 'LIKE', $filter['value'] . '%');

                    break;
                case 'end':
                    $model->addCondition($filter['name'], 'LIKE', '%' . $filter['value']);

                    break;
            }
        }

        return $model;
    }
}
