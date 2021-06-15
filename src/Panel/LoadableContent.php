<?php

declare(strict_types=1);
/**
 * LoadableContent interface.
 */

namespace Phlex\Ui\Panel;

use Phlex\Ui\Callback;

interface LoadableContent
{
    /**
     * Add JsCallback.
     */
    public function setCb(Callback $cb): void;

    /**
     * Return js Callback url string.
     */
    public function getCallbackUrl(): string;

    /**
     * The callback for loading content.
     */
    public function onLoad(\Closure $fx): void;
}
