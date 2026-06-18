<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class DocumentoController extends Controller
{
    public function setoresPartial($path = null)
    {
        $basePath = config('ged.roots.setores');

        // Se clicou em uma pasta, entra nela
        $currentPath = $path;

        $path = $basePath;

        if ($currentPath) {
            $path .= DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $currentPath);
        }

        $pastas = [];

        if (File::exists($path)) {
            $pastas = collect(File::directories($path))
                ->map(function ($dir) {
                    return basename($dir);
                })
                ->values();
        }

        $arquivos = [];

        if (File::exists($path)) {

            $pastas = collect(File::directories($path))
                ->map(fn ($dir) => basename($dir))
                ->values();

            $arquivos = collect(File::files($path))
                ->map(fn ($file) => $file->getFilename())
                ->values();
        }

        $breadcrumb = [];

        if ($currentPath) {

            $partes = explode('/', $currentPath);

            foreach ($partes as $indice => $parte) {

                $breadcrumb[] = [
                    'nome' => $parte,
                    'path' => implode('/', array_slice($partes, 0, $indice + 1)),
                ];
            }
        }

        $parentPath = null;

        if ($currentPath) {

            $partes = explode('/', $currentPath);

            array_pop($partes);

            $parentPath = implode('/', $partes);
        }

        return view('ged.partials.setores', [
            'roots' => $path,
            'pastas' => $pastas,
            'arquivos' => $arquivos,
            'current' => $currentPath,
            'breadcrumb' => $breadcrumb,
            'parentPath' => $parentPath,
        ]);
    }

    public function pesquisa(Request $request)
    {
        $termo = trim($request->get('q', ''));

        $resultados = [];

        if ($termo !== '') {

            $resultados = array_merge(
                $this->buscarArquivos(config('ged.roots.setores'), $termo, 'Setores'),
                $this->buscarArquivos(config('ged.roots.pf'), $termo, 'PF'),
                $this->buscarArquivos(config('ged.roots.pj'), $termo, 'PJ')
            );
        }

        return view('ged.partials.pesquisa', [
            'termo' => $termo,
            'resultados' => collect($resultados),
        ]);
    }

    private function buscarArquivos(string $raiz, string $termo, string $origem): array
    {
        $resultados = [];

        if (!is_dir($raiz)) {
            return $resultados;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $raiz,
                \FilesystemIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {

            $nome = $item->getFilename();

            if (stripos($nome, $termo) === false) {
                continue;
            }

            $resultados[] = [
                'nome' => $nome,
                'caminho' => $item->getPathname(),
                'origem' => $origem,
                'tipo' => $item->isDir() ? 'pasta' : 'arquivo',
            ];
        }

        return $resultados;
    }
}
