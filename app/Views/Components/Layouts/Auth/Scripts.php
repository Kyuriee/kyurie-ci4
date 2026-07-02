<?php
$page_assets ??= [];
$page_assets['js'] ??= [];
?>

<!-- Global Alert Bridge -->
<?= view('Components/Alert', ['alert' => $alert ?? null]) ?>

<!-- Global Auth JS (Independent) -->
<?= vite_js('resources/js/auth.js') ?>

<!-- Page JS -->
<?php foreach ($page_assets['js'] as $js): ?>
    <?= vite_js($js) ?>
<?php endforeach; ?>

<?= $this->renderSection('scripts') ?>

<!-- Alpine Starter Manual -->
<script type="module">
    Alpine.start();
</script>