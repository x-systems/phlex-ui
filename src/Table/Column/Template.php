<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Ui\Table;

/**
 * Implements Column helper for grid.
 */
class Template extends Table\Column
{
    /**
     * User-defined template for this Column.
     *
     * @var string
     */
    public $template;

    /**
     * call new Table\Column\Template('{$name} {$surname}');.
     *
     * @param string $template Template with {$tags}
     */
    public function __construct($template)
    {
        $this->template = $template;
        /*
        if (is_array($template) && isset($template[0])) {
            $this->template = $template[0];
        } elseif (is_string($template)) {
            $this->template = $template;
        }
         */
    }

    public function getDataCellTemplate(\Phlex\Data\Model\Field $field = null)
    {
        return $this->template;
    }
}
