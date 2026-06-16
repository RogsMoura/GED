<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\GedService;

class GedController extends Controller
{
    // EXPLORAR
    public function explorar(GedService $ged, $tipo, $path = '')
    {
        $path = urldecode($path);

        $search = request('search');
        $sort = request('sort', 'name_asc');
        $perPage = request('per_page', 100);
        $page = (int) request('page', 1);

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

        $dados = cache()->remember($cacheKey, now()->addHours(12), function () use ($caminho, $path) {

            $pastas = [];
            $arquivos = [];
            $carregarArquivos = !empty($path);

            foreach (new \FilesystemIterator($caminho, \FilesystemIterator::SKIP_DOTS) as $item) {

                $nome = $item->getFilename();

                if (in_array($nome, ['$RECYCLE.BIN', 'System Volume Information'])) {
                    continue;
                }

                if ($item->isDir()) {
                    $pastas[] = $nome;
                    continue;
                }

                if (!$carregarArquivos) {
                    continue;
                }

                if (
                    str_starts_with($nome, '~$') ||
                    in_array($nome, ['Thumbs.db', '.ppinfocache'])
                ) {
                    continue;
                }

                $arquivos[] = $nome;
            }

            $pastas = collect($pastas);

            $arquivos = collect($arquivos);

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

        if (empty($path)) {

            $arquivos = collect();

            // Pesquisa
            if ($search) {

                $pastas = $pastas->filter(function ($item) use ($search) {

                    return str_contains(
                        mb_strtolower($item),
                        mb_strtolower($search)
                    );

                });

            }

            // Ordenação
            switch ($sort) {

                case 'name_desc':
                    $pastas = $pastas->sortDesc();
                    break;

                default:
                    $pastas = $pastas->sort();
            }

            $pastas = $pastas->values();

            $total = $pastas->count();

            $pastas = new LengthAwarePaginator(
                $pastas->slice(($page - 1) * $perPage, $perPage)->values(),
                $total,
                $perPage,
                $page,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );
        }

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
        $arquivo = $ged->fullPath($tipo, urldecode($path));

        if (!$arquivo || !file_exists($arquivo)) {
            abort(404);
        }

        return response()->file(
            $arquivo,
            [
                'Content-Disposition' => 'inline; filename="' . basename($arquivo) . '"',
            ]
        );
    }

    //UPLOAD
    public function upload(Request $request, GedService $ged, $tipo)
    {
        $base = $ged->root($tipo);

        if (!$base) {
            abort(404);
        }

        $path = $request->input('path', '');

        $destino = $base;

        if ($path) {
            $destino .= '\\' . str_replace('/', '\\', $path);
        }

        try {

            if (!$request->hasFile('arquivos')) {
                return back()->with('error', 'Nenhum arquivo selecionado.');
            }

            foreach ($request->file('arquivos') as $arquivo) {

                $arquivo->move(
                    $destino,
                    $arquivo->getClientOriginalName()
                );
            }

            $this->limparCachePasta($tipo, $path, $ged);

            return back()->with('success', 'Arquivo(s) enviado(s) com sucesso.');

        } catch (\Throwable $e) {

            report($e);

            return back()->with('error', 'Erro ao enviar arquivo(s).');

        }
    }

    //DOWNLOAD
    public function download(GedService $ged, $tipo, $path)
    {
        $arquivo = $ged->fullPath($tipo, urldecode($path));

        if (!$arquivo || !file_exists($arquivo)) {
            abort(404);
        }

        return response()->download($arquivo);
    }

    //DELETE
    public function delete(Request $request, GedService $ged, $tipo)
    {
        $path = $request->input('path');

        $full = $ged->fullPath($tipo, $path);

        if (!$full || !file_exists($full)) {
            abort(404);
        }

        try {

            if (is_dir($full)) {
                $this->deleteRecursively($full);
            } elseif (is_file($full)) {
                unlink($full);
            }

            $pasta = dirname($path);

            if ($pasta === '.') {
                $pasta = '';
            }

            $this->limparCachePasta($tipo, $pasta, $ged);

            return back()->with('success', 'Item excluído com sucesso.');

        } catch (\Throwable $e) {

            report($e);

            return back()->with('error', 'Erro ao excluir o item.');

        }
    }
    
    //DELETE RECURSIVE
    private function deleteRecursively(string $caminho): void
    {
        if (is_file($caminho) || is_link($caminho)) {
            unlink($caminho);
            return;
        }

        $itens = array_diff(scandir($caminho), ['.', '..']);

        foreach ($itens as $item) {
            $this->deleteRecursively($caminho . DIRECTORY_SEPARATOR . $item);
        }

        rmdir($caminho);
    }

    //DELETE MULTIPLE
    public function deleteMultiple(Request $request, GedService $ged, $tipo)
    {
        $paths = $request->input('paths', []);

        if (empty($paths)) {
            return back()->with('error', 'Nenhum item selecionado.');
        }

        try {

            $pastasParaLimpar = [];

            foreach ($paths as $path) {

                $full = $ged->fullPath($tipo, $path);

                if (!$full || !file_exists($full)) {
                    continue;
                }

                if (is_dir($full)) {
                    $this->deleteRecursively($full);
                } else {
                    unlink($full);
                }

                $pasta = dirname($path);

                if ($pasta === '.') {
                    $pasta = '';
                }

                $pastasParaLimpar[$pasta] = true;
            }

            foreach (array_keys($pastasParaLimpar) as $pasta) {
                $this->limparCachePasta($tipo, $pasta, $ged);
            }

            return back()->with('success', 'Itens excluídos com sucesso.');

        } catch (\Throwable $e) {

            report($e);

            return back()->with('error', 'Erro ao excluir os itens.');

        }
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

        try {

            if (!file_exists($full)) {
                mkdir($full, 0777, true);
            }

            $cacheKey = "ged_{$tipo}_" . md5(dirname($full));

            cache()->forget($cacheKey);

            $this->limparCachePasta($tipo, $path, $ged);

            return back()->with('success', 'Pasta criada com sucesso.');

        } catch (\Throwable $e) {

            report($e);

            return back()->with('error', 'Erro ao criar a pasta.');

        }
    }

    //EDITAR
    public function rename(Request $request, GedService $ged, $tipo)
    {
        $old = $request->input('old');
        $new = trim($request->input('new'));

        $oldPath = $ged->fullPath($tipo, $old);

        if (!$oldPath || !file_exists($oldPath)) {
            abort(404);
        }

        $extensao = pathinfo($oldPath, PATHINFO_EXTENSION);

        $novoNome = $new;

        if ($extensao) {
            $novoNome .= '.' . $extensao;
        }

        $novoPath = dirname($oldPath) . DIRECTORY_SEPARATOR . $novoNome;

        try {

            rename($oldPath, $novoPath);

            $pasta = dirname($old);

            if ($pasta === '.') {
                $pasta = '';
            }

            $this->limparCachePasta($tipo, $pasta, $ged);

            return back()->with('success', 'Item renomeado com sucesso.');

        } catch (\Throwable $e) {

            report($e);

            return back()->with('error', 'Erro ao renomear o item.');

        }
    }

    //LIMPAR CACHE
    private function limparCachePasta(string $tipo, string $path, GedService $ged): void
    {
        $base = $ged->root($tipo);

        if (!$base) {
            return;
        }

        $caminho = $base;

        if ($path) {
            $caminho .= '\\' . str_replace('/', '\\', $path);
        }

        $cacheKey = "ged_{$tipo}_" . md5($caminho);

        cache()->forget($cacheKey);
    }
}