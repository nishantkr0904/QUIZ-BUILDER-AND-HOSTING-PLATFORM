<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Quiz Platform'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/app.css')); ?>" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials._navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <?php echo $__env->make('partials._footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
</body>
</html>
<?php /**PATH D:\TrinwoSolutions\QUIZ-BUILDER-AND-HOSTING-PLATFORM\resources\views/layouts/app.blade.php ENDPATH**/ ?>