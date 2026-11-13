<?php

namespace CodeHqDk\MyDashboard\WidgetTester\Assets;

use CodeHqDk\MyDashboard\Interfaces\WidgetProvider;
use CodeHqDk\MyDashboard\WidgetTester\ProjectPaths;
use Composer\InstalledVersions;

class AssetsImporter
{
    private ?string $css_name = null;
    private ?string $js_name = null;

    public function __construct(
        private WidgetProvider $widget_provider,
        private ProjectPaths $project_path,
        private bool $local_asset = true
    ) {
    }

    public function getCssName(): ?string
    {
        return $this->css_name;
    }

    public function getJsName(): ?string
    {
        return $this->js_name;
    }

    public function import(): void
    {
        $this->css_name = null;
        $this->js_name = null;

        $project_root = $this->project_path->getProjectPath();//InstalledVersions::getRootPackage()['install_path'];
        if ($this->local_asset) {
            $manifest = $this->readManifest($project_root . '/manifest.json');
            [$this->css_name, $this->js_name] = $this->getCssAndJsNames($manifest);
            return;
        }

        $package_path = $this->getWidgetPackagePath($project_root);
        $source_assets_path = $this->getSourceAssetsPath($package_path);
        $destination_path = $project_root . '/public/assets/widgets/' . $this->getWidgetTypeId();
        $manifest = $this->readManifest($package_path . '/manifest.json');
        [$css_name, $js_name] = $this->getCssAndJsNames($manifest);

        if (!is_dir($destination_path) && !mkdir($destination_path, 0777, true) && !is_dir($destination_path)) {
            throw new \RuntimeException(sprintf('Unable to create widget asset directory: %s', $destination_path));
        }

        if (!copy($package_path . '/manifest.json', $destination_path . '/manifest.json')) {
            throw new \RuntimeException(sprintf('Unable to copy widget asset manifest: %s', $package_path . '/manifest.json'));
        }

        $this->copyManifestAssets($manifest, $source_assets_path, $destination_path);
        $this->copyAssetDirectory($source_assets_path, $destination_path, 'images', 'image');
        $this->copyAssetDirectory($source_assets_path, $destination_path, 'icon', 'icon');

        $this->css_name = $css_name;
        $this->js_name = $js_name;
    }

    private function getWidgetTypeId(): string
    {
        $widget_type_classname = $this->widget_provider->returnWidgetType();
        $widget_type_id = $widget_type_classname::getTypeId();

        if ($widget_type_id === '' || $widget_type_id === '.' || $widget_type_id === '..'
            || str_contains($widget_type_id, '/') || str_contains($widget_type_id, '\\')) {
            throw new \RuntimeException('The widget type ID must be a valid directory name.');
        }

        return $widget_type_id;
    }

    private function getWidgetPackagePath(string $project_root): string
    {
        $package_vendor = $this->widget_provider->returnComposerPackageVendorName();
        $package_name = $this->widget_provider->returnComposerPackageName();

        if ($package_vendor === '' || $package_vendor === '.' || $package_vendor === '..'
            || $package_name === '' || $package_name === '.' || $package_name === '..'
            || str_contains($package_vendor, '/') || str_contains($package_vendor, '\\')
            || str_contains($package_name, '/') || str_contains($package_name, '\\')) {
            throw new \RuntimeException('The widget provider must return valid Composer package coordinates.');
        }

        return $project_root . '/vendor/' . $package_vendor . '/' . $package_name;
    }

