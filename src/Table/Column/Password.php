<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model\Field;
use Phlex\Ui\Table;

/**
 * Implements Column helper for grid.
 */
class Password extends Table\Column
{
    public $sortable = false;

    public function getDataCellTemplate(Field $field = null)
    {
        return '***';
    }
}
