<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

class SomeData extends \Phlex\Data\Model
{
    public function __construct()
    {
        $fakerPersistence = new Persistence_Faker();

        parent::__construct($fakerPersistence);
    }

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $model = $this;

        $model->addField('title');
        $model->addField('name', ['actual' => 'firstName']);
        $model->addField('surname', ['actual' => 'lastName']);
        $model->addField('date', ['type' => 'date']);
        $model->addField('salary', ['type' => 'money', 'actual' => 'randomNumber']);
        $model->addField('logo_url');
    }
}
