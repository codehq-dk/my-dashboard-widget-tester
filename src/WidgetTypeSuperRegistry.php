<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeFormView;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeView;
use Psr\Container\ContainerInterface;

readonly class WidgetTypeSuperRegistry
{
    public function __construct(private ContainerInterface $container, private WidgetProvider $widgetProvider)
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

    public function getWidgetTypeFormView(): ?WidgetTypeFormView
    {
        $widget_type_form_view_classname = $this->widgetProvider->returnWidgetFormView();
        return $this->container->get($widget_type_form_view_classname);
    }

    public function getWidgetTypeSettings()
    {
        $widget_type_settings_classname = $this->widgetProvider->returnWidgetSettings();
        return $this->container->get($widget_type_settings_classname);
    }
}