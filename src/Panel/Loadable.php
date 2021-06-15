<?php

declare(strict_types=1);
/**
 * Loadable Interface.
 */

namespace Phlex\Ui\Panel;

interface Loadable
{
    /** Add loadable content to panel. */
    public function addDynamicContent(LoadableContent $content);

    /** Get panel loadable content. */
    public function getDynamicContent(): LoadableContent;
}
