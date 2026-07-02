<?php if (! empty($alert)) : ?>
<script>
    window.__APP_ALERT__ = <?= json_encode($alert, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
</script>
<?php endif ?>