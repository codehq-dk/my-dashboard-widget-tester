<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetTypeSettings;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeViewHelperInterface;

class WidgetTypeViewHelper implements WidgetTypeViewHelperInterface
{
    private ?WidgetTypeSettings $settings = null;

    public function setSettings(WidgetTypeSettings $settings): void
    {
        $this->settings = $settings;
    }
    /**
     * @inheritDoc
     */
    public function getSettings(string $widget_type_settings_classname): ?WidgetTypeSettings
    {
        return $this->settings;
    }
}