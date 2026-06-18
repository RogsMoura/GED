<div>

    <h2 class="text-2xl font-bold mb-4">
        🔍 Pesquisa Global
    </h2>

    <p class="text-gray-600 mb-6">
        Resultado para: <strong>{{ $termo }}</strong>
    </p>

    @if($resultados->isEmpty())

        <div class="p-4 bg-white rounded shadow">
            Nenhum arquivo encontrado.
        </div>

    @else

        <div class="space-y-3">

            @foreach($resultados as $arquivo)

                <div class="p-4 bg-white rounded shadow">

                    <div class="font-semibold">
                        {{ $arquivo['tipo'] === 'pasta' ? '📂' : '📄' }}
                        {{ $arquivo['nome'] }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $arquivo['origem'] }}
                    </div>

                    <div class="text-xs text-gray-400 mt-1">
                        {{ $arquivo['caminho'] }}
                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>