    private function readManifest(string $manifest_path): array
    {
        if (!is_file($manifest_path)) {
            throw new \RuntimeException(sprintf('Widget asset manifest not found: %s', $manifest_path));
        }

        $manifest_contents = file_get_contents($manifest_path);
        if ($manifest_contents === false) {
            throw new \RuntimeException(sprintf('Unable to read widget asset manifest: %s', $manifest_path));
        }

        try {
            $manifest = json_decode($manifest_contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \RuntimeException(sprintf('Invalid widget asset manifest: %s', $manifest_path), 0, $exception);
        }

        if (!is_array($manifest)) {
            throw new \RuntimeException(sprintf('Widget asset manifest must contain a JSON object: %s', $manifest_path));
        }

        return $manifest;
    }

    private function getSourceAssetsPath(string $package_path): string
    {
        $source_assets_path = realpath($package_path . '/public/assets/widgets/' . $this->getWidgetTypeId());
        if ($source_assets_path === false) {
            throw new \RuntimeException(sprintf('Widget public assets directory not found: %s/public/assets', $package_path));
        }

        return $source_assets_path;
    }

    private function getCssAndJsNames(array $manifest): array
    {
        $css_name = null;
        $js_name = null;
        foreach ($manifest as $asset_path) {
            $this->validateManifestAssetPath($asset_path);

            $asset_extension = strtolower(pathinfo($asset_path, PATHINFO_EXTENSION));
            if ($asset_extension === 'css') {
                if ($css_name !== null) {
                    throw new \RuntimeException('Widget asset manifest contains more than one CSS file.');
                }
                $css_name = $asset_path;
            } elseif ($asset_extension === 'js') {
                if ($js_name !== null) {
                    throw new \RuntimeException('Widget asset manifest contains more than one JavaScript file.');
                }
                $js_name = $asset_path;
            }
        }

        if ($css_name === null || $js_name === null) {
            throw new \RuntimeException('Widget asset manifest must contain one CSS file and one JavaScript file.');
        }

        return [$css_name, $js_name];
    }

    private function validateManifestAssetPath(mixed $asset_path): void
    {
        if (!is_string($asset_path) || $asset_path === '' || str_starts_with($asset_path, '/')
            || str_contains($asset_path, '\\')
            || in_array('..', explode('/', $asset_path), true)) {
            throw new \RuntimeException('Widget asset manifest contains an invalid asset path.');
        }
    }

    private function copyManifestAssets(array $manifest, string $source_assets_path, string $destination_path): void
    {
        foreach ($manifest as $asset_path) {
            $source_path = realpath($source_assets_path . '/' . $asset_path);
            if ($source_path === false || !str_starts_with($source_path, $source_assets_path . DIRECTORY_SEPARATOR)
                || !is_file($source_path)) {
                throw new \RuntimeException(sprintf('Widget asset file not found or outside public/assets: %s', $asset_path));
            }

            $target_path = $destination_path . '/' . $asset_path;
            $target_directory = dirname($target_path);
            if (!is_dir($target_directory) && !mkdir($target_directory, 0777, true) && !is_dir($target_directory)) {
                throw new \RuntimeException(sprintf('Unable to create widget asset directory: %s', $target_directory));
            }

            if (!copy($source_path, $target_path)) {
                throw new \RuntimeException(sprintf('Unable to copy widget asset: %s', $asset_path));
            }
        }
    }

    private function copyAssetDirectory(
        string $source_assets_path,
        string $destination_path,
        string $directory_name,
        string $asset_name
    ): void
    {
        $source_directory_path = $source_assets_path . '/' . $directory_name;
        if (is_dir($source_directory_path)) {
            $resolved_directory_path = realpath($source_directory_path);
            if ($resolved_directory_path === false
                || !str_starts_with($resolved_directory_path, $source_assets_path . DIRECTORY_SEPARATOR)) {
                throw new \RuntimeException(sprintf('Widget %s directory must be inside public/assets.', $directory_name));
            }

            $this->copyAssetFiles(
                $resolved_directory_path,
                $destination_path . '/' . $directory_name,
                $directory_name,
                $asset_name
            );
        }
    }

    private function copyAssetFiles(
        string $source_path,
        string $destination_path,
        string $directory_name,
        string $asset_name
    ): void
    {
        if (!is_dir($destination_path) && !mkdir($destination_path, 0777, true) && !is_dir($destination_path)) {
            throw new \RuntimeException(sprintf('Unable to create widget asset directory: %s', $destination_path));
        }

        $entries = scandir($source_path);
        if ($entries === false) {
            throw new \RuntimeException(sprintf('Unable to read widget %s directory: %s', $directory_name, $source_path));
        }

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $source_entry = $source_path . '/' . $entry;
            if (is_link($source_entry)) {
                throw new \RuntimeException(sprintf('Widget %s directory contains a symbolic link: %s', $directory_name, $source_entry));
            }

            if (is_dir($source_entry)) {
                continue;
            }

            if (is_file($source_entry)) {
                if (!copy($source_entry, $destination_path . '/' . $entry)) {
                    throw new \RuntimeException(sprintf('Unable to copy widget %s: %s', $asset_name, $source_entry));
                }
            } else {
                throw new \RuntimeException(sprintf('Unsupported entry in widget %s directory: %s', $directory_name, $source_entry));
            }
        }
    }
}