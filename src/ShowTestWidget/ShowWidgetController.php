<?php

namespace CodeHqDk\MyDashboard\WidgetTester\ShowTestWidget;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeViewHelperInterface;
use CodeHqDk\MyDashboard\WidgetTester\Assets\AssetsImporter;
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
        private readonly WidgetTypeSuperRegistry    $widget_type_super_registry,
        private readonly WidgetTypeViewHelperInterface $widget_type_view_helper,
        private readonly AssetsImporter $assets_importer
    ) {
    }

    public function buildResponse(ServerRequestInterface $request): ResponseInterface
    {
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

        $css_file = '/assets/widgets/' . $widget_type_text . '/' . $this->assets_importer->getCssName();
        $js_file = '/assets/widgets/' . $widget_type_text . '/' . $this->assets_importer->getJsName();

        return '<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kontroltårnet widget tester</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Schoolbell&display=swap" rel="stylesheet">
        <style>
            body { width: 1920px; height: 1080px;}
        </style>
        <style>
        .widget{box-sizing:border-box;position:absolute;top:0;left:0;width:100%;height:100%;background-color:#dadada}.widget .widget__type-bagde{display:inline-block;background:rgba(0,0,0,.7);color:#fff;border-radius:16px;padding:10px 20px;font-size:1.2rem;font-weight:500;position:absolute;left:50%;bottom:30px;transform:translateX(-50%);box-shadow:0 2px 8px rgba(0,0,0,.08);letter-spacing:.03em}.widget-header{padding:5px;height:50px;display:flex;align-items:flex-start;gap:10px}.widget-header__logo{height:100%}.widget-header__title{font-size:1.7rem;font-weight:bold;vertical-align:top;overflow:hidden;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;margin:auto 0}.widget-body{padding:5px;height:50px}.widget-body img{height:100%}body{margin:0;padding:0}.dashboard{width:100%;height:100%}.dashboard__header{height:8%;width:100%;background-color:#758897;display:flex;align-items:center;justify-content:space-between;padding:15px;box-sizing:border-box}.dashboard__header__logo-and-name{flex:0%;white-space:nowrap;text-align:left;height:100%;display:flex;align-items:center}.dashboard__header__logo-and-name__logo{height:130%;margin-right:-2px;margin-left:-10px}.dashboard__header__logo-and-name__name{display:inline-block;color:#fff;font-size:3em;font-weight:bold;font-family:"Arial",serif;white-space:nowrap;filter:drop-shadow(2px 2px 2px #0c0c0c)}.dashboard__header__logo-and-name__news{text-align:center;display:inline-block;color:#fff;font-size:1em;font-weight:bold;font-family:"Arial",serif;width:100%;white-space:nowrap;filter:drop-shadow(2px 2px 2px #0c0c0c)}.dashboard__header__logo-and-name__news__time,.dashboard__header__logo-and-name__news__message{display:inline-block}.dashboard__header__qr-code__text{color:#fff;font-size:3em;font-weight:bold;font-family:"Arial",serif;white-space:nowrap;filter:drop-shadow(2px 2px 2px #0c0c0c);vertical-align:center;margin-right:10px;vertical-align:top}.dashboard__header__qr-code{display:inline-block;height:100%}.dashboard__header__qr-code img{height:100%}.dashboard__body.grid{box-sizing:border-box;padding:.5rem;height:91%;display:grid;grid-template-columns:repeat(4, 1fr);grid-template-rows:repeat(2, 1fr);gap:.5rem}.dashboard__body{height:91%}.dashboard__body__slot{overflow:hidden;position:relative;box-sizing:border-box;width:100%;height:100%}.dashboard__body__slot.cell{padding:2.4rem}.dashboard__footer{height:0%}.progress-bar{width:0;height:1%;background-color:#758897;line-height:1%}.dashboard__slot-loading-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:10}.dashboard__slot-loading-logo{width:50%;height:auto;max-width:300px;min-width:64px;animation:dashboard-logo-pulse 1.2s infinite ease-in-out;filter:drop-shadow(0 6px 18px rgba(23, 63, 63, 0.5333333333)) drop-shadow(0 2px 4px rgba(23, 63, 63, 0.2666666667));display:block;margin:0 auto}@keyframes dashboard-logo-pulse{0%{transform:scale(1);opacity:1}50%{transform:scale(1.15);opacity:.7}100%{transform:scale(1);opacity:1}}.fade-container{-webkit-mask-image:linear-gradient(to bottom, black 90%, transparent 100%);mask-image:linear-gradient(to bottom, black 90%, transparent 100%)}
        </style>
        <link rel="stylesheet" type="text/css" href="'.$css_file.'">
        <script src="'.$js_file.'"></script>  
    </head>
    <body>
<div class="dashboard">
    <div class="dashboard__header">
        <div class="dashboard__header__logo-and-name">
            <img class="dashboard__header__logo-and-name__logo" src="/logo-type2.png" alt="Kontroltårnet.dk">
            <span class="dashboard__header__logo-and-name__name">Kontroltårnet.dk</span>
            <span class="dashboard__header__logo-and-name__news">
            </span>
        </div>
    </div>
    <div class="dashboard__body grid">
        <div class="dashboard__body__slot cell" data-type="widget-list" data-widget-slot-id="1">
            <div class="widget">  
                ' . self::WIDGET_PLACEHOLDER . '
            </div>        
        </div>
    </div>
    <div class="dashboard__footer">
        <span></span>
    </div>
    <div class="progress-bar" id="progressBar"></div>
</div>
    </body>
</html>';
    }
}