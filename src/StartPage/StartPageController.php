<?php

declare(strict_types=1);

namespace CodeHqDk\MyDashboard\WidgetTester\StartPage;

use NanoTemplate\Renderer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class StartPageController
{
    public function __construct(
        private readonly Psr17Factory               $psr_17_factory,
        private readonly StartPageView              $view,
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

        return $this->build20OkResponse(__NAMESPACE__, __DIR__, $this->view);
    }

    public function build20OkResponse(string $view_namespace, string $template_path, $view): ResponseInterface
    {
        $renderer = new Renderer($view_namespace, $template_path);
        $content = $renderer->capture($view);
        $response_body = $this->psr_17_factory->createStream($content);
        return $this->psr_17_factory->createResponse(200)->withBody($response_body);
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->buildResponse($request);
    }
}
