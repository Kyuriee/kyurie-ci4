<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>Dashboard Admin</title>
</head>
<body style="font-family: system-ui, sans-serif; padding: 2rem;">
    <h1>Login admin berhasil ✅</h1>
    <p>Halo, <strong><?= esc($admin_name ?? '-') ?></strong> (<?= esc($admin_level ?? '-') ?>)</p>
    <p><em>Ini placeholder — dashboard Mazer (sidebar/topbar/widgets) belum dibangun, nyusul fase berikutnya.</em></p>
    <p><a href="<?= admin_url('logout') ?>">Logout</a></p>
</body>
</html>
