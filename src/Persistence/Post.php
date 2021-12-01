<?php

declare(strict_types=1);

namespace Phlex\Ui\Persistence;

use Phlex\Data;

class Post extends Data\Persistence\Array_
{
    public function add(Data\Model $model, array $defaults = []): Data\Model
    {
        $row = [];
        foreach ($model->getFields() as $field) {
            $key = $field->getCodec($this)->getKey();

            if ($field->isPrimaryKey()) {
                $row[$key] = 0;

                continue;
            }

            if ($field->getValueType() instanceof Data\Model\Field\Type\Boolean) {
                $row[$key] = isset($_POST[$field->elementId]);

                continue;
            }

            if (isset($_POST[$field->elementId])) {
                $row[$key] = $_POST[$field->elementId];
            }
        }
        $data = [$row];

        $this->data = $model->table ? [$model->table => $data] : $data;

        return parent::add($model, $defaults);
    }
}
