<?php

declare(strict_types=1);

namespace Phlex\Ui;

class JsSortable extends JsCallback
{
    /**
     * The html element that contains others element for reordering.
     *
     * @var string
     */
    public $container = 'tbody';

    /**
     * The html element inside the container that need reordering.
     *
     * @var string
     */
    public $draggable = 'tr';

    /**
     * The data label set as data-label attribute on the html element.
     *  The callback will send source parameter on the moved element using this attribute.
     *  default to data-id.
     *
     * If the data-{label} attribute is not set for each list element, then the $_POST['order']
     * value will be empty. Only org_idx and new_idx will be sent in callback request.
     *
     * @var string
     */
    public $dataLabel = 'id';

    /**
     * The css class name of the handle element for dragging purpose.
     *   if null, the entire element become the dragging handle.
     *
     * @var string|null
     */
    public $handleClass;

    /**
     * Whether callback will be fire automatically or not.
     *
     * @var bool
     */
    public $autoFireCb = true;

    /**
     * The View that need reordering.
     *
     * @var View|null
     */
    public $view;

    protected function doInitialize(): void
    {
        parent::doInitialize();
        if (!$this->view) {
            $this->view = $this->getOwner();
        }
        $this->getApp()->requireJs('https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.5/lib/draggable.bundle.js');

        $this->view->js(true)->phlexJsSortable([
            'uri' => $this->getJsUrl(),
            'uri_options' => $this->args,
            'container' => $this->container,
            'draggable' => $this->draggable,
            'handleClass' => $this->handleClass,
            'dataLabel' => $this->dataLabel,
            'autoFireCb' => $this->autoFireCb,
        ]);
    }

    /**
     * Callback when container has been reorder.
     */
    public function onReorder(\Closure $fx)
    {
        $this->set(function () use ($fx) {
            $sortOrders = explode(',', $_POST['order'] ?? '');
            $source = $_POST['source'] ?? null;
            $newIdx = $_POST['new_idx'] ?? null;
            $orgIdx = $_POST['org_idx'] ?? null;

            return $fx($sortOrders, $source, $newIdx, $orgIdx);
        });
    }

    /**
     * return js action to retrieve order.
     *
     * @param array|null $uriOptions
     *
     * @return mixed
     */
    public function jsGetOrders($uriOptions = null)
    {
        return $this->view->js()->phlexJsSortable('getSortOrders', [$uriOptions]);
    }
}
