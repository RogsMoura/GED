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

                        <input type="file"
                               name="arquivo"
                               class="mb-2 w-full border rounded p-2">

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Upload
                        </button>

                    </form>

                </div>

                
                <div class="border rounded-lg p-4">

                    <h3 class="font-semibold mb-3">
                        Criar Pasta
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

            
            <div class="mb-8">

                <h2 class="font-bold text-lg mb-3">
                    📁 Pastas
                </h2>

                <div class="space-y-2">

                    <?php $__empty_1 = true; $__currentLoopData = $pastas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div class="flex items-center justify-between border rounded p-3">

                            <a href="/ged/<?php echo e($tipo); ?>/<?php echo e($path ? $path.'/' : ''); ?><?php echo e(urlencode($pasta)); ?>"
                               class="text-blue-600 hover:underline">

                                📁 <?php echo e($pasta); ?>


                            </a>

                            <form method="POST"
                                  action="/ged/<?php echo e($tipo); ?>/delete">

                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <input type="hidden"
                                       name="path"
                                       value="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($pasta); ?>">

                                <button
                                    onclick="return confirm('Excluir pasta?')"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                    Excluir

                                </button>

                            </form>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <p class="text-gray-500">
                            Nenhuma pasta encontrada.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

            
            <div>

                <h2 class="font-bold text-lg mb-3">
                    📄 Arquivos
                </h2>

                <div class="space-y-2">

                    <?php $__empty_1 = true; $__currentLoopData = $arquivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arquivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div class="flex items-center justify-between border rounded p-3">

                            <span>
                                📄 <?php echo e($arquivo); ?>

                            </span>

                            <div class="flex gap-2">

                                <a href="/ged-arquivo/<?php echo e($tipo); ?>/<?php echo e($path ? $path.'/' : ''); ?><?php echo e(urlencode($arquivo)); ?>"
                                   class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">

                                    Abrir

                                </a>

                                <a href="/ged-download/<?php echo e($tipo); ?>/<?php echo e($path ? $path.'/' : ''); ?><?php echo e(urlencode($arquivo)); ?>"
                                   class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">

                                    Download

                                </a>

                                <form method="POST"
                                      action="/ged/<?php echo e($tipo); ?>/delete">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <input type="hidden"
                                           name="path"
                                           value="<?php echo e($path ? $path.'/' : ''); ?><?php echo e($arquivo); ?>">

                                    <button
                                        onclick="return confirm('Excluir arquivo?')"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

                                        Excluir

                                    </button>

                                </form>

                            </div>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <p class="text-gray-500">
                            Nenhum arquivo encontrado.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

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