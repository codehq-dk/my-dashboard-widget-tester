<?php

namespace CodeHqDk\MyDashboard\WidgetTester\Assets;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;

class AssetsImporter
{
    public function __construct(private WidgetProvider $widget_provider)
    {
    }
}