<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column\FilterModel;

use Phlex\Data\Model;
use Phlex\Data\Model\Field\Type\Selectable;
use Phlex\Ui\Form;
use Phlex\Ui\Table\Column;

class TypeEnum extends Column\FilterModel
{
    protected function doInitialize(): void
    {
        // bypass parent init since we are not using op and value field but create them from
        // the lookup field value.
        Model::doInitialize();
        $this->afterInit();

        $this->op = null;
        if ($this->lookupField->getValueType() instanceof Selectable) {
            foreach ($this->lookupField->getValueType()->values as $key => $value) {
                $this->addField($key, ['type' => 'boolean', 'options' => [Form\Control::OPTION_SEED => ['caption' => $value]]]);
            }
        }
    }

    public function setConditionForModel($model)
    {
        if ($filter = $this->recallData()) {
            $values = [];
            foreach ($filter as $key => $isSet) {
                if ($isSet === true) {
                    $values[] = $key;
                }
            }
            if (!empty($values)) {
                $model->addCondition($filter['name'], 'in', $values);
            }
        }

        return $model;
    }
}
