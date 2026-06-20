<?php
$request = service('request');

$siteName    = trim($meta['site_name'] ?? $meta['title'] ?? 'Kyurie CI4');
$pageTitle   = trim($meta['title'] ?? $siteName);
$subtitle    = trim($meta['subtitle'] ?? '');
$description = trim($meta['description'] ?? '');
$keywords    = trim($meta['keywords'] ?? '');
$author      = trim($meta['author'] ?? 'Kyurie');

$fullTitle = $pageTitle;

if ($subtitle !== '' && stripos($pageTitle, $subtitle) === false) {
    $fullTitle .= ' - ' . $subtitle;
}

$assetUrl = static function (?string $path): string {
    if (empty($path)) {
        return '';
    }

    return preg_match('~^https?://~i', $path)
        ? $path
        : base_url(ltrim($path, '/'));
};

$logo    = $assetUrl($meta['logo'] ?? '');
$favicon = $assetUrl($meta['favicon'] ?? '');

$canonical = $seo['canonical'] ?? current_url();

$ogTitle       = $seo['og_title'] ?? $fullTitle;
$ogDescription = $seo['og_description'] ?? $description;
$ogImage       = $assetUrl($seo['og_image'] ?? ($meta['logo'] ?? ''));
$ogUrl         = $seo['og_url'] ?? current_url();
$ogType        = $seo['og_type'] ?? 'website';
$twitterCard   = $seo['twitter_card'] ?? 'summary_large_image';

$path = trim($request->getUri()->getPath(), '/');

$isActive = static function (string $uri) use ($path): bool {
    $clean = trim($uri, '/');

    if ($clean === '') {
        return $path === '';
    }

    return $path === $clean || strpos($path, $clean . '/') === 0;
};

$user = $user ?? null;
$alert = $alert ?? null;

$schemaGraph = [
    [
        '@type' => 'WebSite',
        '@id'   => base_url('/') . '#website',
        'name'  => $siteName,
        'url'   => base_url('/'),
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => base_url('search') . '?keyword={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ],
    [
        '@type' => 'Organization',
        '@id'   => base_url('/') . '#organization',
        'name'  => $siteName,
        'url'   => base_url('/'),
    ],
];

if ($logo !== '') {
    $schemaGraph[1]['logo'] = $logo;
}

$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => $schemaGraph,
];

$jsonFlags = JSON_UNESCAPED_UNICODE
    | JSON_UNESCAPED_SLASHES
    | JSON_HEX_TAG
    | JSON_HEX_APOS
    | JSON_HEX_QUOT
    | JSON_HEX_AMP;
?>

<!doctype html>
<html lang="<?= esc($meta['lang'] ?? 'id', 'attr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <meta name="robots" content="<?= esc($seo['robots'] ?? 'index, follow, max-image-preview:large', 'attr'); ?>">

    <title><?= esc($fullTitle); ?></title>

    <?php if ($description !== '') : ?>
        <meta name="description" content="<?= esc($description, 'attr'); ?>">
    <?php endif; ?>

    <?php if ($keywords !== '') : ?>
        <meta name="keywords" content="<?= esc($keywords, 'attr'); ?>">
    <?php endif; ?>

    <meta name="author" content="<?= esc($author, 'attr'); ?>">
    <link rel="canonical" href="<?= esc($canonical, 'attr'); ?>">

    <?php if ($favicon !== '') : ?>
        <link rel="icon" href="<?= esc($favicon, 'attr'); ?>">
        <link rel="apple-touch-icon" href="<?= esc($favicon, 'attr'); ?>">
    <?php endif; ?>

    <meta property="og:title" content="<?= esc($ogTitle, 'attr'); ?>">
    <meta property="og:description" content="<?= esc($ogDescription, 'attr'); ?>">
    <meta property="og:type" content="<?= esc($ogType, 'attr'); ?>">
    <meta property="og:url" content="<?= esc($ogUrl, 'attr'); ?>">

    <?php if ($ogImage !== '') : ?>
        <meta property="og:image" content="<?= esc($ogImage, 'attr'); ?>">
        <meta property="og:image:secure_url" content="<?= esc($ogImage, 'attr'); ?>">
    <?php endif; ?>

    <meta name="twitter:card" content="<?= esc($twitterCard, 'attr'); ?>">
    <meta name="twitter:title" content="<?= esc($ogTitle, 'attr'); ?>">
    <meta name="twitter:description" content="<?= esc($ogDescription, 'attr'); ?>">

    <?php if ($ogImage !== '') : ?>
        <meta name="twitter:image" content="<?= esc($ogImage, 'attr'); ?>">
    <?php endif; ?>

    <?php if (function_exists('csrf_token') && function_exists('csrf_hash')) : ?>
        <meta name="csrf-token-name" content="<?= esc(csrf_token(), 'attr'); ?>">
        <meta name="csrf-token" content="<?= esc(csrf_hash(), 'attr'); ?>">

        <?php if (function_exists('csrf_header')) : ?>
            <meta name="csrf-header" content="<?= esc(csrf_header(), 'attr'); ?>">
        <?php endif; ?>
    <?php endif; ?>

    <script type="application/ld+json">
        <?= json_encode($schema, $jsonFlags); ?>
    </script>

    <?= $this->renderSection('schema'); ?>
    <?= $this->renderSection('head'); ?>

    <?= vite('resources/js/app.js'); ?>

    <?= $this->renderSection('styles'); ?>
