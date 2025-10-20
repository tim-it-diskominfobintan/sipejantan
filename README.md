<<<<<<< HEAD
<<<<<<< HEAD
# 🚀 Core Laravel 8

Project adalah boilerplate Laravel 8 yang dilengkapi dengan autentikasi bawaan dari laravel/ui. Dirancang untuk mempercepat proses pengembangan aplikasi Laravel di `Lingkungan Pemerintah Kabupaten Bintan` dengan struktur yang mudah dipahami dan fitur login siap pakai. Dan sudah terintegrasi dengan [`Bintan Single Sign On`](https://sso.bintankab.go.id).

## ‼️ Catatan Development dan Security

- Selalu gunakan **DB Transaction** `DB::beginTransaction()` **jika** melakukan operasi 2 tabel atau lebih.
- Jangan lupa menambahkan `allowOnlyAjax($request)` pada Controller jika itu adalah fungsi AJAX.
- Selalu gunakan `handleAjaxJqueryError()` pada error handling AJAX jQuery.
- Simpan **File** penting seperti `KTP, KK, SIM atau Kartu Identitas lain` hanya di **private** driver storage `Storage::disk('private')->putFileAs($path, $file, $fileName)`. Nanti cara aksesnya lihat di **route** saja. Sudah author cantumkan.

## 🛠 Fitur

- [x] Auth Basic (Login, Register)
- [x] Admin Panel
- [x] Integrasi Bintan Single Sign On
- [x] Manajemen Role
- [x] Manajemen Permission
- [x] Manajemen OPD Bintan
- [x] Manajemen User
- [x] Manajemen Session
- [x] Helper PHP dan Javascript
- [ ] Developer Panel (Coming Soon)
- [ ] Pemilihan theme (Coming Soon)
- [ ] Handling error untuk Axios `handleAjaxAxiosError()`

## 📦 Requirement

- PHP >= 7.3
- Composer
- MySQL >= 5.7 / SQLite

## 🚀 Instalasi

1. Buat database dengan nama `core_laravel_8` atau nama aplikasi anda

2. Clone project dan setup (single command)
```bash
git clone https://github.com/tim-it-diskominfobintan/core-laravel.git && cd core-laravel && composer install && cp .env.example .env && php artisan key:generate
```

3. Sesuaikan `.env` nya, lalu jalankan
```bash
php artisan migrate && php artisan db:seed
```

4. Hapus riwayat git
```bash
rm -rf .git
```

5. Hapus riwayat git dan inisiasi git baru
```bash
rm -rf .git && git init
```

6. Tambahkan ke repository git baru
```bash
git remote add origin https://github.com/com/tim-it-diskominfobintan/your-app.git
```

7. Tinggal menyesuaikan fitur dengan aplikasi anda
=======
# sipejantan
Aplikasi Pendataan PJU oleh dishub
>>>>>>> 9996b3385d0ef95f2df87d3818d4b1d5ddd4960f
=======
# sipejantan
Aplikasi Pendataan PJU oleh dishub
>>>>>>> 9996b3385d0ef95f2df87d3818d4b1d5ddd4960f
