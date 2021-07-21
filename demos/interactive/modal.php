<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($app, ['Modal View']);

$session = new Session();
// Re-usable component implementing counter

\Phlex\Ui\Header::addTo($app, ['Static Modal Dialog']);

$bar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);

$modal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Add a name']);
\Phlex\Ui\LoremIpsum::addTo($modal);
\Phlex\Ui\Button::addTo($modal, ['Hide'])->on('click', $modal->hide());

$noTitle = \Phlex\Ui\Modal::addTo($app, ['title' => false]);
\Phlex\Ui\LoremIpsum::addTo($noTitle);
\Phlex\Ui\Button::addTo($noTitle, ['Hide'])->on('click', $noTitle->hide());

$scrolling = \Phlex\Ui\Modal::addTo($app, ['title' => 'Long Content that Scrolls inside Modal']);
$scrolling->addScrolling();
\Phlex\Ui\LoremIpsum::addTo($scrolling);
\Phlex\Ui\LoremIpsum::addTo($scrolling);
\Phlex\Ui\LoremIpsum::addTo($scrolling);
\Phlex\Ui\Button::addTo($scrolling, ['Hide'])->on('click', $scrolling->hide());

\Phlex\Ui\Button::addTo($bar, ['Show'])->on('click', $modal->show());
\Phlex\Ui\Button::addTo($bar, ['No Title'])->on('click', $noTitle->show());
\Phlex\Ui\Button::addTo($bar, ['Scrolling Content'])->on('click', $scrolling->show());

// Modal demos.

// REGULAR

$simpleModal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Simple modal']);
\Phlex\Ui\Message::addTo($simpleModal)->set('Modal message here.');
ViewTester::addTo($simpleModal);

$menuBar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);
$button = \Phlex\Ui\Button::addTo($menuBar)->set('Show Modal');
$button->on('click', $simpleModal->show());

// DYNAMIC

\Phlex\Ui\Header::addTo($app, ['Three levels of Modal loading dynamic content via callback']);

// vp1Modal will be render into page but hide until $vp1Modal->show() is activate.
$vp1Modal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Lorem Ipsum load dynamically']);

// vp2Modal will be render into page but hide until $vp1Modal->show() is activate.
$vp2Modal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Text message load dynamically'])->addClass('small');

$vp3Modal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Third level modal'])->addClass('small');
$vp3Modal->set(function ($modal) {
    \Phlex\Ui\Text::addTo($modal)->set('This is yet another modal');
    \Phlex\Ui\LoremIpsum::addTo($modal, ['size' => 2]);
});

// When $vp1Modal->show() is activate, it will dynamically add this content to it.
$vp1Modal->set(function ($modal) use ($vp2Modal) {
    ViewTester::addTo($modal);
    \Phlex\Ui\View::addTo($modal, ['Showing lorem ipsum']); // need in behat test.
    \Phlex\Ui\LoremIpsum::addTo($modal, ['size' => 2]);
    $form = \Phlex\Ui\Form::addTo($modal);
    $form->addControl('color', null, ['enum' => ['red', 'green', 'blue'], 'default' => 'green']);
    $form->onSubmit(function (\Phlex\Ui\Form $form) use ($vp2Modal) {
        return $vp2Modal->show(['color' => $form->model->get('color')]);
    });
});

// When $vp2Modal->show() is activate, it will dynamically add this content to it.
$vp2Modal->set(function ($modal) use ($vp3Modal) {
    // ViewTester::addTo($modal);
    \Phlex\Ui\Message::addTo($modal, ['Message', @$_GET['color']])->text->addParagraph('This text is loaded using a second modal.');
    \Phlex\Ui\Button::addTo($modal)->set('Third modal')->on('click', $vp3Modal->show());
});

$bar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);
$button = \Phlex\Ui\Button::addTo($bar)->set('Open Lorem Ipsum');
$button->on('click', $vp1Modal->show());

// ANIMATION

$menuItems = [
    'scale' => [],
    'flip' => ['horizontal flip', 'vertical flip'],
    'fade' => ['fade up', 'fade down', 'fade left', 'fade right'],
    'drop' => [],
    'fly' => ['fly left', 'fly right', 'fly up', 'fly down'],
    'swing' => ['swing left', 'swing right', 'swing up', 'swing down'],
    'slide' => ['slide left', 'slide right', 'slide up', 'slide down'],
    'browse' => ['browse', 'browse right'],
    'static' => ['jiggle', 'flash', 'shake', 'pulse', 'tada', 'bounce'],
];

\Phlex\Ui\Header::addTo($app, ['Modal Animation']);

$transitionModal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Animated modal']);
\Phlex\Ui\Message::addTo($transitionModal)->set('A lot of animated transition available');
$transitionModal->duration(1000);

$menuBar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);
$main = \Phlex\Ui\Menu::addTo($menuBar);
$transitionMenu = $main->addMenu('Select Transition');

