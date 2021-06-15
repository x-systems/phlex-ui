<?php

declare(strict_types=1);

namespace Phlex\Ui\Exception;

class NoRenderTree extends \Phlex\Ui\Exception
{
    public function __construct($object, $action = '')
    {
        parent::__construct('You must use either add($obj) or $obj->initialize() before ' . ($action ?: 'performing this action'));
        $this->addMoreInfo('obj', $object);
    }
}
