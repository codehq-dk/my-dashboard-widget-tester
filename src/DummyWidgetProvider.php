<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;

class DummyWidgetProvider implements WidgetProvider
{

    /**
     * @inheritDoc
     */
    public function returnComposerPackageVendorName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function returnComposerPackageName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function returnWidgetType(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function returnWidgetView(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function returnWidgetSettings(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function returnWidgetFormView(): string
    {
        return '';
    }
}