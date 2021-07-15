<?php

declare(strict_types=1);

namespace Phlex\Ui;

class Label extends View
{
    public $ui = 'label';

    /**
     * Add icon before label. If 'string' or seed is specified, it will
     * be converted to object by doInitialize().
     *
     * @var View|array|string
     */
    public $icon;

    /**
     * Icon to the right of the label.
     *
     * @see $icon
     *
     * @var View|array|string
     */
    public $iconRight;

    /**
     * Add "Detail" to label.
     *
     * @var string|false|null
     */
    public $detail;

    /**
     * Image to the left of the label. Cannot be used with label. If string
     * is set, will be used as Image source. Can also contain seed or object.
     *
     * @var View|array|string
     */
    public $image;

    /**
     * Image to the right of the label.
     *
     * @see $image
     *
     * @var View|array|string
     */
    public $imageRight;

    public $defaultTemplate = 'label.html';

    protected function doRender(): void
    {
        if ($this->icon) {
            $this->icon = Icon::addTo($this, [$this->icon], ['BeforeContent']);
        }

        if ($this->image) {
            $this->image = Image::addTo($this, [$this->image], ['BeforeContent']);
            $this->addClass('image');
        }

        if (isset($this->detail)) {
            $this->detail = View::addTo($this, [$this->detail], ['AfterContent'])->addClass('detail');
        }

        if ($this->iconRight) {
            $this->iconRight = Icon::addTo($this, [$this->iconRight], ['AfterContent']);
        }

        if ($this->imageRight) {
            $this->imageRight = Image::addTo($this, [$this->imageRight], ['AfterContent']);
            $this->addClass('image');
        }

        parent::doRender();
    }
}
