<?php

namespace App\Console\Commands;

use App\Models\GedIndex;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;

class IndexarGed extends Command
{
    protected $signature = 'ged:indexar';

    protected $description = 'Indexa arquivos e pastas do GED';

    public function handle(): int
    {
        $this->info('Limpando índice anterior...');

        GedIndex::truncate();

        $roots = config('ged.roots');

        foreach ($roots as $tipo => $raiz) {

            $this->info("Indexando {$tipo}...");

            if (!is_dir($raiz)) {
                $this->warn("Diretório não encontrado: {$raiz}");
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $raiz,
                    FilesystemIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {

                $fullPath = $item->getPathname();

                $relativePath = str_replace(
                    $raiz . DIRECTORY_SEPARATOR,
                    '',
                    $fullPath
                );

                GedIndex::create([
                    'tipo' => $tipo,
                    'path' => $relativePath,
                    'nome' => $item->getFilename(),
                    'is_file' => $item->isFile(),
                    'parent_path' => dirname($relativePath) === '.'
                        ? null
                        : dirname($relativePath),
                ]);
            }
        }

        $this->info('Indexação concluída.');

        return self::SUCCESS;
    }
}