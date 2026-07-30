<?= $this->extend('Layouts/AdminMain'); ?>

<?= $this->section('content'); ?>
<div
    x-data="gamesPage({
        categories: <?= esc(json_encode($categories), 'attr') ?>,
        listUrl: '<?= admin_url('games/list') ?>',
        resourceUrl: '<?= admin_url('games') ?>',
        imageBaseUrl: '<?= base_url('assets/images/games/icons/') ?>',
        bannerBaseUrl: '<?= base_url('assets/images/games/banners/') ?>',
    })"
    x-init="init()">

    <?= view('Components/Pages/Admin/Games/Header') ?>
    <?= view('Components/Pages/Admin/Games/Filters') ?>
    <?= view('Components/Pages/Admin/Games/Table') ?>
    <?= view('Components/Pages/Admin/Games/Modal') ?>
</div>
<?= $this->endSection(); ?>
