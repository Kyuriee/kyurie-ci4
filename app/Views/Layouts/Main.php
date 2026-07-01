<!DOCTYPE html>
<html lang="<?= esc($meta['lang'] ?? 'id') ?>">
    <head>
        <?= view('Components/Layout/Main/Head') ?>
    </head>
    <body
        x-data="layoutApp()"
        :class="{ 'overflow-hidden': mobileMenu }"
        class="min-h-screen bg-background text-body antialiased"
    >
        <!-- Header -->
        <?= view('Components/Layout/Main/Header') ?>
        <!-- Mobile Menu -->
        <?= view('Components/Layout/Main/MobileMenu') ?>
        <!-- Main Content -->
        <main class="min-h-screen">
            <?= $this->renderSection('content') ?>
        </main>
        <!-- Footer -->
        <?= view('Components/Layout/Main/Footer') ?>
        <!-- Scripts -->
        <?= view('Components/Layout/Main/Scripts') ?>
    </body>
</html>