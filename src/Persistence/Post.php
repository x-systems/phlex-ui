<?php

declare(strict_types=1);

namespace Phlex\Ui\Persistence;

use Phlex\Data\Model;

class Post extends \Phlex\Data\Persistence
{
    public function load(Model $model, $id = 0): array
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
}
