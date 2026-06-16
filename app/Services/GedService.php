<?php

namespace App\Services;

class GedService
{
    public function root(string $tipo): ?string
    {
        return config("ged.roots.{$tipo}");
    }

    public function fullPath($tipo, $path = '')
    {
        $base = $this->root($tipo);

        if (!$base) {
            return null;
        }

        $path = urldecode($path);

        if (str_contains($path, '..')) {
            return null;
        }

        return rtrim($base, '\\')
            . ($path ? '\\' . str_replace('/', '\\', $path) : '');
    }
}