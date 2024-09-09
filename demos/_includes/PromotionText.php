<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\GridLayout;
use Phlex\Ui\Message;
use Phlex\Ui\Text;
use Phlex\Ui\View;

class PromotionText extends View
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $t = Text::addTo($this);
        $t->addParagraph(
            <<< 'EOF'
                Phlex UI base package includes:
                EOF
        );

        $t->addHtml(
            <<< 'HTML'
                <ul>
                <li>Over 40 ready-to-use and nicely styled UI components</li>
                <li>Over 10 ways to build interraction</li>
                <li>Over 10 configurable field types, relations, aggregation and much more</li>
                <li>Over 5 SQL and some NoSQL vendors fully supported</li>
                </ul>
                HTML
        );

        $gl = GridLayout::addTo($this, [null, 'stackable divided', 'columns' => 4]);
        Button::addTo($gl, ['Explore UI components', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c1'])
            ->link('https://github.com/atk4/ui/#bundled-and-planned-components');
        Button::addTo($gl, ['Try out interactive features', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c2'])
            ->link(['interactive/tabs']);
        Button::addTo($gl, ['Dive into Phlex Data', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c3'])
            ->link('https://git.io/ad');
        Button::addTo($gl, ['More Phlex Add-ons', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c4'])
            ->link('https://github.com/atk4/ui/#add-ons-and-integrations');

        View::addTo($this, ['ui' => 'divider']);

        Message::addTo($this, ['Cool fact!', 'info', 'icon' => 'book'])->text
            ->addParagraph('This entire demo is coded with Phlex UI and takes up less than 300 lines of very simple code!');
    }
}
