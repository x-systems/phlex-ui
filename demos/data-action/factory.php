<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\CardDeck;
use Phlex\Ui\UserAction\ExecutorFactory;
use Phlex\Ui\View;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Executor Factory in View Instance', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['factory-view']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

$msg = \Phlex\Ui\Message::addTo($webpage, [
    'Customizing action trigger by Overriding Executor Factory',
]);
$msg->text->addParagraph('');

$msg->text->addHtml('Override Executor class may be applied globally, via the App instance like below, or per <a href="factory-view.php">View instance</a>.');

$msg->text->addParagraph('In this example, Crud and Card button was changed and set through the App instance.');

// Overriding basic ExecutorFactory in order to change Table and Modal button.
// and also changing default add action label.
$myFactory = get_class(new class() extends ExecutorFactory {
    public const BUTTON_PRIMARY_COLOR = 'green';

    protected $triggerSeed = [
        self::TABLE_BUTTON => [
            'edit' => [Button::class, null, 'icon' => 'pencil'],
            'delete' => [Button::class, null, 'icon' => 'times red'],
        ],
        self::CARD_BUTTON => [
            'edit' => [Button::class, 'Edit', 'icon' => 'pencil', 'ui' => 'tiny button'],
            'delete' => [Button::class, 'Remove', 'icon' => 'times', 'ui' => 'tiny button'],
        ],
    ];

    protected $triggerCaption = [
        'add' => 'Add New Record',
    ];
});

// Set new executor factory globally.
$webpage->setExecutorFactory(new $myFactory());

$country = new CountryLock($webpage->db);

$crud = \Phlex\Ui\Crud::addTo($webpage, ['ipp' => 5]);
$crud->setModel($country);

View::addTo($webpage, ['class' => ['ui divider']]);

$deck = CardDeck::addTo($webpage, ['menu' => false, 'search' => false, 'paginator' => false, 'useTable' => true]);
$deck->setModel($country->setLimit(3), [$country->key()->iso, $country->key()->iso3]);
