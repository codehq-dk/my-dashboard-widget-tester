<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeFormHelperInterface;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeViewHelperInterface;
use CodeHqDk\MyDashboard\WidgetTester\Assets\AssetsImporter;
use CodeHqDk\MyDashboard\WidgetTester\SaveSettings\SaveSettingsProvider;
use CodeHqDk\MyDashboard\WidgetTester\ShowSettingsForm\ShowSettingsFormProvider;
use CodeHqDk\MyDashboard\WidgetTester\ShowTestWidget\ShowTestWidgetProvider;
use CodeHqDk\MyDashboard\WidgetTester\StartPage\StartPageProvider;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Slince\Di\Container;

class WidgetTester
{
    public function __construct(private string $widget_provider_classname, private bool $local_assets = true)
    {
    }

    public function start(): void
    {
        // Dependency Injection container configuration
        $container = new Container();

        // Request setup
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        $server_request = $creator->fromGlobals();

        // New Router configuration
        $container->register(Router::class, function (ContainerInterface $container) {
            $new_router = new Router();
            $strategy = new ApplicationStrategy();
            $strategy->setContainer($container); // Container is set here to allow class name in route mapping - see below
            $new_router->setStrategy($strategy);
            return $new_router;
        });

        new StartPageProvider()->register($container);
        new ShowTestWidgetProvider()->register($container);
        new ShowSettingsFormProvider()->register($container);
        new SaveSettingsProvider()->register($container);

        $container->register($this->widget_provider_classname, null);
        $container->register(WidgetProvider::class, $this->widget_provider_classname);
        $widget_provider = $container->get(WidgetProvider::class);

        /** @var Container $container */
        $container->register(WidgetTypeSuperRegistry::class, function() use ($container, $widget_provider) {
            return new WidgetTypeSuperRegistry($container, $widget_provider);
        });

        $container->register(WidgetTypeFormHelper::class, function() use ($widget_provider) {
            return new WidgetTypeFormHelper($widget_provider);
        });

        $container->register(WidgetTypeViewHelper::class, null);
        $container->register(WidgetTypeViewHelperInterface::class, WidgetTypeViewHelper::class);

        $container->register(WidgetTypeFormHelper::class, null);
        $container->register(WidgetTypeFormHelperInterface::class, WidgetTypeFormHelper::class);


        $assets_importer = new AssetsImporter($widget_provider, $this->local_assets);
        $assets_importer->import();
        $container->register(AssetsImporter::class, $assets_importer);

        /** @var Router $router */
        $router = $container->get(Router::class);

        $response = $router->dispatch($server_request);

        $emitter =  new SapiEmitter();
        $emitter->emit($response);
    }
}