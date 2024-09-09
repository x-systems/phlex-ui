<?php

declare(strict_types=1);

namespace Phlex\Ui\Exception;

use Phlex\Ui\Exception;

class NoRenderTree extends Exception
{
    public function __construct($object, $action = '')
    {
        parent::__construct('You must use either add($obj) or $obj->initialize() before ' . ($action ?: 'performing this action'));
        $this->addMoreInfo('obj', $object);
    }
}
