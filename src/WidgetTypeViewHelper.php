<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetTypeSettings;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeViewHelperInterface;

class WidgetTypeViewHelper implements WidgetTypeViewHelperInterface
{
    public function __construct(private readonly ProjectPaths $project_paths)
    {
    }

    /**
     * @inheritDoc
     */
    public function getSettings(string $widget_type_settings_classname): ?WidgetTypeSettings
    {
        $file = $this->project_paths->getRunTimePath() . '/widget_settings.json';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $widget_type_specific_settings = json_decode($content, true);
        } else {
            $widget_type_specific_settings = [];
        }

        return $widget_type_settings_classname::fromArray($widget_type_specific_settings);
    }
}