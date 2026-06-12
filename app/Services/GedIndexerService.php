<?php

namespace App\Services;

use App\Models\GedIndex;

class GedIndexerService
{
    public function indexFolder($tipo, $basePath, $relative = '')
    {
        $path = $basePath;

        if ($relative) {
            $path .= '\\' . str_replace('/', '\\', $relative);
        }

        if (!is_dir($path)) return;

        $items = scandir($path);

        foreach ($items as $item) {

            if (in_array($item, ['.', '..'])) continue;

            $full = $path . '\\' . $item;

            $rel = trim($relative . '/' . $item, '/');

            $isFile = is_file($full);

            GedIndex::updateOrCreate([
                'tipo' => $tipo,
                'path' => $rel,
            ], [
                'nome' => $item,
                'is_file' => $isFile,
                'parent_path' => $relative ?: null,
            ]);

            if (is_dir($full)) {
                $this->indexFolder($tipo, $basePath, $rel);
            }
        }
    }
}