# Agent Instructions

## Communication

- Pakai mode hemat token secara default.
- Untuk command, sebutkan command yang dijalankan dan hasil singkat saja.
- Jangan paste output panjang kecuali ada error penting atau user minta detail.
- Untuk final answer, cukup tulis file berubah, commit bila ada, verifikasi utama, dan next command bila perlu.
- Pakai Bahasa Indonesia santai dan langsung.

## Project Context

- Stack: CodeIgniter 4, PHP 8.3, MySQL.
- Arsitektur backend: MVC + `app/Services`.
- Sebelum mengubah backend, baca dulu `docs/agents/backend-flow.md`.

## Git Workflow

- Branch utama: `main`.
- Branch kerja default: `develop`.
- Untuk fitur/perubahan baru, buat branch dari `develop` bila perubahan cukup besar.
- Kalau user minta langsung semua branch, apply di `main`, commit, lalu fast-forward `develop`.

## Verification

- Jalankan `php -l` hanya jika perubahan PHP kompleks atau diperlukan untuk verifikasi.
