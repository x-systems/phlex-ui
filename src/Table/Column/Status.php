<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model;
use Phlex\Ui\Table;
use Phlex\Ui\Webpage;

/**
 * Implements Column helper for grid.
 */
class Status extends Table\Column
{
    /**
     * Describes list of highlited statuses for this Field.
     *
     * @var array
     */
    public $states = [];

    /**
     * Pass argument with possible states like this:.
     *
     *  [ 'positive'=>['Paid', 'Archived'], 'negative'=>['Overdue'] ]
     *
     * @param array $states List of status=>[value,value,value]
     */
    public function __construct($states)
    {
        $this->states = $states;
    }

    public function getDataCellHtml(Model\Field $field = null, $extra_tags = [])
    {
        if ($field === null) {
            throw new \Phlex\Ui\Exception('Status can be used only with model field');
        }

        $attr = $this->getTagAttributes('body');

        $extra_tags = array_merge_recursive($attr, $extra_tags, ['class' => '{$_' . $field->elementId . '_status}']);

        if (is_array($extra_tags['class'] ?? null)) {
            $extra_tags['class'] = implode(' ', $extra_tags['class']);
        }

        return Webpage::tag(
            'td',
            $extra_tags,
            [Webpage::tag('i', ['class' => 'icon {$_' . $field->elementId . '_icon}'], '') .
            ' {$' . $field->elementId . '}', ]
        );
    }

    public function getHtmlTags(Model $row, $field)
    {
        $cl = '';

        // search for a class
        foreach ($this->states as $class => $values) {
            if (in_array($field->get(), $values, true)) {
                $cl = $class;

                break;
            }
        }

        if (!$cl) {
            return [];
        }

        switch ($cl) {
            case 'positive':
                $ic = 'checkmark';

                break;
            case 'negative':
                $ic = 'close';

                break;
            case 'default':
                $ic = 'question';

                break;
            default:
                $ic = '';
        }

        return [
            '_' . $field->elementId . '_status' => $cl . ' single line',
            '_' . $field->elementId . '_icon' => $ic,
        ];
    }
}
