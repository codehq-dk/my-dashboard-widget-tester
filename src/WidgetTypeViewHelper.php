<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetTypeSettings;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeViewHelperInterface;

class WidgetTypeViewHelper implements WidgetTypeViewHelperInterface
{
    /**
     * @inheritDoc
     */
    public function getSettings(string $widget_type_settings_classname): ?WidgetTypeSettings
    {
        $content = file_get_contents(dirname(__DIR__) . '/runtime/widget_settings.json');

        $widget_type_specific_settings = json_decode($content, true);

        return $widget_type_settings_classname::fromArray($widget_type_specific_settings);
    }
}