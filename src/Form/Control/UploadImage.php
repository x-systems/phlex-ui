<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Control;

use Phlex\Ui\View;

class UploadImage extends Upload
{
    /**
     * The thumbnail view to add to this input.
     *
     * @var View|null
     */
    public $thumbnail;

    /**
     * The template region where to add the thumbnail view.
     * Default to AfterAfterInput.
     *
     * @var string
     */
    public $thumnailRegion = 'AfterAfterInput';

    /**
     * The default thumbnail source.
     *
     * @var string
     */
    public $defaultSrc;

    protected function doInitialize(): void
    {
        parent::doInitialize();

        if (!$this->accept) {
            $this->accept = ['.jpg', '.jpeg', '.png'];
        }

        if (!$this->thumbnail) {
            $this->thumbnail = (new View(['element' => 'img', 'class' => ['right', 'floated', 'image'], 'ui' => true]))
                ->setAttribute(['width' => '36px', 'height' => '36px']);
        }

        if ($this->defaultSrc) {
            $this->thumbnail->setAttribute(['src' => $this->defaultSrc]);
        }

        $this->add($this->thumbnail, $this->thumnailRegion);
    }

    /**
     * Set the thumbnail img src value.
     *
     * @param string $src
     */
    public function setThumbnailSrc($src)
    {
        $this->thumbnail->setAttribute(['src' => $src]);
        $action = $this->thumbnail->js();
        $action->attr('src', $src);
        $this->addJsAction($action);
    }

    /**
     * Clear the thumbnail src.
     * You can also supply a default thumbnail src.
     */
    public function clearThumbnail($defaultThumbnail = null)
    {
        $action = $this->thumbnail->js();
        if (isset($defaultThumbnail)) {
            $action->attr('src', $defaultThumbnail);
        } else {
            $action->removeAttr('src');
        }
        $this->addJsAction($action);
    }
}
