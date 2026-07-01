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
<?= view('Components/Pages/Home/CategoryGames', [
    'category_sections' => $category_sections,
]) ?>
<?= $this->endSection(); ?>