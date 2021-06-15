<?php

declare(strict_types=1);

namespace Phlex\Ui\UserAction;

use Phlex\Ui\View;

class PreviewExecutor extends BasicExecutor
{
    /** @var View */
    public $preview;

    /**
     * @var string can be "console", "text", or "html"
     */
    public $previewType = 'console';

    public function initPreview()
    {
        if (!$this->hasAllArguments()) {
            \Phlex\Ui\Message::addTo($this, ['type' => 'error', $this->missingArgsMsg]);

            return;
        }

        $text = $this->executePreview();

        switch ($this->previewType) {
            case 'console':
                $this->preview = View::addTo($this, ['ui' => 'inverted black segment', 'element' => 'pre']);
                $this->preview->set($text);

                break;
            case 'text':
                $this->preview = View::addTo($this, ['ui' => 'segment']);
                $this->preview->set($text);

                break;
            case 'html':
                $this->preview = View::addTo($this, ['ui' => 'segment']);
                $this->preview->template->dangerouslySetHtml('Content', $text);

                break;
        }

        \Phlex\Ui\Button::addToWithCl($this, $this->executorButton)->on('click', function () {
            return $this->executeModelAction();
        });
    }

    public function executePreview()
    {
        $args = [];

        foreach ($this->action->args as $key => $val) {
            $args[] = $this->arguments[$key];
        }

        return $this->action->preview(...$args);
    }
}
