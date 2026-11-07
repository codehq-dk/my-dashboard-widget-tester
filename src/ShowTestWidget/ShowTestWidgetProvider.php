<?php

namespace CodeHqDk\MyDashboard\WidgetTester\ShowTestWidget;

use CodeHqDk\MyDashboard\Interfaces\Provider;
use League\Route\Router;
use Psr\Container\ContainerInterface;

class ShowTestWidgetProvider implements Provider
{
    public const string SHOW_TEST_WIDGET = '/show-test-widget';

    public function register(ContainerInterface $container): void
    {
        $routerWrapper = $container->get(Router::class);
        $routerWrapper->map('GET', self::SHOW_TEST_WIDGET, ShowWidgetController::class, null);
    }
}