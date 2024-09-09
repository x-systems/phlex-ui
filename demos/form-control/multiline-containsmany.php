<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Crud;
use Phlex\Ui\Form\Control\Multiline;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// This demo require specific Database setup.

if (!class_exists(Client::class)) {
    class Client extends Model
    {
        public $table = 'client';
        public $caption = 'Client';

        protected function doInitialize(): void
        {
            parent::doInitialize();

            $this->addField('name');
            $this->containsMany('Accounts', ['theirModel' => [Account::class]]);
        }
    }

    class Account extends Model
    {
        public $caption = ' ';

        protected function doInitialize(): void
        {
            parent::doInitialize();

            $this->addField('email', [
                'required' => true,
                'options' => [Multiline::OPTION_PRESETS => [Multiline::INPUT => ['icon' => 'envelope', 'type' => 'email']]],
            ]);
            $this->addField('password', [
                'required' => true,
                'options' => [Multiline::OPTION_PRESETS => [Multiline::INPUT => ['icon' => 'key', 'type' => 'password']]],
            ]);
            $this->addField('site', ['required' => true]);
            $this->addField('type', [
                'default' => 'user',
                'values' => ['user' => 'Regular User', 'admin' => 'System Admin'],
                'options' => [Multiline::OPTION_PRESETS => [Multiline::TABLE_CELL => ['width' => 'four']]],
            ]);
        }
    }
}

Crud::addTo($webpage)->setModel(new Client($webpage->db));
