<?php
$page_assets ??= [];
$page_assets['js'] ??= [];
?>
<?= view('Components/Alert', ['alert' => $alert ?? null]) ?>
<?= vite_js('resources/js/app.js') ?>
<?php foreach ($page_assets['js'] as $js): ?>
    <?= vite_js($js) ?>
<?php endforeach; ?>
<?= $this->renderSection('scripts') ?>
<script type="module">
    Alpine.start();
</script>