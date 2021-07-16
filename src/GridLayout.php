<?php

declare(strict_types=1);

namespace Phlex\Ui;

class GridLayout extends View
{
    /** @var int Number of rows */
    protected $rows = 1;

    /** @var int Number of columns */
    protected $columns = 2;

    /** @var array Array of columns css wide classes */
    protected $words = [
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve',
        'thirteen', 'fourteen', 'fifteen', 'sixteen',
    ];

    /**
     * @var HtmlTemplate
     */
    protected $templateWrap;

    /**
     * @var HtmlTemplate
     */
    protected $templateRow;

    /**
     * @var HtmlTemplate
     */
    protected $templateColumn;

    /**
     * @var HtmlTemplate
     */
    public $template;

    /** @var string Semantic UI CSS class */
    public $ui = 'grid';

    /** @var string Template file */
    public $defaultTemplate = 'grid-layout.html';

    /** @var string CSS class for columns view */
    public $column_class = '';

    /**
     * Initialization.
     */
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->template->set('column_class', $this->column_class);

        // extract template parts
        $this->templateWrap = clone $this->template;
        $this->templateRow = $this->template->cloneRegion('row');
        $this->templateColumn = $this->template->cloneRegion('column');

        // clean them
        $this->templateRow->del('column');
        $this->templateWrap->del('rows');

        // Will need to manipulate template a little
        $this->buildTemplate();
    }

    /**
     * Build and set view template.
     */
    protected function buildTemplate()
    {
        $this->templateWrap->del('rows');
        $this->templateWrap->dangerouslyAppendHtml('rows', '{rows}');

        for ($row = 1; $row <= $this->rows; ++$row) {
            $this->templateRow->del('column');

            for ($col = 1; $col <= $this->columns; ++$col) {
                $this->templateColumn->set('Content', '{$r' . $row . 'c' . $col . '}');

                $this->templateRow->dangerouslyAppendHtml('column', $this->templateColumn->renderToHtml());
            }

            $this->templateWrap->dangerouslyAppendHtml('rows', $this->templateRow->renderToHtml());
        }
        $this->templateWrap->dangerouslyAppendHtml('rows', '{/rows}');
        $tmp = new HtmlTemplate($this->templateWrap->renderToHtml());

        // TODO replace later, the only use of direct template tree manipulation
        $t = $this->template;
        \Closure::bind(function () use ($t, $tmp) {
            $cloneTagTreeFx = function (HtmlTemplate\TagTree $src) use (&$cloneTagTreeFx, $t) {
                $tagTree = $src->clone($t);
                $t->tagTrees[$src->getTag()] = $tagTree;
                \Closure::bind(function () use ($tagTree, $cloneTagTreeFx, $src) {
                    foreach ($tagTree->children as $v) {
                        if (is_string($v)) {
                            $cloneTagTreeFx($src->getParentTemplate()->getTagTree($v));
                        }
                    }
                }, null, HtmlTemplate\TagTree::class)();
            };
            $cloneTagTreeFx($tmp->getTagTree('rows'));

        // TODO prune unreachable nodes
        // $template->rebuildTagsIndex();
        }, null, HtmlTemplate::class)();

        $this->addClass($this->words[$this->columns] . ' column');
    }
}
