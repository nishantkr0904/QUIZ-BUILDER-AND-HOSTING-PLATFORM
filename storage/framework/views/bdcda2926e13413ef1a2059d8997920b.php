<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Quiz Platform'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="<?php echo e(asset('build/assets/app.js')); ?>" defer></script>
    <link href="<?php echo e(asset('build/assets/app.css')); ?>" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials._navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->make('components.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <?php echo $__env->make('partials._footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH D:\TrinwoSolutions\QUIZ-BUILDER-AND-HOSTING-PLATFORM\resources\views/layouts/app.blade.php ENDPATH**/ ?>