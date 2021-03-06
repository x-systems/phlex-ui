<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form\Control\Multiline;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

// This demo require specific Database setup.

if (!class_exists(Client::class)) {
    class Client extends \Phlex\Data\Model
    {
        public $table = 'client';
        public $caption = 'Client';

        protected function init(): void
        {
            parent::init();

            $this->addField('name');
            $this->containsMany('Accounts', ['model' => [Account::class]]);
        }
    }

    class Account extends \Phlex\Data\Model
    {
        public $caption = ' ';

        protected function init(): void
        {
            parent::init();

            $this->addField('email', [
                'required' => true,
                'ui' => ['multiline' => [Multiline::INPUT => ['icon' => 'envelope', 'type' => 'email']]],
            ]);
            $this->addField('password', [
                'required' => true,
                'ui' => ['multiline' => [Multiline::INPUT => ['icon' => 'key', 'type' => 'password']]],
            ]);
            $this->addField('site', ['required' => true]);
            $this->addField('type', [
                'default' => 'user',
                'values' => ['user' => 'Regular User', 'admin' => 'System Admin'],
                'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 'four']]],
            ]);
        }
    }
}

\Phlex\Ui\Crud::addTo($app)->setModel(new Client($app->db));
