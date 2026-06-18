<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Portal GED</title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>

    <body class="bg-gray-100">

        <div class="flex h-screen">

            
            <aside class="w-64 bg-white shadow-lg p-4">

                <h1 class="text-xl font-bold mb-6">
                    GED CRF
                </h1>

                <div class="mb-6">

                    <input
                        type="text"
                        id="global-search"
                        placeholder="🔍 Pesquisar no GED..."
                        class="w-full p-2 border rounded"
                        onkeydown="if(event.key === 'Enter') searchGed()">

                </div>

                <nav class="space-y-2">

                    <button onclick="loadPage('/portal/home')"
                        class="w-full text-left p-2 hover:bg-gray-100 rounded">
                        🏠 Home
                    </button>

                    <button onclick="loadPage('/portal/ged/setores')"
                        class="w-full text-left p-2 hover:bg-gray-100 rounded">
                        📁 Setores
                    </button>

                    <button onclick="loadPage('/ged/pf')"
                        class="w-full text-left p-2 hover:bg-gray-100 rounded">
                        👤 Pessoa Física
                    </button>

                    <button onclick="loadPage('/ged/pj')"
                        class="w-full text-left p-2 hover:bg-gray-100 rounded">
                        🏢 Pessoa Jurídica
                    </button>

                </nav>

            </aside>

            
            <main class="flex-1 p-6" id="content">

                <h2 class="text-2xl font-bold">
                    Bem-vindo ao Portal GED
                </h2>

                <p class="text-gray-600 mt-2">
                    Selecione uma opção no menu lateral.
                </p>

            </main>

        </div>

        <script>
            function loadPage(url) {

                fetch(url)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('content').innerHTML = html;
                    })
                    .catch(err => {
                        document.getElementById('content').innerHTML =
                            "<p>Erro ao carregar página</p>";
                    });

            }

            function searchGed() {

                const termo = document
                    .getElementById('global-search')
                    .value
                    .trim();

                if (!termo) return;

                loadPage('/portal/pesquisa?q=' + encodeURIComponent(termo));
            }
        </script>

    </body>
</html><?php /**PATH C:\laragon\www\ged-crfpb\resources\views/layouts/portal.blade.php ENDPATH**/ ?>