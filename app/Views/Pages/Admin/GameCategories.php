<?= $this->extend('Layouts/AdminMain'); ?>

<?= $this->section('content'); ?>
<div
    x-data="gameCategoriesPage({
        listUrl: '<?= admin_url('game-categories/list') ?>',
        resourceUrl: '<?= admin_url('game-categories') ?>',
        imageBaseUrl: '<?= base_url('assets/images/games/categories/') ?>',
    })"
    x-init="init()">

    <?= view('Components/Pages/Admin/GameCategories/Header') ?>
    <?= view('Components/Pages/Admin/GameCategories/Filters') ?>
    <?= view('Components/Pages/Admin/GameCategories/Table') ?>
    <?= view('Components/Pages/Admin/GameCategories/Modal') ?>
</div>
<?= $this->endSection(); ?>
