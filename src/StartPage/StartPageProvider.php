<?php

namespace CodeHqDk\MyDashboard\WidgetTester\StartPage;

use CodeHqDk\MyDashboard\Interfaces\Provider;
use League\Route\Router;
use Psr\Container\ContainerInterface;

class StartPageProvider implements Provider
{
    public const string START_PAGE_URL = '/';

    public function register(ContainerInterface $container): void
    {
        $routerWrapper = $container->get(Router::class);
        $routerWrapper->map('GET', self::START_PAGE_URL, StartPageController::class, null);
    }
}