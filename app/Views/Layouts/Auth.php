<!DOCTYPE html>
<html lang="<?= esc($meta['lang'] ?? 'id') ?>">
<head>
    <?= view('Components/Layouts/Auth/Head') ?>
</head>
<body 
    x-data="{}" 
    class="min-h-screen bg-game-ambient text-body font-body antialiased flex flex-col justify-center py-12 sm:px-6 lg:px-8"
>

    <!-- Tempat Form Login / Register / Forgot Password -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <?= $this->renderSection('content') ?>
    </div>

    <?= view('Components/Layouts/Auth/Scripts') ?>
</body>
</html>