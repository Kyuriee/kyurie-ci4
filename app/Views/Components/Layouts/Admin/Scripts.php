<?php $page_assets ??= ['js' => []]; ?>
<?= view('Components/Alert', ['alert' => $alert ?? null]) ?>
<?= vite_js('resources/js/admin.js') ?>
<?php foreach ($page_assets['js'] as $js): ?>
    <?= vite_js($js) ?>
<?php endforeach; ?>
<script type="module">
    Alpine.start();
</script>
