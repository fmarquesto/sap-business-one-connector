<?php

use fmarquesto\SapBusinessOneConnector\Entities\Items;

require_once __DIR__. '/../vendor/autoload.php';
require_once __DIR__. '/Entities/Items.php';

$items = new Items();
$items->setSelect('BarCode');
$items->setMultipleSelect(['IssueMethod','SalesUnitHeight1']);
var_dump($items-> getFirstByFilter("BarCode gt '1'"));die;
var_dump($items->getOneByKey('21386'));
