<?php

declare(strict_types=1);

namespace Phlex\Ui;

use Phlex\Data\Model;

class Lister extends View
{
    use \Phlex\Core\HookTrait;

    /** @const string */
    public const HOOK_BEFORE_ROW = self::class . '@beforeRow';
    /** @const string */
    public const HOOK_AFTER_ROW = self::class . '@afterRow';

    /**
     * Lister repeats part of it's template. This property will contain
     * the repeating part. Clones from {row}. If your template does not
     * have {row} tag, then entire template will be repeated.
     *
     * @var HtmlTemplate
     */
    public $templateRow;

    /**
     * Lister use this part of template in case there are no elements in it.
     *
     * @var HtmlTemplate|null
     */
    public $templateEmpty;

    public $defaultTemplate;

    /**
     * A dynamic paginator attach to window scroll event.
     *
     * @var JsPaginator|null
     */
    public $jsPaginator;

    /**
     * The number of item per page for JsPaginator.
     *
     * @var int|null
     */
    public $ipp;

    /**
     * Initialization.
     */
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->initChunks();
    }

    /**
     * From the current template will extract {row} into $this->t_row_master and {empty} into $this->t_empty.
     */
    public function initChunks()
    {
        if (!$this->template) {
            throw new Exception('Lister does not have default template. Either supply your own HTML or use "defaultTemplate"=>"lister.html"');
        }

        // empty row template
        if ($this->template->hasTag('empty')) {
            $this->templateEmpty = $this->template->cloneRegion('empty');
            $this->template->del('empty');
        }

        // data row template
        if ($this->template->hasTag('row')) {
            $this->templateRow = $this->template->cloneRegion('row');
            $this->template->del('rows');
        } else {
            $this->templateRow = clone $this->template;
            $this->template->del('_top');
        }
    }

    /**
     * Add Dynamic paginator when scrolling content via Javascript.
     * Will output x item in lister set per ipp until user scroll content to the end of page.
     * When this happen, content will be reload x number of items.
     *
     * @param int    $ipp          Number of item per page
     * @param array  $options      an array with js Scroll plugin options
     * @param View   $container    The container holding the lister for scrolling purpose. Default to view owner.
     * @param string $scrollRegion A specific template region to render. Render output is append to container html element.
     *
     * @return $this
     */
    public function addJsPaginator($ipp, $options = [], $container = null, $scrollRegion = null)
    {
        $this->ipp = $ipp;
        $this->jsPaginator = JsPaginator::addTo($this, ['view' => $container, 'options' => $options]);

        // set initial model limit. can be overwritten by onScroll
        $this->model->setLimit($ipp);

        // add onScroll callback
        $this->jsPaginator->onScroll(function ($p) use ($ipp, $scrollRegion) {
            // set/overwrite model limit
            $this->model->setLimit($ipp, ($p - 1) * $ipp);

            // render this View (it will count rendered records !)
            $jsonArr = $this->renderToJsonArr(true, $scrollRegion);

            // if there will be no more pages, then replace message=Success to let JS know that there are no more records
            if ($this->_rendered_rows_count < $ipp) {
                $jsonArr['message'] = 'Done'; // Done status means - no more requests from JS side
            }

            // return json response
            $this->getApp()->terminateJson($jsonArr);
        });

        return $this;
    }

    /** @var int This will count how many rows are rendered. Needed for JsPaginator for example. */
    protected $_rendered_rows_count = 0;

    protected function doRender(): void
    {
        if (!$this->template) {
            throw new Exception('Lister requires you to specify template explicitly');
        }

        // if no model is set, don't show anything (even warning)
        if (!$this->model) {
            parent::doRender();

            return;
        }

        // Generate template for data row
        $this->templateRow->trySet('_id', $this->name);

        // Iterate data rows
        $this->_rendered_rows_count = 0;

        foreach ($this->model as $entity) {
            if ($this->hook(self::HOOK_BEFORE_ROW, [$entity]) === false) {
                continue;
            }

            $this->renderRow($entity);

            ++$this->_rendered_rows_count;
        }

        // empty message
        if (!$this->_rendered_rows_count) {
            if (!$this->jsPaginator || !$this->jsPaginator->getPage()) {
                $empty = isset($this->templateEmpty) ? $this->templateEmpty->renderToHtml() : '';
                if ($this->template->hasTag('rows')) {
                    $this->template->dangerouslyAppendHtml('rows', $empty);
                } else {
                    $this->template->dangerouslyAppendHtml('_top', $empty);
                }
            }
        }

        // stop JsPaginator if there are no more records to fetch
        if ($this->jsPaginator && ($this->_rendered_rows_count < $this->ipp)) {
            $this->jsPaginator->jsIdle();
        }

        parent::doRender();
    }

    /**
     * Render individual row. Override this method if you want to do more
     * decoration.
     */
    public function renderRow(Model $entity)
    {
        $this->templateRow->trySet($entity->encode($this));

        $this->templateRow->trySet('_title', $entity->getTitle());
        $this->templateRow->trySet('_href', $this->url(['id' => $entity->getId()]));
        $this->templateRow->trySet('_id', $entity->getId());

        $html = $this->templateRow->renderToHtml();
        if ($this->template->hasTag('rows')) {
            $this->template->dangerouslyAppendHtml('rows', $html);
        } else {
            $this->template->dangerouslyAppendHtml('_top', $html);
        }
    }
}
