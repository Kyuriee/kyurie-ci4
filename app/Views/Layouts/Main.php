<!DOCTYPE html>
<html lang="<?= esc($meta['lang'] ?? 'id') ?>">
    <head>
        <?= view('Components/Layouts/Main/Head') ?>
    </head>
    <body
        x-data="layoutApp()"
        :class="{ 'overflow-hidden': mobileMenu }"
        class="min-h-screen bg-background text-body antialiased"
    >
        <!-- Header -->
        <?= view('Components/Layouts/Main/Header') ?>
        <!-- Mobile Menu -->
        <?= view('Components/Layouts/Main/MobileMenu') ?>
        <!-- Main Content -->
        <main class="min-h-screen">
            <?= $this->renderSection('content') ?>
        </main>
        <!-- Footer -->
        <?= view('Components/Layouts/Main/Footer') ?>
        <!-- Scripts -->
        <?= view('Components/Layouts/Main/Scripts') ?>
    </body>
</html>