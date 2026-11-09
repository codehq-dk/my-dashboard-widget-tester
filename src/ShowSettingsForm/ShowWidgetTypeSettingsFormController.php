<?php

namespace CodeHqDk\MyDashboard\WidgetTester\ShowSettingsForm;

use CodeHqDk\MyDashboard\WidgetTester\WidgetTypeSuperRegistry;
use Exception;
use NanoTemplate\Renderer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;

class ShowWidgetTypeSettingsFormController
{
    public function __construct(
        private readonly Psr17Factory               $psr_17_factory,
        private readonly WidgetTypeSuperRegistry   $widget_type_super_registry,
    ) {
    }

    protected function buildResponse(ServerRequestInterface $request): ResponseInterface
    {
        $widget_type_form_view = $this->widget_type_super_registry->getWidgetTypeFormView();

        $view_namespace = $this->getNameSpaceFromObject($widget_type_form_view);
        $template_path = $this->getFilePathFromObject($widget_type_form_view);

        return $this->build20OkResponse($view_namespace, $template_path, $widget_type_form_view);
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->buildResponse($request);
    }

    public function getFilePathFromObject(Object $object): string
    {
        $ref  = new ReflectionClass($object);
        $file = $ref->getFileName(); // string eller false (for interne klasser)

        if ($file === false) {
            throw new Exception('Cannot find file of object' . $object);
        }

        return dirname($file);
    }

    public function getNameSpaceFromObject(Object $object): string
    {
        $ref = new ReflectionClass($object);
        return $ref->getNamespaceName();
    }

    public function build20OkResponse(string $view_namespace, string $template_path, $view): ResponseInterface
    {
        $renderer = new Renderer($view_namespace, $template_path);
        $content = $renderer->capture($view);
        $response_body = $this->psr_17_factory->createStream($content);
        return $this->psr_17_factory->createResponse(200)->withBody($response_body);
    }
}