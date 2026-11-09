<?php

namespace CodeHqDk\MyDashboard\WidgetTester\ShowSettingsForm;

use CodeHqDk\MyDashboard\Interfaces\Provider;
use League\Route\Router;
use Psr\Container\ContainerInterface;

class ShowSettingsFormProvider implements Provider
{
    public const string SHOW_TEST_WIDGET_FORM = '/show-test-widget-settings-form';

    public function register(ContainerInterface $container): void
    {
        $routerWrapper = $container->get(Router::class);
        $routerWrapper->map('GET', self::SHOW_TEST_WIDGET_FORM, ShowWidgetTypeSettingsFormController::class, null);
    }
}