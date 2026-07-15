<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title><?= esc($meta['title'] ?? 'Admin') ?></title>

    <?= vite_css('resources/css/admin.css') ?>
</head>

<body
    x-data="{ darkMode: $persist(false) }"
    :class="{ 'dark bg-gray-900': darkMode === true }"
>
    <?= $this->renderSection('content') ?>

    <?= vite_js('resources/js/admin.js') ?>
    <script type="module">
        Alpine.start();
    </script>
</body>

</html>