foreach ($menuItems as $key => $items) {
    if (!empty($items)) {
        $sm = $transitionMenu->addMenu($key);
        foreach ($items as $item) {
            $smi = $sm->addItem($item);
            $smi->on('click', $transitionModal->js()->modal('setting', 'transition', $smi->js()->text())->modal('show'));
        }
    } else {
        $mi = $transitionMenu->addItem($key);
        $mi->on('click', $transitionModal->js()->modal('setting', 'transition', $mi->js()->text())->modal('show'));
    }
}

// DENY APPROVE

\Phlex\Ui\Header::addTo($app, ['Modal Options']);

$denyApproveModal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Deny / Approve actions']);
\Phlex\Ui\Message::addTo($denyApproveModal)->set('This modal is only closable via the green button');
$denyApproveModal->addDenyAction('No', new \Phlex\Ui\JsExpression('function(){window.alert("Can\'t do that."); return false;}'));
$denyApproveModal->addApproveAction('Yes', new \Phlex\Ui\JsExpression('function(){window.alert("You\'re good to go!");}'));
$denyApproveModal->notClosable();

$menuBar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);
$button = \Phlex\Ui\Button::addTo($menuBar)->set('Show Deny/Approve');
$button->on('click', $denyApproveModal->show());

// MULTI STEP

\Phlex\Ui\Header::addTo($app, ['Multiple page modal']);

// Add modal to layout.
$stepModal = \Phlex\Ui\Modal::addTo($app, ['title' => 'Multi step actions']);
$stepModal->setOption('observeChanges', true);

// Add buttons to modal for next and previous actions.
$action = new \Phlex\Ui\View(['ui' => 'buttons']);
$prevAction = new \Phlex\Ui\Button(['Prev', 'labeled', 'icon' => 'left arrow']);
$nextAction = new \Phlex\Ui\Button(['Next', 'iconRight' => 'right arrow']);

$action->addView($prevAction);
$action->addView($nextAction);

$stepModal->addButtonAction($action);

// Set modal functionality. Will changes content according to page being displayed.
$stepModal->set(function ($modal) use ($stepModal, $session, $prevAction, $nextAction) {
    $page = $session->recall('page', 1);
    $success = $session->recall('success', false);
    if (isset($_GET['move'])) {
        if ($_GET['move'] === 'next' && $success) {
            ++$page;
        }
        if ($_GET['move'] === 'prev' && $page > 1) {
            --$page;
        }
        $session->memorize('success', false);
    } elseif ($page === 2) {
    } else {
        $page = 1;
    }
    $session->memorize('page', $page);
    if ($page === 1) {
        \Phlex\Ui\Message::addTo($modal)->set('Thanks for choosing us. We will be asking some questions along the way.');
        $session->memorize('success', true);
        $modal->js(true, $prevAction->js(true)->show());
        $modal->js(true, $nextAction->js(true)->show());
        $modal->js(true, $prevAction->js()->addClass('disabled'));
        $modal->js(true, $nextAction->js(true)->removeClass('disabled'));
    } elseif ($page === 2) {
        $modelRegister = new \Phlex\Data\Model(new \Phlex\Data\Persistence\Array_());
        $modelRegister->addField('name', ['caption' => 'Please enter your name (John)']);

        $form = \Phlex\Ui\Form::addTo($modal, ['segment' => true]);
        $form->setModel($modelRegister);

        $form->onSubmit(function (\Phlex\Ui\Form $form) use ($nextAction, $session) {
            if ($form->model->get('name') !== 'John') {
                return $form->error('name', 'Your name is not John! It is "' . $form->model->get('name') . '". It should be John. Pleeease!');
            }

            $session->memorize('success', true);
            $session->memorize('name', $form->model->get('name'));
            $js[] = $form->success('Thank you, ' . $form->model->get('name') . ' you can go on!');
            $js[] = $nextAction->js()->removeClass('disabled');

            return $js;
        });
        $modal->js(true, $prevAction->js()->removeClass('disabled'));
        $modal->js(true, $nextAction->js(true)->addClass('disabled'));
    } elseif ($page === 3) {
        $name = $session->recall('name');
        \Phlex\Ui\Message::addTo($modal)->set("Thank you {$name} for visiting us! We will be in touch");
        $session->memorize('success', true);
        $modal->js(true, $prevAction->js(true)->hide());
        $modal->js(true, $nextAction->js(true)->hide());
    }
    $stepModal->js(true)->modal('refresh');
});

// Bind next action to modal next button.
$nextAction->on('click', $stepModal->js()->atkReloadView(
    ['uri' => $stepModal->cb->getJsUrl(), 'uri_options' => ['move' => 'next']]
));

// Bin prev action to modal previous button.
$prevAction->on('click', $stepModal->js()->atkReloadView(
    ['uri' => $stepModal->cb->getJsUrl(), 'uri_options' => ['move' => 'prev']]
));

// Bind display modal to page display button.
$menuBar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);
$button = \Phlex\Ui\Button::addTo($menuBar)->set('Multi Step Modal');
$button->on('click', $stepModal->show());