</head>

<body
    x-data="layoutApp()"
    :class="{ 'overflow-hidden': mobileMenu }"
    class="min-h-screen bg-slate-950 text-slate-100 antialiased selection:bg-violet-500/30 selection:text-white"
>
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute left-1/2 top-[-180px] h-[420px] w-[720px] -translate-x-1/2 rounded-full bg-violet-600/20 blur-3xl"></div>
        <div class="absolute right-[-180px] top-[260px] h-[360px] w-[360px] rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-180px] left-[-120px] h-[360px] w-[360px] rounded-full bg-amber-400/10 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="<?= base_url('/'); ?>" class="flex items-center gap-3" aria-label="<?= esc($siteName, 'attr'); ?>">
                <?php if ($logo !== '') : ?>
                    <img
                        src="<?= esc($logo, 'attr'); ?>"
                        alt="<?= esc($siteName, 'attr'); ?>"
                        class="h-9 w-9 rounded-xl object-contain"
                        loading="eager"
                    >
                <?php else : ?>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-500 text-sm font-black text-white">
                        <?= esc(strtoupper(substr($siteName, 0, 1))); ?>
                    </span>
                <?php endif; ?>

                <span class="leading-tight">
                    <span class="block text-sm font-black tracking-wide text-white">
                        <?= esc($siteName); ?>
                    </span>

                    <?php if ($subtitle !== '') : ?>
                        <span class="hidden text-xs text-slate-400 sm:block">
                            <?= esc($subtitle); ?>
                        </span>
                    <?php endif; ?>
                </span>
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Main navigation">
                <a
                    href="<?= base_url('/'); ?>"
                    class="<?= $isActive('/') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white'; ?> rounded-xl px-4 py-2 text-sm font-semibold transition"
                >
                    Home
                </a>

                <a
                    href="<?= base_url('history'); ?>"
                    class="<?= $isActive('history') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white'; ?> rounded-xl px-4 py-2 text-sm font-semibold transition"
                >
                    Cek Transaksi
                </a>

                <a
                    href="<?= base_url('price-list'); ?>"
                    class="<?= $isActive('price-list') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white'; ?> rounded-xl px-4 py-2 text-sm font-semibold transition"
                >
                    Daftar Harga
                </a>
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <form
                    x-on:submit.prevent="submitSearch"
                    class="relative"
                    role="search"
                >
                    <input
                        x-model="searchKeyword"
                        type="search"
                        name="keyword"
                        autocomplete="off"
                        placeholder="Cari game..."
                        class="h-10 w-64 rounded-2xl border border-white/10 bg-white/5 px-4 pr-10 text-sm text-white outline-none transition placeholder:text-slate-500 focus:border-violet-400/60 focus:bg-white/10"
                    >

                    <button
                        type="submit"
                        class="absolute right-2 top-1/2 flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded-xl text-slate-400 hover:bg-white/10 hover:text-white"
                        aria-label="Cari"
                    >
                        <span aria-hidden="true">⌕</span>
                    </button>
                </form>

                <?php if ($user) : ?>
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            x-on:click="open = !open"
                            x-on:click.outside="open = false"
                            class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-left transition hover:bg-white/10"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-500 text-xs font-black text-white">
                                <?= esc(strtoupper(substr($user['username'] ?? 'U', 0, 2))); ?>
                            </span>

                            <span class="leading-tight">
                                <span class="block max-w-32 truncate text-sm font-bold text-white">
                                    <?= esc($user['username'] ?? 'User'); ?>
                                </span>
                                <span class="block text-xs text-slate-400">
                                    Rp <?= number_format((float) ($user['balance'] ?? 0), 0, ',', '.'); ?>
                                </span>
                            </span>
                        </button>

                        <div
                            x-cloak
                            x-show="open"
                            x-transition
                            class="absolute right-0 mt-3 w-56 overflow-hidden rounded-2xl border border-white/10 bg-slate-900 shadow-2xl shadow-black/30"
                        >
                            <a href="<?= base_url('user'); ?>" class="block px-4 py-3 text-sm font-semibold text-slate-200 hover:bg-white/5">
                                Dashboard
                            </a>
                            <a href="<?= base_url('user/topup'); ?>" class="block px-4 py-3 text-sm font-semibold text-slate-200 hover:bg-white/5">
                                Top Up Saldo
                            </a>
                            <a href="<?= base_url('user/history'); ?>" class="block px-4 py-3 text-sm font-semibold text-slate-200 hover:bg-white/5">
                                Riwayat Pesanan
                            </a>
                            <a href="<?= base_url('logout'); ?>" class="block border-t border-white/10 px-4 py-3 text-sm font-semibold text-rose-300 hover:bg-rose-500/10">
                                Logout
                            </a>
                        </div>
                    </div>
                <?php else : ?>
                    <a
                        href="<?= base_url('login'); ?>"
                        class="rounded-2xl px-4 py-2 text-sm font-bold text-slate-200 transition hover:bg-white/5 hover:text-white"
                    >
                        Login
                    </a>

                    <a
                        href="<?= base_url('register'); ?>"
                        class="rounded-2xl bg-violet-500 px-4 py-2 text-sm font-black text-white shadow-lg shadow-violet-500/20 transition hover:bg-violet-400"
                    >
                        Register
                    </a>
                <?php endif; ?>
            </div>

            <button
                type="button"
                x-on:click="mobileMenu = true"
                class="flex h-10 w-10 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white lg:hidden"
                aria-label="Open menu"
            >
                ☰
            </button>
        </div>
    </header>

    <div
        x-cloak
        x-show="mobileMenu"
        x-transition.opacity
        class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm lg:hidden"
        x-on:click="mobileMenu = false"
    ></div>

    <aside
        x-cloak
        x-show="mobileMenu"
        x-transition
        class="fixed bottom-0 right-0 top-0 z-[70] w-[86%] max-w-sm border-l border-white/10 bg-slate-950 p-5 shadow-2xl lg:hidden"
    >
        <div class="mb-6 flex items-center justify-between">
            <span class="text-base font-black text-white">
                Menu
            </span>

            <button
                type="button"
                x-on:click="mobileMenu = false"
                class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white"
                aria-label="Close menu"
            >
                ✕
            </button>
        </div>

        <form x-on:submit.prevent="submitSearch" class="mb-5">
            <input
                x-model="searchKeyword"
                type="search"
                placeholder="Cari game favorit..."
                class="h-12 w-full rounded-2xl border border-white/10 bg-white/5 px-4 text-sm text-white outline-none placeholder:text-slate-500 focus:border-violet-400"
            >
        </form>

        <?php if ($user) : ?>
            <div class="mb-5 rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-500 text-sm font-black text-white">
                        <?= esc(strtoupper(substr($user['username'] ?? 'U', 0, 2))); ?>
                    </span>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-black text-white">
                            <?= esc($user['username'] ?? 'User'); ?>
                        </p>
                        <p class="text-xs text-slate-400">
                            Saldo Rp <?= number_format((float) ($user['balance'] ?? 0), 0, ',', '.'); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <nav class="space-y-2">
            <a href="<?= base_url('/'); ?>" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-200 hover:bg-white/10">
                Home
            </a>
            <a href="<?= base_url('history'); ?>" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-200 hover:bg-white/10">
                Cek Transaksi
            </a>
            <a href="<?= base_url('price-list'); ?>" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-200 hover:bg-white/10">
                Daftar Harga
            </a>

            <?php if ($user) : ?>
                <a href="<?= base_url('user'); ?>" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-200 hover:bg-white/10">
                    Dashboard
                </a>
                <a href="<?= base_url('user/topup'); ?>" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-200 hover:bg-white/10">
                    Top Up Saldo
                </a>
                <a href="<?= base_url('logout'); ?>" class="block rounded-2xl px-4 py-3 text-sm font-bold text-rose-300 hover:bg-rose-500/10">
                    Logout
                </a>
            <?php else : ?>
                <div class="grid grid-cols-2 gap-3 pt-3">
                    <a href="<?= base_url('login'); ?>" class="rounded-2xl border border-white/10 px-4 py-3 text-center text-sm font-black text-white">
                        Login
                    </a>
                    <a href="<?= base_url('register'); ?>" class="rounded-2xl bg-violet-500 px-4 py-3 text-center text-sm font-black text-white">
                        Register
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </aside>

    <main id="main-content" class="min-h-[calc(100vh-64px)]">
        <?= $this->renderSection('content'); ?>
    </main>

    <footer class="border-t border-white/10 bg-slate-950/80">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1.4fr_1fr_1fr] lg:px-8">
            <div>
                <div class="mb-3 flex items-center gap-3">
                    <?php if ($logo !== '') : ?>
                        <img src="<?= esc($logo, 'attr'); ?>" alt="<?= esc($siteName, 'attr'); ?>" class="h-9 w-9 rounded-xl object-contain" loading="lazy">
                    <?php endif; ?>

                    <span class="text-base font-black text-white">
                        <?= esc($siteName); ?>
                    </span>
                </div>

                <?php if ($description !== '') : ?>
                    <p class="max-w-xl text-sm leading-6 text-slate-400">
                        <?= esc($description); ?>
                    </p>
                <?php endif; ?>
            </div>

            <div>
                <h2 class="mb-3 text-sm font-black text-white">Navigasi</h2>
                <div class="space-y-2 text-sm text-slate-400">
                    <a href="<?= base_url('/'); ?>" class="block hover:text-white">Home</a>
                    <a href="<?= base_url('history'); ?>" class="block hover:text-white">Cek Transaksi</a>
                    <a href="<?= base_url('price-list'); ?>" class="block hover:text-white">Daftar Harga</a>
                </div>
            </div>

            <div>
                <h2 class="mb-3 text-sm font-black text-white">Akun</h2>
                <div class="space-y-2 text-sm text-slate-400">
                    <?php if ($user) : ?>
                        <a href="<?= base_url('user'); ?>" class="block hover:text-white">Dashboard</a>
                        <a href="<?= base_url('user/history'); ?>" class="block hover:text-white">Riwayat Pesanan</a>
                    <?php else : ?>
                        <a href="<?= base_url('login'); ?>" class="block hover:text-white">Login</a>
                        <a href="<?= base_url('register'); ?>" class="block hover:text-white">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-slate-500">
            © <?= date('Y'); ?> <?= esc($siteName); ?>. All rights reserved.
        </div>
    </footer>

    <?php if (! empty($alert)) : ?>
        <script>
            window.__APP_ALERT__ = <?= json_encode([
                'type'    => $alert['type'] ?? 'info',
                'title'   => $alert['title'] ?? '',
                'message' => $alert['message'] ?? '',
            ], $jsonFlags); ?>;
        </script>
    <?php endif; ?>

    <?= $this->renderSection('modals'); ?>
    <?= $this->renderSection('scripts'); ?>
</body>
</html>