<?php

declare(strict_types=1);
/**
 * Behat test for ScopeBuilder
 * Test Model condition rendering into ScopeBuilder component.
 */

namespace Phlex\Ui\Demos;

use Phlex\Data\Model\Scope;
use Phlex\Data\Model\Scope\Condition;
use Phlex\Ui\Header;
use Phlex\Ui\View;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$model = new Stat($webpage->db, ['caption' => 'Demo Stat']);

$project = new Condition($model->key()->project_name, Condition::OPERATOR_REGEXP, '[a-zA-Z]');
$brazil = new Condition($model->key()->client_country_iso, '=', 'BR');
$start = new Condition($model->key()->start_date, '=', '2020-10-22');
$finish = new Condition($model->key()->finish_time, '!=', '22:22');
$isCommercial = new Condition($model->key()->is_commercial, '0');
$budget = new Condition($model->key()->project_budget, '>=', '1000');
$currency = new Condition($model->key()->currency, 'USD');

$scope = Scope::createAnd($project, $brazil, $start);
$orScope = Scope::createOr($finish, $isCommercial, $currency);

$model->addCondition($budget);
$model->scope()->add($scope);
$model->scope()->add($orScope);

$form = \Phlex\Ui\Form::addTo($webpage);

$form->addControl('qb', [\Phlex\Ui\Form\Control\ScopeBuilder::class, 'model' => $model]);

$form->onSubmit(function ($form) use ($model) {
    $message = $form->model->get('qb')->toWords($model);
    $view = (new \Phlex\Ui\View(['id' => false]))->addClass('atk-scope-builder-response');
    $view->initialize();

    $view->set($message);

    return $view;
});

$expectedWord = <<<'EOF'
    Project Budget is greater or equal to '1000'
    and (Project Name is regular expression '[a-zA-Z]'
    and Client Country Iso is equal to 'BR' ('Brazil') and Start Date is equal to '2020-10-22')
    and (Finish Time is not equal to '22:22' or Is Commercial is equal to '0' or Currency is equal to 'USD')
    EOF;

$expectedInput = <<< 'EOF'
    {
      "logicalOperator": "AND",
      "children": [
        {
          "type": "query-builder-rule",
          "query": {
            "rule": "phlex_fp_stat__project_budget",
            "operator": ">=",
            "value": "1000",
            "option": null
          }
        },
        {
          "type": "query-builder-group",
          "query": {
            "logicalOperator": "AND",
            "children": [
              {
                "type": "query-builder-rule",
                "query": {
                  "rule": "phlex_fp_stat__project_name",
                  "operator": "matches regular expression",
                  "value": "[a-zA-Z]",
                  "option": null
                }
              },
              {
                "type": "query-builder-rule",
                "query": {
                  "rule": "phlex_fp_stat__client_country_iso",
                  "operator": "equals",
                  "value": "BR",
                  "option": {
                    "key": "BR",
                    "text": "Brazil",
                    "value": "BR"
                  }
                }
              },
              {
                "type": "query-builder-rule",
                "query": {
                  "rule": "phlex_fp_stat__start_date",
                  "operator": "is on",
                  "value": "2020-10-22",
                  "option": null
                }
              }
            ]
          }
        },
        {
          "type": "query-builder-group",
          "query": {
            "logicalOperator": "OR",
            "children": [
              {
                "type": "query-builder-rule",
                "query": {
                  "rule": "phlex_fp_stat__finish_time",
                  "operator": "is not on",
                  "value": "22:22",
                  "option": null
                }
              },
              {
                "type": "query-builder-rule",
                "query": {
                  "rule": "phlex_fp_stat__is_commercial",
                  "operator": "equals",
                  "value": "0",
                  "option": null
                }
              },
              {
                "type": "query-builder-rule",
                "query": {
                  "rule": "phlex_fp_stat__currency",
                  "operator": "equals",
                  "value": "USD",
                  "option": null
                }
              }
            ]
          }
        }
      ]
    }
    EOF;

Header::addTo($webpage, ['Word:']);
View::addTo($webpage, ['element' => 'p', 'content' => $expectedWord])->addClass('atk-expected-word-result');

Header::addTo($webpage, ['Input:']);
View::addTo($webpage, ['element' => 'p', 'content' => $expectedInput])->addClass('atk-expected-input-result');
