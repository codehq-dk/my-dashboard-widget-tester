<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use CodeHqDk\MyDashboard\Widget\BlackboardWidget\BlackboardWidgetProvider;
use CodeHqDk\MyDashboard\WidgetTester\WidgetTester;

$widget_tester = new WidgetTester(BlackboardWidgetProvider::class);
$widget_tester->start();
