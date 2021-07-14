<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

class Flyers extends \Phlex\Data\Model
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField('first_name');
        $this->addField('last_name');
        $this->addField('age', ['values' => ['1' => 'From months to 2 years old', '2' => 'From 3 to 17 years old', '3' => '18 years or more']]);
    }
}
