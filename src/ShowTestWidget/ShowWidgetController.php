<?php

namespace CodeHqDk\MyDashboard\WidgetTester\ShowTestWidget;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeViewHelperInterface;
use CodeHqDk\MyDashboard\WidgetTester\StartPage\StartPageView;
use CodeHqDk\MyDashboard\WidgetTester\WidgetTypeSuperRegistry;
use Exception;
use NanoTemplate\Renderer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;

class ShowWidgetController
{
    private const WIDGET_PLACEHOLDER = "<<<test-widget>>>";

    public function __construct(
        private readonly Psr17Factory               $psr_17_factory,
        private readonly StartPageView              $view,
        private readonly WidgetProvider             $widget_provider,
        private readonly WidgetTypeSuperRegistry    $widget_type_super_registry,
        private readonly WidgetTypeViewHelperInterface $widget_type_view_helper
//        private readonly ProjectPaths               $project_paths,
//        private readonly Document                   $dom,
//        private readonly AssetHandler               $assets,
//        private readonly CommonControllerResponses  $common_controller_responses
    ) {
    }

    public function buildResponse(ServerRequestInterface $request): ResponseInterface
    {
//        $this->dom->addCss($this->assets->getUrl('start-page.css'));
//        $this->dom->addJs($this->assets->getUrl('start-page.js'));
        $widget_view = $this->widget_provider->returnWidgetView();
        $widget_view = $this->widget_type_super_registry->getWidgetTypeView();
        $widget_view->setHelper($this->widget_type_view_helper);

        $view_namespace = $this->getNameSpaceFromObject($widget_view);
        $template_path = $this->getFilePathFromObject($widget_view);

        return $this->build20OkResponse($view_namespace, $template_path, $widget_view);
    }

    public function build20OkResponse(string $view_namespace, string $template_path, $view): ResponseInterface
    {
        $renderer = new Renderer($view_namespace, $template_path);
        $content = $renderer->capture($view);

        $html_page = $this->getBasicHtml5Page();
        $html_page = str_replace(self::WIDGET_PLACEHOLDER, $content, $html_page);

        $response_body = $this->psr_17_factory->createStream($html_page);
        return $this->psr_17_factory->createResponse(200)->withBody($response_body);
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

    private function getBasicHtml5Page(): string
    {
        $widget_type_text = $this->widget_type_super_registry->getWidgetType();

        return '<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HTML5 Boilerplate</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <h1>' . $widget_type_text . '</h1>
  <script src="scripts.js"></script>
  ' . self::WIDGET_PLACEHOLDER . '
</body>

</html>';
    }
}