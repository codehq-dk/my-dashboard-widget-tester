<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeView;
use Psr\Container\ContainerInterface;

class WidgetTypeSuperRegistry
{
    public function __construct(private readonly ContainerInterface $container, private readonly WidgetProvider $widgetProvider)
    {
    }

    public function getWidgetTypeView(string $widget_type_id = ''): WidgetTypeView
    {
        $widget_type_view_classname = $this->widgetProvider->returnWidgetView();
        return $this->container->get($widget_type_view_classname);
    }

    public function getWidgetType(): string
    {
        $widget_type_classname = $this->widgetProvider->returnWidgetType();
        return $widget_type_classname::getTypeId();
    }
}