# Panduan Deployment Cepat ke Niagahoster (cPanel / VPS)

Dokumen ini berisi panduan deployment tercepat untuk menaikkan aplikasi **Recovest Finance & Recon** ke **Niagahoster cPanel Hosting** (Shared/Cloud Hosting) atau **Niagahoster VPS**.

---

## OPSI 1: DEPLOY CEPAT KE NIAGAHOSTER cPANEL (10 MENIT)

Ini adalah metode **tercepat & termudah** jika Anda menggunakan paket Niagahoster Web Hosting / Cloud Hosting cPanel.

### Yang Perlu Disiapkan di cPanel Niagahoster:
1. Akses masuk **cPanel Niagahoster** (melalui Member Area Niagahoster).
2. Nama **Domain / Subdomain** yang sudah aktif.
3. Database MySQL baru yang dibuat di cPanel.

---

### Langkah Demi Langkah Deployment cPanel:

#### Langkah 1: Buat Database MySQL di cPanel Niagahoster
1. Login ke cPanel -> Buka menu **MySQL® Databases**.
2. Buat Database Baru (contoh: `u1234567_recovest`).
3. Buat User MySQL Baru & Password (contoh: `u1234567_user` / `PasswordAman123!`).
4. Tambahkan User ke Database & centang **ALL PRIVILEGES**.

#### Langkah 2: Upload File Project ke cPanel
1. Compress folder `recovest-finance` di laptop Anda menjadi file `recovest-finance.zip`.
2. Di cPanel -> Buka **File Manager**.
3. Masuk ke folder domain Anda (`public_html` atau nama subfolder domain).
4. Upload `recovest-finance.zip` lalu **Extract**.

#### Langkah 3: Konfigurasi File `.env` di File Manager cPanel
1. Di File Manager -> Edit file `.env`.
2. Ubah konfigurasi database ke MySQL Niagahoster Anda:

```ini
APP_NAME=RecovestFinance
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u1234567_recovest
DB_USERNAME=u1234567_user
DB_PASSWORD=PasswordAman123!

SESSION_DRIVER=database
QUEUE_CONNECTION=sync
```

#### Langkah 4: Arahkan Domain ke Folder `public`
Ada 2 cara mudah di Niagahoster:

- **Cara A (Rekomendasi)**: Di cPanel -> menu **Domains** -> Ubah *Document Root* domain Anda menjadi `public_html/public` (atau `path-project/public`).
- **Cara B**: File `.htaccess` otomatis sudah kami sediakan di root project yang mengarahkan semua *traffic* ke folder `public/`.

#### Langkah 5: Migrasi Database & Seeder
1. Di cPanel -> Buka menu **Terminal** (atau via SSH).
2. Jalankan perintah migrasi:

```bash
php artisan migrate --force
php artisan db:seed --class=DummyBankDataSeeder --force
php artisan storage:link
```

*(Jika cPanel Anda tidak menyediakan fitur Terminal, Anda bisa mengimpor file SQL database melalui phpMyAdmin)*.

#### Langkah 6: Aktifkan SSL Gratis di cPanel
1. Di cPanel -> Buka menu **Lets Encrypt SSL** atau **SSL/TLS Status**.
2. Pilih domain Anda lalu klik **Run AutoSSL** / **Issue SSL**.

---

## OPSI 2: DEPLOY KE VERCEL (FREE SERVERLESS)

Jika ingin deploy gratis tanpa hosting bayaran:
- Hubungkan database cloud **[Supabase.com](https://supabase.com)** (PostgreSQL gratis).
- Import repositori GitHub Anda ke **[Vercel.com](https://vercel.com)** (file `vercel.json` dan `api/index.php` sudah siap pakai).

---

## Kredensial Default Login Aplikasi setelah Deploy

- **URL Dashboard**: `https://<DOMAIN-ANDA>/login`
- **Email Admin**: `admin@recovest.id`
- **Password**: `password123`
- **Registrasi**: Buka registrasi umum di `/register`
