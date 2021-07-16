<?php

declare(strict_types=1);

namespace Phlex\Ui\Persistence;

use Phlex\Data;

class Post extends Data\Persistence
{
    public function load(Data\Model $model, $id = 0): array
    {
        // carefully copy stuff from $_POST into the model
        $data = [];

        foreach ($model->getFields() as $field => $def) {
            if ($def->type === 'boolean') {
                $data[$field] = isset($_POST[$field]);

                continue;
            }

            if (isset($_POST[$field])) {
                $data[$field] = $_POST[$field];
            }
        }

//        return array_merge($model->get(), $data);
        return $data;
    }

    public function query(Data\Model $model = null): Data\Persistence\Query
    {
    }

    public function lastInsertId(Data\Model $model = null): string
    {
    }
}
