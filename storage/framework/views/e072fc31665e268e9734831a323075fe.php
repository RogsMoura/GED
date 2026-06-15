<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="max-w-7xl mx-auto p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <h1 class="text-2xl font-bold mb-4">
                <?php echo e($titulo); ?>

            </h1>

            
            <div class="mb-6 text-sm text-gray-600">

                <a href="/ged/<?php echo e($tipo); ?>"
                   class="text-blue-600 hover:underline">
                    🏠 <?php echo e($titulo); ?>

                </a>

                <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <span class="mx-1">→</span>

                    <a href="<?php echo e($item['url']); ?>"
                       class="text-blue-600 hover:underline">
                        <?php echo e($item['nome']); ?>

                    </a>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>

            
            <div class="grid md:grid-cols-2 gap-4 mb-6">

                <?php if(!empty($path)): ?>
                    
                    <div class="border rounded-lg p-4">

                        <h3 class="font-semibold mb-3">
                            Upload de Arquivo
                        </h3>

                        <form method="POST"
                            enctype="multipart/form-data"
                            action="/ged/<?php echo e($tipo); ?>/upload">

                            <?php echo csrf_field(); ?>

                            <input type="hidden"
                                name="path"
                                value="<?php echo e($path); ?>">

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
                <?php endif; ?>

                
                <div class="border rounded-lg p-4">

                    <h3 class="font-semibold mb-3">
                        📁 Nova Pasta
                    </h3>

                    <form method="POST"
                          action="/ged/<?php echo e($tipo); ?>/folder">

                        <?php echo csrf_field(); ?>

                        <input type="hidden"
                               name="path"
                               value="<?php echo e($path); ?>">

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

            <form method="POST" action="/ged/<?php echo e($tipo); ?>/delete-multiple">

                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

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

                
                <div class="mb-8">

                    <h2 class="font-bold text-lg mb-3">
                        📁 Pastas
                    </h2>

                    <div class="space-y-2">

                    <?php $__empty_1 = true; $__currentLoopData = $pastas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div class="flex items-center justify-between border rounded p-3">

                            <div class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="paths[]"
                                    value="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($pasta); ?>">

                                <a href="/ged/<?php echo e($tipo); ?>/<?php echo e($path ? $path.'/' : ''); ?><?php echo e(urlencode($pasta)); ?>"
                                class="text-blue-600 hover:underline">

                                    📁 <?php echo e($pasta); ?>


                                </a>

                            </div>

                            <div class="flex items-center gap-2">

                                <button
                                    type="button"
                                    title="Renomear"
                                    data-path="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($pasta); ?>"
                                    data-name="<?php echo e($pasta); ?>"
                                    onclick="renomearItem(this)"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">

                                    ✏️

                                </button>

                                <button
                                    type="button"
                                    title="Excluir"
                                    data-path="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($pasta); ?>"
                                    onclick="excluirItem(this.dataset.path)"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                    🗑️

                                </button>

                            </div>

                        </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <p class="text-gray-500">
                            Nenhuma pasta encontrada.
                        </p>

                        <?php endif; ?>

                    </div>

                </div>

                <?php if(!empty($path)): ?>
                    
                    <div>

                        <h2 class="font-bold text-lg mb-3">
                            📄 Arquivos
                        </h2>

                        <div class="space-y-2">

                            <?php $__empty_1 = true; $__currentLoopData = $arquivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arquivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <div class="flex items-center justify-between border rounded p-3">

                                <div class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="paths[]"
                                        value="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($arquivo); ?>">

                                    <span>
                                        📄 <?php echo e($arquivo); ?>

                                    </span>

                                </div>

                                <div class="flex items-center gap-2">

                                    <a href="/ged-arquivo/<?php echo e($tipo); ?>/<?php echo e($path ? $path.'/' : ''); ?><?php echo e(urlencode($arquivo)); ?>"
                                        target="_blank"
                                        title="Visualizar"
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">

                                            👁️
                                    </a>

                                    <a href="/ged-download/<?php echo e($tipo); ?>/<?php echo e($path ? $path.'/' : ''); ?><?php echo e(urlencode($arquivo)); ?>"
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">

                                            ⬇️

                                    </a>

                                    <button
                                        type="button"
                                        title="Renomear"
                                        data-path="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($arquivo); ?>"
                                        data-name="<?php echo e(pathinfo($arquivo, PATHINFO_FILENAME)); ?>"
                                        onclick="renomearItem(this)"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">

                                        ✏️

                                    </button>

                                    <button
                                        type="button"
                                        title="Excluir"
                                        data-path="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($arquivo); ?>"
                                        onclick="excluirItem(this.dataset.path)"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                        🗑️

                                    </button>

                                </div>

                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <p class="text-gray-500">
                                Nenhum arquivo encontrado.
                            </p>

                            <?php endif; ?>

                        </div>

                    </div>
                <?php endif; ?>
            </form>

        </div>

    </div>

    <form id="renameForm"
        method="POST"
        action="/ged/<?php echo e($tipo); ?>/rename"
        style="display:none;">

        <?php echo csrf_field(); ?>

        <input type="hidden"
            id="renameOld"
            name="old">

        <input type="hidden"
            id="renameNew"
            name="new">

    </form>

    <form id="deleteForm"
        method="POST"
        action="/ged/<?php echo e($tipo); ?>/delete"
        style="display:none;">

        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>

        <input
            type="hidden"
            id="deletePath"
            name="path">

    </form>

    <?php $__env->startPush('scripts'); ?>

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

        <?php if(session('success')): ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: <?php echo json_encode(session('success'), 15, 512) ?>,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        </script>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: <?php echo json_encode(session('error'), 15, 512) ?>,
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        </script>
        <?php endif; ?>

    <?php $__env->stopPush(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\ged-crfpb\resources\views/ged/explorador.blade.php ENDPATH**/ ?>