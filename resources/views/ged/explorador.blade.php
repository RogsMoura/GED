<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <h1 class="text-2xl font-bold mb-4">
                {{ $titulo }}
            </h1>

            <div class="flex items-center gap-3 mb-4">

                <a href="{{ url('/ged/' . $tipo) }}"
                onclick="event.preventDefault(); history.length > 1 ? history.back() : window.location = this.href;"
                class="px-4 py-2 bg-gray-100 border border-gray-300
                        text-gray-700 rounded-lg hover:bg-gray-200 rounded-lg">

                    ← Voltar

                </a>

            </div>

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
            <div class="flex items-center gap-2 mb-6">

                {{-- NOVA PASTA --}}
                <button
                    type="button"
                    title="Nova pasta"
                    onclick="criarPasta()"
                    class="w-10 h-10 flex items-center justify-center
                        bg-gray-100 text-gray-700 rounded-lg
                        hover:bg-gray-200 transition whitespace-nowrap">

                    📁

                </button>
                
                {{-- UPLOAD --}}
                @if(!empty($path))

                    <button
                        type="button"
                        title="Upload de arquivos"
                        onclick="document.getElementById('uploadInput').click()"
                        class="w-10 h-10 flex items-center justify-center
                        bg-gray-100 text-gray-700 rounded-lg
                        hover:bg-gray-200 transition whitespace-nowrap">

                        ⬆️

                    </button>

                @endif

            </div>

            {{-- Filtros --}}
            @if(empty($path))

                <div class="bg-gray-50 border rounded-lg p-4 mb-6">

                    <form id="filterForm" method="GET">

                        <div class="grid lg:grid-cols-2 gap-4">

                            <div class="flex flex-wrap gap-3 items-center">

                                <input
                                    id="searchInput"
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="🔍 Buscar pasta"
                                    class="border rounded p-2 flex-1 min-w-[250px]">

                            </div>

                            <div class="flex gap-2 justify-end">

                                <select
                                    id="sortSelect"
                                    name="sort"
                                    class="border rounded p-2">

                                    <option value="name_asc" {{ request('sort', 'name_asc') == 'name_asc' ? 'selected' : '' }}>
                                        A → Z
                                    </option>

                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                        Z → A
                                    </option>

                                </select>

                                <select
                                    id="perPageSelect"
                                    name="per_page"
                                    class="border rounded p-2">

                                    @foreach([25, 50, 100] as $size)

                                        <option
                                            value="{{ $size }}"
                                            {{ request('per_page', 50) == $size ? 'selected' : '' }}>

                                            {{ $size }} por página

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </form>

                </div>

            @endif

            {{-- Exclusão múltipla --}}
            <form method="POST" action="/ged/{{ $tipo }}/delete-multiple">

                @csrf
                @method('DELETE')

                <div class="flex flex-wrap items-center justify-between gap-4 mb-6 p-4 bg-gray-50 border rounded-lg">

                    <div class="flex items-center gap-4">

                        <label class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll">
                            <span>Selecionar todos</span>
                        </label>

                        <span id="selectedCount" class="text-sm text-gray-500">
                            0 itens selecionados
                        </span>

                    </div>

                    <button
                        id="deleteSelectedBtn"
                        type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50">

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

                    @if(empty($path) && $pastas instanceof \Illuminate\Pagination\LengthAwarePaginator)

                        <div class="mt-6">
                            {{ $pastas->appends(request()->query())->links() }}
                        </div>

                    @endif

                </div>

                {{-- Arquivos --}}
                @if(!empty($path))
                    
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

    <form id="folderForm"
        method="POST"
        action="/ged/{{ $tipo }}/folder"
        class="hidden">

        @csrf

        <input
            type="hidden"
            name="path"
            value="{{ $path }}">

        <input
            type="hidden"
            id="folderName"
            name="nome">

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

    <form id="uploadForm"
        method="POST"
        enctype="multipart/form-data"
        action="/ged/{{ $tipo }}/upload"
        class="hidden">

        @csrf

        <input
            type="hidden"
            name="path"
            value="{{ $path }}">

        <input
            id="uploadInput"
            type="file"
            name="arquivos[]"
            multiple
            class="hidden">

    </form>

    @push('scripts')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            atualizarContador();

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

            //UPLOAD
            document.getElementById('uploadInput')?.addEventListener('change', function () {

                if (this.files.length > 0) {
                    document.getElementById('uploadForm').submit();
                }

            });

            //CRIAR PASTA
            function criarPasta()
            {
                Swal.fire({
                    title: 'Nova pasta',
                    input: 'text',
                    inputPlaceholder: 'Digite o nome da pasta',
                    showCancelButton: true,
                    confirmButtonText: 'Criar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#16a34a',

                    inputValidator: (value) => {

                        if (!value) {
                            return 'Informe um nome.';
                        }

                    }

                }).then((result) => {

                    if (result.isConfirmed) {

                        document.getElementById('folderName').value = result.value;
                        document.getElementById('folderForm').submit();

                    }

                });
            }

            //ATUALIZAR CONTADOR SELECT
            function atualizarContador() {

                const total = document.querySelectorAll(
                    'input[name="paths[]"]:checked'
                ).length;

                const contador = document.getElementById('selectedCount');

                contador.textContent =
                    `${total} item(ns) selecionado(s)`;
            }
            document.querySelectorAll('input[name="paths[]"]')
                .forEach(item => {

                    item.addEventListener('change', atualizarContador);

                });
                
            document.getElementById('selectAll')
                ?.addEventListener('change', function () {

                    document.querySelectorAll('input[name="paths[]"]')
                        .forEach(item => item.checked = this.checked);

                    atualizarContador();
            });

            // Busca instantânea
            let debounce;
            document.getElementById('searchInput')
                ?.addEventListener('input', function () {

                    clearTimeout(debounce);

                    debounce = setTimeout(() => {

                        document.getElementById('filterForm').submit();

                    }, 500);

                });

            // Ordenação automática
            document.getElementById('sortSelect')
                ?.addEventListener('change', function () {

                    document.getElementById('filterForm').submit();

                });

            // Quantidade por página automática
            document.getElementById('perPageSelect')
                ?.addEventListener('change', function () {

                    document.getElementById('filterForm').submit();

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

</x-app-layout>