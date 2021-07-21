<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\View::addTo($webpage, [
    'Sticky GET allows us to preserve some GET arguments',
    'ui' => 'ignored info message',
]);

/** @var \Phlex\Ui\Button $myButtonClass */
$myButtonClass = get_class(new class() extends \Phlex\Ui\Button {
    protected function doRender(): void
    {
        $this->link($this->content);
        $this->addClass('green');

        parent::doRender();
    }
});

// Buttons
$myButtonClass::addTo($webpage, [$webpage->url()]);
$myButtonClass::addTo($webpage, [$webpage->url(['xx' => 'YEY'])]);
$myButtonClass::addTo($webpage, [$webpage->url(['c' => 'OHO'])]);
$myButtonClass::addTo($webpage, [$webpage->url(['xx' => 'YEY', 'c' => 'OHO'])]);

// URLs presented by a blank app
\Phlex\Ui\Header::addTo($webpage, ['URLs presented by a blank app']);
Button::addTo($webpage, [$webpage->url()]);
Button::addTo($webpage, [$webpage->url(['b' => 2])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => false])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => null])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => 'abc'])]);

// Sticky for xx=
\Phlex\Ui\Header::addTo($webpage, ['Now add sticky for xx=' . $webpage->stickyGet('xx')]);
Button::addTo($webpage, [$webpage->url()]);
Button::addTo($webpage, [$webpage->url(['b' => 2])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => false])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => null])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => 'abc'])]);

// Sticky for c=
\Phlex\Ui\Header::addTo($webpage, ['Now also add sticky for c=' . $webpage->stickyGet('c')]);
Button::addTo($webpage, [$webpage->url()]);
Button::addTo($webpage, [$webpage->url(['b' => 2])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => false])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => null])]);
Button::addTo($webpage, [$webpage->url(['b' => 2, 'c' => 'abc'])]);

// Various ways to build links
\Phlex\Ui\Header::addTo($webpage, ['Various ways to build links']);
Button::addTo($webpage, [$webpage->url()]);
Button::addTo($webpage, [$webpage->url('other.php')]);
Button::addTo($webpage, [$webpage->url('other')]);
Button::addTo($webpage, [$webpage->url(['other', 'b' => 2])]);
Button::addTo($webpage, [$webpage->url('http://yahoo.com/')]);
Button::addTo($webpage, [$webpage->url('http://yahoo.com/?q=abc')]);
