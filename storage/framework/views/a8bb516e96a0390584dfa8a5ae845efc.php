<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Login - PT WAGS'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/scss/app.scss', 'resources/js/app.js']); ?>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="auth-page">
    <?php echo $__env->yieldContent('content'); ?>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
<?php /**PATH C:\Users\sekar\Downloads\wags-main (1)\wags-main\resources\views/layouts/auth.blade.php ENDPATH**/ ?>