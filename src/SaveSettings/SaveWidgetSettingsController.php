<?php

namespace CodeHqDk\MyDashboard\WidgetTester\SaveSettings;

use CodeHqDk\MyDashboard\WidgetTester\ProjectPaths;
use CodeHqDk\MyDashboard\WidgetTester\StartPage\StartPageProvider;
use CodeHqDk\MyDashboard\WidgetTester\WidgetTypeSuperRegistry;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SaveWidgetSettingsController
{
    public function __construct(
        private readonly Psr17Factory                $psr_17_factory,
        private readonly WidgetTypeSuperRegistry     $widget_type_super_registry,
        private readonly ProjectPaths                $project_paths
    ) {
    }

    protected function buildResponse(ServerRequestInterface $request): ResponseInterface
    {
        $widget_settings_class = $this->widget_type_super_registry->getWidgetTypeSettings();
        $widget_type_specific_settings = $widget_settings_class::fromArray($request->getParsedBody());
        if (!file_exists($this->project_paths->getRunTimePath())) {
            mkdir($this->project_paths->getRunTimePath(), 0777, true);
        }

        file_put_contents($this->project_paths->getRunTimePath() . '/widget_settings.json', json_encode($widget_type_specific_settings, JSON_PRETTY_PRINT));

        return $this->psr_17_factory->createResponse(303)->withHeader('Location', StartPageProvider::START_PAGE_URL);
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->buildResponse($request);
    }

}