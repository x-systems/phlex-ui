<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Faker\Factory;
use Faker\Generator;
use Phlex\Data\Model;
use Phlex\Data\Persistence\Array_;

class Persistence_Faker extends Array_
{
    /** @var Generator */
    public $faker;

    /** @var int */
    public $count = 5;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function configure(Model $model): Model
    {
        $this->onHook(self::HOOK_AFTER_ADD, function ($persistence, $model) {
            $data = [];
            for ($i = 0; $i < $this->count; ++$i) {
                $row = [];
                foreach ($model->getFields() as $field) {
                    $key = $field->getCodec($this)->getKey();

                    if ($field->isPrimaryKey()) {
                        $row[$key] = $i + 1;

                        continue;
                    }

                    if ($key === 'logo_url') {
                        $row[$key] = '../images/' . ['default.png', 'logo.png'][random_int(0, 1)]; // one of these
                    } else {
                        $row[$key] = $this->faker->{$key}();
                    }
                }
                $data[$i + 1] = $row;
            }

            $this->data = $model->table ? [$model->table => $data] : $data;
        });

        return $model;
    }
}
