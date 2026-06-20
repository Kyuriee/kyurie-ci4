# Backend Flow Notes for Agents

Dokumen ini adalah briefing singkat untuk session agent baru sebelum mengubah backend project ini.

## Stack

- Framework: CodeIgniter 4.
- PHP: target project PHP 8.3, composer saat ini menerima `^8.2`.
- Database: MySQL.
- Pola utama: MVC CodeIgniter 4 dengan tambahan layer `app/Services`.
- Jangan pakai layer `app/Providers`; folder itu sengaja dihapus karena terlalu kompleks untuk kebutuhan project sekarang.

## Struktur Penting

- `app/Config/Routes.php`: pintu masuk URL ke controller.
- `app/Controllers`: menerima request, validasi ringan, session check, redirect/JSON/view response.
- `app/Services`: business logic aplikasi. Taruh proses utama di sini.
- `app/Models`: query database dan operasi tabel.
- `app/Views`: template output HTML.
- `app/Database/Migrations`: definisi struktur tabel.
- `app/Database/Seeds`: data awal.
- `app/Filters`: middleware CI4, saat ini ada `AuthFilter`.
- `app/Config/Services.php`: registry service reusable.
- `app/Helpers/vite_helper.php`: integrasi asset Vite.

## Prinsip Arsitektur

Gunakan flow ini untuk fitur backend:

```text
Route -> Controller -> Service -> Model -> Database
                         |
                         -> helper/service lain bila perlu
Controller -> View atau JSON response
```

Controller tidak boleh menampung business logic panjang. Controller cukup:

- baca input dari `$this->request`;
- cek session/authorization sederhana;
- validasi request yang dekat dengan HTTP/form;
- panggil service;
- set flashdata, redirect, JSON, atau render view.

Service adalah tempat aturan bisnis:

- validasi domain, misalnya produk harus aktif, stok tersedia, game sesuai produk;
- hitung harga lewat `PriceService`;
- susun data untuk halaman;
- koordinasi beberapa model;
- return array konsisten seperti `['success' => bool, 'message' => string, 'data' => mixed]` untuk proses aksi.

Model fokus ke database:

- set `$table`, `$primaryKey`, `$returnType`;
- gunakan method query bernama jelas seperti `getDetailBySlug()`, `getProductsByGame()`, `findByInvoice()`;
- jangan taruh redirect, session, atau render view di model.

## Request Flow Saat Ini

### Home

`GET /` masuk ke `Home::index()`.

Flow:

```text
Home::index()
-> HomeService
-> banner, flashsale, popular games, category sections
-> renderView('Home/Index')
```

`BaseController::renderView()` menggabungkan data halaman dengan `base_data` global seperti meta, SEO, user aktif, dan alert.

### Auth

Route utama:

- `auth/login`
- `auth/register`
- `auth/logout`

Flow login:

```text
Auth::login()
-> throttler + form validation
-> AuthService::login()
-> set session user_id bila sukses
-> optional remember me cookie
-> redirect
```

Flow register:

```text
Auth::register()
-> form validation
-> AuthService::register()
-> redirect login bila sukses
```

User aktif dibaca di `BaseController::_get_current_user()` dari session `user_id`, lalu fallback ke cookie `remember_me`.

### Game Detail

`GET /games/(:any)` masuk ke `Game::detail($slug)`.

Flow:

```text
Game::detail()
-> GameService::getDetailPage($slug)
-> GameModel::getDetailBySlug()
-> ProductModel::getProductsByGame()
-> PriceService::getFinalPrice()
-> PaymentMethodModel::getActive()
-> renderView('pages/games/detail')
```

Jika game tidak ditemukan atau tidak aktif, controller throw `PageNotFoundException`.

### Search

`GET /search/games` dipakai untuk pencarian game.

Flow ideal:

```text
Search controller
-> GameService::searchGames($keyword)
-> GameModel::searchGames()
-> JSON response
```

Minimal keyword di service saat ini 2 karakter.

### Order

Route utama:

- `POST /order/prepare`
- `POST /order/create`
- `GET /order/list`
- `GET /order/(:num)`

Flow prepare:

```text
Order::prepare()
-> session user_id wajib ada
-> CheckoutService::prepareOrder()
-> validate game, product, stock, customer_id, optional payment method
-> PriceService
-> JSON result
```

Flow create:

```text
Order::create()
-> session user_id wajib ada
-> OrderService::create()
-> validate product, game, customer_id
-> generate invoice via OrderModel
-> insert orders
-> redirect payment/{payment_token}
```

Catatan: project sekarang tidak memproses game provider eksternal. Order berhenti di status `pending` setelah dibuat, lalu diarahkan ke halaman pembayaran.

