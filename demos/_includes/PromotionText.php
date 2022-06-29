<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

class PromotionText extends \Phlex\Ui\View
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $t = \Phlex\Ui\Text::addTo($this);
        $t->addParagraph(
            <<< 'EOF'
                Agile Toolkit base package includes:
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

        $gl = \Phlex\Ui\GridLayout::addTo($this, [null, 'stackable divided', 'columns' => 4]);
        \Phlex\Ui\Button::addTo($gl, ['Explore UI components', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c1'])
            ->link('https://github.com/atk4/ui/#bundled-and-planned-components');
        \Phlex\Ui\Button::addTo($gl, ['Try out interactive features', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c2'])
            ->link(['interactive/tabs']);
        \Phlex\Ui\Button::addTo($gl, ['Dive into Phlex Data', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c3'])
            ->link('https://git.io/ad');
        \Phlex\Ui\Button::addTo($gl, ['More Phlex Add-ons', 'primary basic fluid', 'iconRight' => 'right arrow'], ['r1c4'])
            ->link('https://github.com/atk4/ui/#add-ons-and-integrations');

        \Phlex\Ui\View::addTo($this, ['ui' => 'divider']);

        \Phlex\Ui\Message::addTo($this, ['Cool fact!', 'info', 'icon' => 'book'])->text
            ->addParagraph('This entire demo is coded in Agile Toolkit and takes up less than 300 lines of very simple code!');
    }
}
