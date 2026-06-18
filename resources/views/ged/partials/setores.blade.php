<div>
    <h2 class="text-2xl font-bold mb-4">
        📁 GED - Setores
    </h2>

    {{-- VOLTAR --}}
    @if($current)

        <button
            onclick="loadPage('{{ $parentPath ? '/portal/ged/setores/' . $parentPath : '/portal/ged/setores' }}')"
            class="mb-4 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">

            ⬅ Voltar

        </button>

    @endif

    {{-- URL RETORNAVEL / BREADCRUMB--}}
    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mb-6">

        <button
            onclick="loadPage('/portal/ged/setores')"
            class="hover:text-blue-600">

            Setores

        </button>

        @foreach($breadcrumb as $item)

            <span>›</span>

            <button
                onclick="loadPage('/portal/ged/setores/{{ $item['path'] }}')"
                class="hover:text-blue-600">

                {{ $item['nome'] }}

            </button>

        @endforeach

    </div>

    <p class="text-gray-600 mb-4">
        Caminho: {{ $roots }}
    </p>

    {{-- PASTAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

        @forelse($pastas as $pasta)
            <div
                onclick="loadPage('/portal/ged/setores/{{ $current ? $current . '/' . $pasta : $pasta }}')"
                class="p-4 bg-white rounded shadow hover:bg-gray-50 cursor-pointer">

                📂 {{ $pasta }}

            </div>
        @empty
            <p class="text-gray-500">
                Nenhuma pasta encontrada
            </p>
        @endforelse

    </div>

    {{-- ARQUIVOS --}}
    @if($arquivos->isNotEmpty())

        <h3 class="text-xl font-semibold mt-8 mb-4">
            Arquivos
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            @foreach($arquivos as $arquivo)

                <div class="p-4 bg-white rounded shadow">

                    📄 {{ $arquivo }}

                </div>

            @endforeach

        </div>

    @endif

</div>