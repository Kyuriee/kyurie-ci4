<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<?= view('Components/Pages/Home/Banner', [
    'banners' => $banners,
]) ?>
<?= view('Components/Pages/Home/Flashsale', [
    'flashsale' => $flashsale,
]) ?>
<?= view('Components/Pages/Home/PopularGames', [
    'popular_games' => $popular_games,
]) ?>
<?= $this->endSection(); ?>
