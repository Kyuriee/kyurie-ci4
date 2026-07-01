<!-- Global JS -->
<?= vite_js('resources/js/app.js') ?>
<!-- Page JS -->
<?php foreach ($page_assets['js'] as $js): ?>
    <?= vite_js($js) ?>
<?php endforeach; ?>
<?= $this->renderSection('scripts') ?>
<script type="module">
    Alpine.start();
</script>