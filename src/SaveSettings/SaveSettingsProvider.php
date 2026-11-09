<?php

namespace CodeHqDk\MyDashboard\WidgetTester\SaveSettings;

use CodeHqDk\MyDashboard\Interfaces\Provider;
use League\Route\Router;
use Psr\Container\ContainerInterface;

class SaveSettingsProvider implements Provider
{
    public const string SAVE_WIDGET_SETTINGS_URL = '/save-widget-settings';
    public function register(ContainerInterface $container): void
    {
        $routerWrapper = $container->get(Router::class);
        $routerWrapper->map('POST', self::SAVE_WIDGET_SETTINGS_URL, SaveWidgetSettingsController::class, null);
    }

}