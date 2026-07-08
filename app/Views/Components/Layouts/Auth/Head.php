<?php
$meta ??= [];
$seo ??= [];
$page_assets ??= [];
$page_assets['css'] ??= [];

$asset = static function (?string $path): string {
    if (empty($path)) {
        return '';
    }
    return preg_match('~^https?://~i', $path)
        ? $path
        : base_url(ltrim($path, '/'));
};

$title       = $meta['title'] ?? $meta['site_name'] ?? 'Auth System';
$description = $meta['description'] ?? '';
$keywords    = $meta['keywords'] ?? '';
$author      = $meta['author'] ?? '';
$lang        = $meta['lang'] ?? 'id';
$favicon     = $asset($meta['favicon'] ?? '');
$logo        = $asset($meta['logo'] ?? '');
$canonical   = $seo['canonical'] ?? current_url();

$ogTitle       = ($seo['og_title'] ?? null) ?: $title;
$ogDescription = ($seo['og_description'] ?? null) ?: $description;
$ogImage       = $asset($seo['og_image'] ?? $logo);
$ogUrl         = ($seo['og_url'] ?? null) ?? current_url();
$ogType        = $seo['og_type'] ?? 'website';
$robots        = $seo['robots'] ?? 'index,follow';
$twitterCard  = $seo['twitter_card'] ?? 'summary_large_image';
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#3B82F6">
<meta name="robots" content="<?= esc($robots) ?>">

<title><?= esc($title) ?></title>

<?php if ($description): ?>
    <meta name="description" content="<?= esc($description) ?>">
<?php endif; ?>
<?php if ($keywords): ?>
    <meta name="keywords" content="<?= esc($keywords) ?>">
<?php endif; ?>
<?php if ($author): ?>
    <meta name="author" content="<?= esc($author) ?>">
<?php endif; ?>

<link rel="canonical" href="<?= esc($canonical) ?>">

<?php if ($favicon): ?>
    <link rel="icon" href="<?= esc($favicon) ?>">
    <link rel="apple-touch-icon" href="<?= esc($favicon) ?>">
<?php endif; ?>

<meta property="og:type" content="<?= esc($ogType) ?>">
<meta property="og:title" content="<?= esc($ogTitle) ?>">
<meta property="og:description" content="<?= esc($ogDescription) ?>">
<meta property="og:url" content="<?= esc($ogUrl) ?>">
<?php if ($ogImage): ?>
    <meta property="og:image" content="<?= esc($ogImage) ?>">
<?php endif; ?>

<meta name="twitter:card" content="<?= esc($twitterCard) ?>">
<meta name="twitter:title" content="<?= esc($ogTitle) ?>">
<meta name="twitter:description" content="<?= esc($ogDescription) ?>">
<?php if ($ogImage): ?>
    <meta name="twitter:image" content="<?= esc($ogImage) ?>">
<?php endif; ?>

<?php if (function_exists('csrf_token')): ?>
    <meta name="csrf-token-name" content="<?= esc(csrf_token()) ?>">
    <meta name="csrf-token" content="<?= esc(csrf_hash()) ?>">
    <?php if (function_exists('csrf_header')): ?>
        <meta name="csrf-header" content="<?= esc(csrf_header()) ?>">
    <?php endif; ?>
<?php endif; ?>

<?= vite_css('resources/css/auth.css') ?>

<?php foreach ($page_assets['css'] as $css): ?>
    <?= vite_css($css) ?>
<?php endforeach; ?>

<?= $this->renderSection('styles') ?>
<?= $this->renderSection('head') ?>