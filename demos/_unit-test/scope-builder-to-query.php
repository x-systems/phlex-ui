<?php

declare(strict_types=1);
/**
 * Behat test for ScopeBuilder
 * Test query output by ScopeBuilder into model scope definition.
 */

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form\Control\ScopeBuilder;
use Phlex\Ui\Grid;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

$query = <<<'EOF'
    {
      "logicalOperator": "AND",
      "children": [
        {
          "type": "query-builder-rule",
          "query": {
            "rule": "atk_fp_product__product_category_id",
            "operator": "equals",
            "operand": "Product Category Id",
            "value": "3"
          }
        },
        {
          "type": "query-builder-rule",
          "query": {
            "rule": "atk_fp_product__product_sub_category_id",
            "operator": "equals",
            "operand": "Product Sub Category Id",
            "value": "6"
          }
        }
      ]
    }
    EOF;

$q = $app->decodeJson($query);
$scope = ScopeBuilder::queryToScope($q);

$product = new Product($app->db);

$g = Grid::addTo($app);
$g->setModel($product->addCondition($scope));
