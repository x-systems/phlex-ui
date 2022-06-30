<?php

declare(strict_types=1);

namespace Phlex\Ui\Component;

use Phlex\Data\Model;
use Phlex\Ui\JsVueService;
use Phlex\Ui\View;

/**
 * Will send query with define callback and reload a specific view.
 */
class ItemSearch extends View
{
    /**
     * View to be reload that contains data to be filtered.
     *
     * @var View|string the atk4 View to be reload or a jquery id selector string
     */
    public $reload;

    /**
     * The initial query.
     *
     * @var string
     */
    public $q;

    /**
     * The css for the input field.
     *
     * @var string
     */
    public $inputCss = 'ui input right icon transparent';

    /**
     * Keyboard debounce time in ms for the input.
     * Will limit network request while user is typing search criteria.
     *
     * @var int
     */
    public $inputTimeOut = 350;

    /**
     * The jquery selector where you need to add the semantic-ui 'loading' class.
     * Default to reload selector.
     *
     * @var View
     */
    public $context;

    /** @var string|null The URL argument name use for query. If null, then->>name will be assiged. */
    public $queryArg;

    public $defaultTemplate = 'item-search.html';

    protected function doInitialize(): void
    {
        parent::doInitialize();

        if (!$this->queryArg) {
            $this->queryArg = $this->elementName;
        }

        if (!$this->q) {
            $this->q = $this->getQuery();
        }
    }

    /**
     * Return query string sent by request.
     *
     * @return string
     */
    public function getQuery()
    {
        return $_GET[$this->queryArg] ?? null;
    }

    /**
     * Set model condition base on search request.
     */
    public function setModelCondition(Model $model): Model
    {
        if ($q = $this->getQuery()) {
            $model->addCondition($model->titleKey, 'like', '%' . $q . '%');
        }

        return $model;
    }

    protected function doRender(): void
    {
        $this->class = [];
        parent::doRender();

        // reloadId is the view id selector name that need to be reload.
        // this will be pass as get argument to __phlex_reload.
        if ($this->reload instanceof View) {
            $reloadId = $this->reload->elementName;
        } else {
            $reloadId = $this->reload;
        }

        $this->js(true, (new JsVueService())->createPhlexVue(
            '#' . $this->elementName,
            'phlex-item-search',
            [
                'reload' => $reloadId,
                'queryArg' => $this->queryArg,
                'url' => $this->reload->jsUrl(),
                'q' => $this->q,
                'context' => $this->context,
                'options' => [
                    'inputTimeOut' => $this->inputTimeOut,
                    'inputCss' => $this->inputCss,
                ],
            ]
        ));
    }
}
