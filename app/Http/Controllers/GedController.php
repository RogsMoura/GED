<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GedService;

class GedController extends Controller
{
    // EXPLORAR
    public function explorar(GedService $ged, $tipo, $path = '')
    {
        $path = urldecode($path);

        $base = $ged->root($tipo);

        if (!$base) {
            abort(404);
        }

        $caminho = $base;

        if ($path) {
            $caminho .= '\\' . str_replace('/', '\\', $path);
        }

        if (!is_dir($caminho)) {
            abort(404);
        }

        $parts = $path ? explode('/', $path) : [];

        $breadcrumb = collect($parts)->map(function ($part, $index) use ($parts, $tipo) {

            return [
                'nome' => $part,
                'url' => url("/ged/{$tipo}/" . implode('/', array_slice($parts, 0, $index + 1)))
            ];

        });

        $cacheKey = "ged_{$tipo}_" . md5($caminho);

        $dados = cache()->remember($cacheKey, 120, function () use ($caminho) {

            $itens = scandir($caminho);

            $pastas = collect($itens)
                ->reject(fn ($i) => in_array($i, ['.', '..', '$RECYCLE.BIN', 'System Volume Information']))
                ->filter(fn ($i) => is_dir($caminho . '\\' . $i))
                ->values()
                ->map(fn ($i) => (string) $i);

            $arquivos = collect($itens)
                ->reject(fn ($i) => in_array($i, ['.', '..']))
                ->reject(fn ($i) => str_starts_with($i, '~$'))
                ->reject(fn ($i) => in_array($i, ['Thumbs.db', '.ppinfocache']))
                ->filter(fn ($i) => is_file($caminho . '\\' . $i))
                ->values()
                ->map(fn ($i) => (string) $i);

            return [
                'pastas' => $pastas->toArray(),
                'arquivos' => $arquivos->toArray(),
            ];

        });

        $pastas = collect($dados['pastas']);

        $arquivos = collect($dados['arquivos']);

        $labels = [
            'pf' => 'Pessoa Física',
            'pj' => 'Pessoa Jurídica',
            'setores' => 'Setores',
        ];

        $titulo = $labels[$tipo] ?? strtoupper($tipo);

        return view('ged.explorador', [
            'titulo' => $titulo,
            'tipo' => $tipo,
            'path' => $path,
            'pastas' => $pastas,
            'arquivos' => $arquivos,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    // ARQUIVO
    public function arquivo(GedService $ged, $tipo, $path)
    {
        $arquivo = $ged->fullPath($tipo, $path);

        if (!$arquivo) {
            abort(404);
        }

        return response()->file($arquivo);
    }

    //UPLOAD
    public function upload(Request $request, GedService $ged, $tipo)
    {
        $path = $request->input('path', '');

        $base = $ged->root($tipo);

        if (!$base) {
            abort(404);
        }

        $destino = $base . '\\' . str_replace('/', '\\', $path);

        if ($request->hasFile('arquivo')) {

            $file = $request->file('arquivo');

            $file->move(
                $destino,
                $file->getClientOriginalName()
            );

            $cacheKey = "ged_{$tipo}_" . md5($destino);

            cache()->forget($cacheKey);
        }

        return back();
    }

    //DOWNLOAD
    public function download(GedService $ged, $tipo, $path)
    {
        $arquivo = $ged->fullPath($tipo, $path);

        if (!$arquivo) {
            abort(404);
        }

        return response()->download($arquivo);
    }

    public function delete(Request $request, GedService $ged, $tipo)
    {
        $path = $request->input('path');

        $full = $ged->fullPath($tipo, $path);

        if (!$full) {
            abort(404);
        }

        $pastaPai = dirname($full);

        if (is_dir($full)) {
            rmdir($full);
        }

        if (is_file($full)) {
            unlink($full);
        }

        $cacheKey = "ged_{$tipo}_" . md5($pastaPai);

        cache()->forget($cacheKey);

        return back();
    }

    //CRIAR PASTA
    public function createFolder(Request $request, GedService $ged, $tipo)
    {
        $base = $ged->root($tipo);

        if (!$base) {
            abort(404);
        }

        $path = $request->input('path', '');
        $nome = trim($request->input('nome'));

        $full = $base
            . '\\'
            . str_replace('/', '\\', $path)
            . '\\'
            . $nome;

        if (!file_exists($full)) {
            mkdir($full, 0777, true);
        }

        $cacheKey = "ged_{$tipo}_" . md5(dirname($full));

        cache()->forget($cacheKey);

        return back();
    }

    //EDITAR
    public function rename(Request $request, GedService $ged, $tipo)
    {
        $base = $ged->root($tipo);

        if (!$base) {
            abort(404);
        }

        $old = $request->input('old');
        $new = $request->input('new');

        $oldPath = $base . '\\' . str_replace('/', '\\', $old);
        $newPath = $base . '\\' . str_replace('/', '\\', $new);

        rename($oldPath, $newPath);

        $cacheKey = "ged_{$tipo}_" . md5(dirname($oldPath));

        cache()->forget($cacheKey);

        return back();
    }
}