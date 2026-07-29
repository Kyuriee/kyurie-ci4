<?php $page_assets ??= ['css' => []]; ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta name="robots" content="noindex, nofollow, noarchive">
<title><?= esc($meta['title'] ?? 'Admin') ?> — Kyurie</title>

<?php if (function_exists('csrf_token')): ?>
    <meta name="csrf-token-name" content="<?= esc(csrf_token()) ?>">
    <meta name="csrf-token" content="<?= esc(csrf_hash()) ?>">
    <?php if (function_exists('csrf_header')): ?>
        <meta name="csrf-header" content="<?= esc(csrf_header()) ?>">
    <?php endif; ?>
<?php endif; ?>

<?= vite_css('resources/css/admin.css') ?>
<?php foreach ($page_assets['css'] as $css): ?>
    <?= vite_css($css) ?>
<?php endforeach; ?>
