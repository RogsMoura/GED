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

                @if(!empty($path))
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

                            <input
                                type="file"
                                name="arquivos[]"
                                multiple
                                class="mb-2 w-full border rounded p-2">

                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">

                                ⬆️ Upload

                            </button>

                        </form>

                    </div>
                @endif

                {{-- Criar Pasta --}}
                <div class="border rounded-lg p-4">

                    <h3 class="font-semibold mb-3">
                        📁 Nova Pasta
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

            <form method="POST" action="/ged/{{ $tipo }}/delete-multiple">

                @csrf
                @method('DELETE')

                <div class="flex items-center justify-between mb-4">

                    <label class="flex items-center gap-2">

                        <input type="checkbox" id="selectAll">

                        <span>Selecionar todos</span>

                    </label>

                    <button
                        id="deleteSelectedBtn"
                        type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">

                        🗑️ Excluir selecionados

                    </button>

                </div>

                {{-- Pastas --}}
                <div class="mb-8">

                    <h2 class="font-bold text-lg mb-3">
                        📁 Pastas
                    </h2>

                    <div class="space-y-2">

                    @forelse($pastas as $pasta)

                        <div class="flex items-center justify-between border rounded p-3">

                            <div class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="paths[]"
                                    value="{{ $path ? $path.'/' : '' }}{{ $pasta }}">

                                <a href="/ged/{{ $tipo }}/{{ $path ? $path.'/' : '' }}{{ urlencode($pasta) }}"
                                class="text-blue-600 hover:underline">

                                    📁 {{ $pasta }}

                                </a>

                            </div>

                            <div class="flex items-center gap-2">

                                <button
                                    type="button"
                                    title="Renomear"
                                    data-path="{{ $path ? $path.'/' : '' }}{{ $pasta }}"
                                    data-name="{{ $pasta }}"
                                    onclick="renomearItem(this)"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">

                                    ✏️

                                </button>

                                <button
                                    type="button"
                                    title="Excluir"
                                    data-path="{{ $path ? $path.'/' : '' }}{{ $pasta }}"
                                    onclick="excluirItem(this.dataset.path)"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                    🗑️

                                </button>

                            </div>

                        </div>

                        @empty

                        <p class="text-gray-500">
                            Nenhuma pasta encontrada.
                        </p>

                        @endforelse

                    </div>

                </div>

                @if(!empty($path))
                    {{-- Arquivos --}}
                    <div>

                        <h2 class="font-bold text-lg mb-3">
                            📄 Arquivos
                        </h2>

                        <div class="space-y-2">

                            @forelse($arquivos as $arquivo)

                            <div class="flex items-center justify-between border rounded p-3">

                                <div class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="paths[]"
                                        value="{{ $path ? $path.'/' : '' }}{{ $arquivo }}">

                                    <span>
                                        📄 {{ $arquivo }}
                                    </span>

                                </div>

                                <div class="flex items-center gap-2">

                                    <a href="/ged-arquivo/{{ $tipo }}/{{ $path ? $path.'/' : '' }}{{ urlencode($arquivo) }}"
                                        target="_blank"
                                        title="Visualizar"
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">

                                            👁️
                                    </a>

                                    <a href="/ged-download/{{ $tipo }}/{{ $path ? $path.'/' : '' }}{{ urlencode($arquivo) }}"
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">

                                            ⬇️

                                    </a>

                                    <button
                                        type="button"
                                        title="Renomear"
                                        data-path="{{ $path ? $path.'/' : '' }}{{ $arquivo }}"
                                        data-name="{{ pathinfo($arquivo, PATHINFO_FILENAME) }}"
                                        onclick="renomearItem(this)"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">

                                        ✏️

                                    </button>

                                    <button
                                        type="button"
                                        title="Excluir"
                                        data-path="{{ $path ? $path.'/' : '' }}{{ $arquivo }}"
                                        onclick="excluirItem(this.dataset.path)"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                        🗑️

                                    </button>

                                </div>

                            </div>

                            @empty

                            <p class="text-gray-500">
                                Nenhum arquivo encontrado.
                            </p>

                            @endforelse

                        </div>

                    </div>
                @endif
            </form>

        </div>

    </div>

    <form id="renameForm"
        method="POST"
        action="/ged/{{ $tipo }}/rename"
        style="display:none;">

        @csrf

        <input type="hidden"
            id="renameOld"
            name="old">

        <input type="hidden"
            id="renameNew"
            name="new">

    </form>

    <form id="deleteForm"
        method="POST"
        action="/ged/{{ $tipo }}/delete"
        style="display:none;">

        @csrf
        @method('DELETE')

        <input
            type="hidden"
            id="deletePath"
            name="path">

    </form>

    @push('scripts')

        <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // RENAME
            function renomearItem(botao)
            {
                const caminho = botao.dataset.path;
                const nomeAtual = botao.dataset.name;

                Swal.fire({
                    title: 'Renomear item',
                    input: 'text',
                    inputValue: nomeAtual,
                    inputPlaceholder: 'Digite o novo nome',
                    showCancelButton: true,
                    confirmButtonText: '✏️ Renomear',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#eab308',
                    inputValidator: (value) => {

                        if (!value) {
                            return 'Informe um nome.';
                        }

                        if (value === nomeAtual) {
                            return 'Informe um nome diferente.';
                        }

                    }

                }).then((result) => {

                    if (result.isConfirmed) {

                        document.getElementById('renameOld').value = caminho;
                        document.getElementById('renameNew').value = result.value;

                        document.getElementById('renameForm').submit();

                    }

                });
            }

            // SELECT ALL
            document.getElementById('selectAll')?.addEventListener('change', function () {

                document.querySelectorAll('input[name="paths[]"]')
                    .forEach(item => item.checked = this.checked);

            });

            // DELETE
            function excluirItem(caminho)
            {
                Swal.fire({
                    title: 'Excluir item?',
                    text: 'Esta ação não poderá ser desfeita.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '🗑️ Excluir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.isConfirmed) {

                        document.getElementById('deletePath').value = caminho;
                        document.getElementById('deleteForm').submit();

                    }

                });
            }

            // DELETE SELECTED
            document.getElementById('deleteSelectedBtn')
            ?.addEventListener('click', function (e) {

                e.preventDefault();

                const selecionados = document.querySelectorAll(
                    'input[name="paths[]"]:checked'
                );

                if (selecionados.length === 0) {

                    Swal.fire({
                        icon: 'info',
                        title: 'Nenhum item selecionado',
                        text: 'Selecione pelo menos um item.'
                    });

                    return;
                }

                Swal.fire({
                    title: 'Excluir itens selecionados?',
                    text: `Você selecionou ${selecionados.length} item(ns).`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: '🗑️ Excluir'
                }).then((result) => {

                    if (result.isConfirmed) {

                        e.target.closest('form').submit();

                    }

                });

            });
        </script>

        @if(session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: @json(session('error')),
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        </script>
        @endif

    @endpush

    @stack('scripts')

</x-app-layout>