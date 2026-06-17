<?php

namespace App\Services;

class GedService
{
    public function root(string $tipo): ?string
    {
        return config("ged.roots.{$tipo}");
    }

    public function assertInsideRoot(string $tipo, string $path): bool
    {
        $base = $this->root($tipo);
        $full = $this->fullPath($tipo, $path);

        return $base && $full && str_starts_with($full, $base);
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

    public function assertSafePath(string $tipo, string $path = ''): string
    {
        if (str_contains($path, '..')) {
            abort(404);
        }

        $base = $this->root($tipo);

        if (!$base) {
            abort(404);
        }

        $full = $this->fullPath($tipo, $path);

        if (!$full || !str_starts_with($full, $base)) {
            abort(404);
        }

        return $full;
    }
}