<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Card;
use Phlex\Ui\Header;
use Phlex\Ui\UserAction\ExecutorFactory;
use Phlex\Ui\View;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Executor Factory in App instance', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['factory']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

// Overriding basic ExecutorFactory in order to change Card button.
$myFactory = get_class(new class() extends ExecutorFactory {
    public const BUTTON_PRIMARY_COLOR = 'green';

    protected $actionIcon = [
        'callback' => 'sync',
        'preview' => 'eye',
        'edit_argument' => 'user edit',
        'edit_argument_prev' => 'pen square',
        'edit_iso' => 'pencil',
        'confirm' => 'check circle',
        'multi_step' => 'window maximize outline',
    ];

    public function __construct()
    {
        // registering card button default with our own method handler.
        $this->triggerSeed = array_merge(
            $this->triggerSeed,
            [self::CARD_BUTTON => ['default' => [$this, 'getCardButton']]]
        );
    }

    protected function getCardButton($action, $type)
    {
        return [Button::class, 'icon' => $this->actionIcon[$action->elementId]];
    }
});

Header::addTo($webpage, ['Executor Factory set for this Card View only.']);

DemoActionsUtil::setupDemoActions($country = new CountryLock($webpage->db));
$country = $country->loadAny();

$cardActions = Card::addTo($webpage, ['useLabel' => true, 'executorFactory' => new $myFactory()]);
$cardActions->setModel($country);
foreach ($country->getUserActions() as $action) {
    $showActions = ['callback', 'preview', 'edit_argument', 'edit_argument_prev', 'edit_iso', 'confirm', 'multi_step'];
    if (in_array($action->elementId, $showActions, true)) {
        $cardActions->addClickAction($action);
    }
}

// //////////////////////

Header::addTo($webpage, ['Card View using global Executor Factory']);

$model = new CountryLock($webpage->db);
$model = $model->loadAny();

$card = Card::addTo($webpage, ['useLabel' => true]);
$card->setModel($model);
$card->addClickAction($model->getUserAction('edit'));
$card->addClickAction($model->getUserAction('delete'));
