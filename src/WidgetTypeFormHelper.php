<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeFormHelperInterface;
use CodeHqDk\MyDashboard\Interfaces\WidgetTypeSettings;
use CodeHqDk\MyDashboard\WidgetTester\SaveSettings\SaveSettingsProvider;

class WidgetTypeFormHelper implements WidgetTypeFormHelperInterface
{
    public function __construct(private WidgetProvider $widgetProvider, private readonly ProjectPaths $project_paths)
    {
    }

    public function insertCommonHiddenFormElements(): string
    {
        return '';
    }

    public function insertCommonSettingsFormElements(): string
    {
        // TODO: Implement insertCommonSettingsFormElements() method.
        return '';
    }

    public function insertPredefinedSettingsDropdown(): string
    {
        // TODO: Implement insertPredefinedSettingsDropdown() method.
        return '';
    }

    public function getSetupPageHead(): string
    {
        // TODO: Implement getSetupPageHead() method.
        return '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.conditional.slate.min.css" />';
    }

    public function getDesignHeader(): string
    {
        // TODO: Implement getDesignHeader() method.
        return '';
    }

    public function getTypeSpecificSettings(): ?WidgetTypeSettings
    {
        $settings_class = $this->widgetProvider->returnWidgetSettings();

        $file = $this->project_paths->getRunTimePath() . '/widget_settings.json';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $widget_type_specific_settings = json_decode($content, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } else {
            $widget_type_specific_settings = [];
        }

        return $settings_class::fromArray($widget_type_specific_settings);
    }

    public function getWidgetFormPostUrl(): string
    {
        return SaveSettingsProvider::SAVE_WIDGET_SETTINGS_URL;
    }
}