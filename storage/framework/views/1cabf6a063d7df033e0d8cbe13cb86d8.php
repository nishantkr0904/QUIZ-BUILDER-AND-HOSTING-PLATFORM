

<?php $__env->startSection('title', 'Quiz Listing'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h2 class="mb-4">Available Quizzes</h2>
    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-4 mb-4">
            <div class="card quiz-card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($quiz->title); ?></h5>
                    <p class="card-text">Category: <span class="badge bg-info"><?php echo e($quiz->category->name); ?></span></p>
                    <p class="card-text">Difficulty: <span class="badge bg-warning text-dark"><?php echo e(ucfirst($quiz->difficulty)); ?></span></p>
                    <p class="card-text">Duration: <?php echo e($quiz->duration); ?> minutes</p>
                    <p class="card-text">Passing Score: <?php echo e($quiz->passing_score); ?>%</p>
                    <a href="<?php echo e(route('quiz.take', ['id' => $quiz->id])); ?>" class="btn btn-primary">Start Quiz</a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-info">
                No quizzes available at the moment.
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\TrinwoSolutions\QUIZ-BUILDER-AND-HOSTING-PLATFORM\resources\views/quizzes/list.blade.php ENDPATH**/ ?>