<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column\FilterModel;

use Phlex\Ui\Table\Column;

class TypeBoolean extends Column\FilterModel
{
    public $noValueField = true;

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->op->values = ['true' => 'Is Yes', 'false' => 'Is No'];
        $this->op->default = 'true';
    }

    public function setConditionForModel($model)
    {
        $filter = $this->recallData();
        if (isset($filter['id'])) {
            $model->addCondition($filter['name'], $filter['op'] === 'true');
        }

        return $model;
    }
}
