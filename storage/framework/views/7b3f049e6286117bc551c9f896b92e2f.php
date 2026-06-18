<div>

    <h2 class="text-2xl font-bold mb-4">
        🔍 Pesquisa Global
    </h2>

    <p class="text-gray-600 mb-6">
        Resultado para: <strong><?php echo e($termo); ?></strong>
    </p>

    <?php if($resultados->isEmpty()): ?>

        <div class="p-4 bg-white rounded shadow">
            Nenhum arquivo encontrado.
        </div>

    <?php else: ?>

        <div class="space-y-3">

            <?php $__currentLoopData = $resultados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arquivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="p-4 bg-white rounded shadow">

                    <div class="font-semibold">
                        <?php echo e($arquivo['tipo'] === 'pasta' ? '📂' : '📄'); ?>

                        <?php echo e($arquivo['nome']); ?>

                    </div>

                    <div class="text-sm text-gray-500">
                        <?php echo e($arquivo['origem']); ?>

                    </div>

                    <div class="text-xs text-gray-400 mt-1">
                        <?php echo e($arquivo['caminho']); ?>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    <?php endif; ?>

</div><?php /**PATH C:\laragon\www\ged-crfpb\resources\views/ged/partials/pesquisa.blade.php ENDPATH**/ ?>