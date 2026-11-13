<?php

namespace CodeHqDk\MyDashboard\WidgetTester;

class ProjectPaths
{
    public function __construct(private readonly string $project_path)
    {
    }

    public function getSrcPath(): string
    {
        return self::getProjectPath() . '/src';
    }

    public function getProjectPath(): string
    {
        return $this->project_path;
    }

    public function getRunTimePath(): string
    {
        return self::getProjectPath() . '/runtime';
    }

    public function getPublicPath(): string
    {
        return self::getProjectPath() . '/public';
    }
}