<div>
    <h2 class="text-2xl font-bold mb-4">
        📁 GED - Setores
    </h2>

    
    <?php if($current): ?>

        <button
            onclick="loadPage('<?php echo e($parentPath ? '/portal/ged/setores/' . $parentPath : '/portal/ged/setores'); ?>')"
            class="mb-4 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">

            ⬅ Voltar

        </button>

    <?php endif; ?>

    
    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mb-6">

        <button
            onclick="loadPage('/portal/ged/setores')"
            class="hover:text-blue-600">

            Setores

        </button>

        <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <span>›</span>

            <button
                onclick="loadPage('/portal/ged/setores/<?php echo e($item['path']); ?>')"
                class="hover:text-blue-600">

                <?php echo e($item['nome']); ?>


            </button>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

    <p class="text-gray-600 mb-4">
        Caminho: <?php echo e($roots); ?>

    </p>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

        <?php $__empty_1 = true; $__currentLoopData = $pastas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div
                onclick="loadPage('/portal/ged/setores/<?php echo e($current ? $current . '/' . $pasta : $pasta); ?>')"
                class="p-4 bg-white rounded shadow hover:bg-gray-50 cursor-pointer">

                📂 <?php echo e($pasta); ?>


            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-500">
                Nenhuma pasta encontrada
            </p>
        <?php endif; ?>

    </div>

    
    <?php if($arquivos->isNotEmpty()): ?>

        <h3 class="text-xl font-semibold mt-8 mb-4">
            Arquivos
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            <?php $__currentLoopData = $arquivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arquivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="p-4 bg-white rounded shadow">

                    📄 <?php echo e($arquivo); ?>


                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    <?php endif; ?>

</div><?php /**PATH C:\laragon\www\ged-crfpb\resources\views/ged/partials/setores.blade.php ENDPATH**/ ?>