### Payment

Route utama:

- `GET /payment/(:any)`
- `GET|POST /payment/check`

Flow detail:

```text
Payment::detail($token)
-> PaymentService::getDetailPage($token)
-> OrderModel::findByTokenWithGame()
-> split row menjadi order + game
-> renderView('pages/payment/detail')
```

Flow cek invoice:

```text
Payment::check()
-> PaymentService::checkInvoice($invoice)
-> OrderModel::findByInvoice()
-> redirect payment/{payment_token}
```

## BaseController

Semua controller sebaiknya extend `App\Controllers\BaseController`.

Yang sudah disiapkan:

- helpers: `vite`, `url`, `form`;
- `$this->session`;
- `$this->baseModel`;
- `$this->setting_service`;
- `$this->userModel`;
- `$this->base_data`;
- `renderView($view, $data)` untuk merge data global;
- `responseJson($success, $message, $data)` untuk response JSON standar.

Jangan bypass `renderView()` untuk halaman publik kecuali ada alasan khusus, karena view butuh meta, SEO, user, dan alert global.

## Services

Service yang ada:

- `AuthService`
- `CheckoutService`
- `GameService`
- `HomeService`
- `OrderService`
- `PaymentService`
- `PriceService`
- `SettingService`
- `UserService`

Jika menambah service:

1. Buat class di `app/Services`.
2. Taruh business logic di method public yang namanya jelas.
3. Daftarkan di `app/Config/Services.php` bila akan dipakai lewat service locator.
4. Di controller, prefer pola yang konsisten dengan controller sekitar.

Beberapa controller existing memakai `single_service(...)`, tetapi fungsi itu tidak ditemukan di `app/Common.php`. Kalau menyentuh bagian ini, cek dulu apakah helper tersebut tersedia dari tempat lain. Untuk code baru, pola CI4 standar `service('namaService')` atau `new ServiceClass()` yang eksplisit lebih mudah dilacak.

## Models dan Database

Tabel utama dari migration:

- `banner`
- `flashsale`
- `game_categories`
- `games`
- `product`
- `users`
- `admin`
- `utilities`
- `credentials`
- `payment_methods`
- `orders`

Kolom legacy seperti `games.provider`, `product.provider`, `orders.game_provider`, dan `orders.payment_provider` masih ada di migration/database, tetapi jangan jadikan itu alasan membuat ulang folder `Providers`. Untuk sekarang anggap kolom itu data legacy/opsional.

Order memakai `payment_token` yang dibuat otomatis di `OrderModel::insert()` dengan `bin2hex(random_bytes(16))`.

Invoice dibuat oleh `OrderModel::generateInvoice()` dengan format:

```text
INV/YYYYMMDD/0001
```

## Auth dan Filter

`AuthFilter` redirect user yang belum login ke `auth/login`.

Di `app/Config/Filters.php`, alias `auth` diterapkan untuk:

```text
user
user/*
```

Beberapa endpoint order melakukan session check manual di controller. Kalau menambah endpoint protected, pilih salah satu pola dan konsisten:

- filter route untuk halaman/area yang jelas protected;
- session check manual untuk endpoint JSON yang butuh response JSON custom.

## Response Convention

Untuk JSON/action service, gunakan bentuk:

```php
[
    'success' => true,
    'message' => 'Pesan singkat',
    'data'    => [],
]
```

Untuk halaman HTML:

```text
Controller -> Service -> data array -> renderView()
```

Untuk error halaman detail yang tidak ditemukan, gunakan `PageNotFoundException`.

## Aturan Untuk Agent Baru

Sebelum coding:

1. Baca `docs/agents/backend-flow.md` ini.
2. Baca route terkait di `app/Config/Routes.php`.
3. Baca controller, service, dan model yang akan disentuh.
4. Jangan tambah layer baru kalau MVC + Service cukup.
5. Jangan hidupkan lagi folder `app/Providers`.
6. Jangan taruh query SQL di controller.
7. Jangan taruh session/redirect/view di model.
8. Kalau menambah tabel atau kolom, buat migration.
9. Kalau menambah business rule, taruh di service.
10. Setelah edit PHP, jalankan minimal syntax check untuk file yang disentuh.

## Checklist Fitur Baru

Untuk fitur backend baru, ikuti urutan ini:

1. Tambah route di `app/Config/Routes.php`.
2. Tambah method controller.
3. Tambah atau update service.
4. Tambah atau update model query.
5. Tambah migration bila schema berubah.
6. Tambah view bila output HTML.
7. Validasi input di controller atau service sesuai konteks.
8. Return response konsisten.
9. Test manual route utama.
10. Jalankan syntax check PHP untuk file yang berubah.

