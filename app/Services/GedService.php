<?php

namespace App\Services;

class GedService
{
    public function root($tipo)
    {
        return config("ged.roots.{$tipo}");
    }

    public function fullPath($tipo, $path = '')
    {
        $base = $this->root($tipo);

        if (!$base) {
            return null;
        }

        $path = trim(urldecode($path));

        if ($path === '') {
            return $base;
        }

        $path = str_replace('/', '\\', $path);

        if (str_contains($path, '..')) {
            return null;
        }

        return $base . '\\' . ltrim($path, '\\');
    }
}