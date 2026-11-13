<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use CodeHqDk\MyDashboard\Widget\BlackboardWidget\BlackboardWidgetProvider;
use CodeHqDk\MyDashboard\WidgetTester\ProjectPaths;
use CodeHqDk\MyDashboard\WidgetTester\WidgetTester;

$project_path = new ProjectPaths(dirname(__DIR__));
$widget_tester = new WidgetTester(
    BlackboardWidgetProvider::class,
    $project_path,
    false);
$widget_tester->start();
