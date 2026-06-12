<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <h1 class="text-2xl font-bold mb-4">
                {{ $titulo }}
            </h1>

            {{-- Breadcrumb --}}
            <div class="mb-6 text-sm text-gray-600">

                <a href="/ged/{{ $tipo }}"
                   class="text-blue-600 hover:underline">
                    🏠 {{ $titulo }}
                </a>

                @foreach($breadcrumb as $item)

                    <span class="mx-1">→</span>

                    <a href="{{ $item['url'] }}"
                       class="text-blue-600 hover:underline">
                        {{ $item['nome'] }}
                    </a>

                @endforeach

            </div>

            {{-- Ações --}}
            <div class="grid md:grid-cols-2 gap-4 mb-6">

                {{-- Upload --}}
                <div class="border rounded-lg p-4">

                    <h3 class="font-semibold mb-3">
                        Upload de Arquivo
                    </h3>

                    <form method="POST"
                          enctype="multipart/form-data"
                          action="/ged/{{ $tipo }}/upload">

                        @csrf

                        <input type="hidden"
                               name="path"
                               value="{{ $path }}">

                        <input type="file"
                               name="arquivo"
                               class="mb-2 w-full border rounded p-2">

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Upload
                        </button>

                    </form>

                </div>

                {{-- Criar Pasta --}}
                <div class="border rounded-lg p-4">

                    <h3 class="font-semibold mb-3">
                        Criar Pasta
                    </h3>

                    <form method="POST"
                          action="/ged/{{ $tipo }}/folder">

                        @csrf

                        <input type="hidden"
                               name="path"
                               value="{{ $path }}">

                        <input type="text"
                               name="nome"
                               placeholder="Nome da pasta"
                               class="mb-2 w-full border rounded p-2">

                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Criar Pasta
                        </button>

                    </form>

                </div>

            </div>

            {{-- Pastas --}}
            <div class="mb-8">

                <h2 class="font-bold text-lg mb-3">
                    📁 Pastas
                </h2>

                <div class="space-y-2">

                    @forelse($pastas as $pasta)

                        <div class="flex items-center justify-between border rounded p-3">

                            <a href="/ged/{{ $tipo }}/{{ $path ? $path.'/' : '' }}{{ urlencode($pasta) }}"
                               class="text-blue-600 hover:underline">

                                📁 {{ $pasta }}

                            </a>

                            <form method="POST"
                                  action="/ged/{{ $tipo }}/delete">

                                @csrf
                                @method('DELETE')

                                <input type="hidden"
                                       name="path"
                                       value="{{ $path ? $path.'/' : '' }}{{ $pasta }}">

                                <button
                                    onclick="return confirm('Excluir pasta?')"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                    Excluir

                                </button>

                            </form>

                        </div>

                    @empty

                        <p class="text-gray-500">
                            Nenhuma pasta encontrada.
                        </p>

                    @endforelse

                </div>

            </div>

            {{-- Arquivos --}}
            <div>

                <h2 class="font-bold text-lg mb-3">
                    📄 Arquivos
                </h2>

                <div class="space-y-2">

                    @forelse($arquivos as $arquivo)

                        <div class="flex items-center justify-between border rounded p-3">

                            <span>
                                📄 {{ $arquivo }}
                            </span>

                            <div class="flex gap-2">

                                <a href="/ged-arquivo/{{ $tipo }}/{{ $path ? $path.'/' : '' }}{{ urlencode($arquivo) }}"
                                   class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">

                                    Abrir

                                </a>

                                <a href="/ged-download/{{ $tipo }}/{{ $path ? $path.'/' : '' }}{{ urlencode($arquivo) }}"
                                   class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">

                                    Download

                                </a>

                                <form method="POST"
                                      action="/ged/{{ $tipo }}/delete">

                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden"
                                           name="path"
                                           value="{{ $path ? $path.'/' : '' }}{{ $arquivo }}">

                                    <button
                                        onclick="return confirm('Excluir arquivo?')"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                        Excluir

                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <p class="text-gray-500">
                            Nenhum arquivo encontrado.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</x-app-layout>