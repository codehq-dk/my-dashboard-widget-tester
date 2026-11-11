<?php

declare(strict_types=1);

namespace CodeHqDk\MyDashboard\WidgetTester\StartPage;

use CodeHqDk\MyDashboard\Interfaces\WidgetType;

class StartPageView
{
    private WidgetType $widgetType;

    public function setWidgetType(WidgetType $widgetType): void
    {
        $this->widgetType = $widgetType;
    }

    public function getWidgetType(): WidgetType
    {
        return $this->widgetType;
    }
}
