# Deployment on Hostinger (shared hosting)

## Git deployment (αυτόματο)

- Το repo έχει τη **ρίζα** (root) εκτός του Laravel: το Laravel είναι στο φάκελο **`src/`**.
- Στη ρίζα υπάρχουν **`composer.json`** και **`composer.lock`** ώστε το Hostinger να τα βρίσκει. Όταν τρέχει `composer install` στη ρίζα, τρέχει αυτόματα `composer install --no-dev --optimize-autoloader` μέσα στο **`src/`**.
- **Υποχρεωτικά:** Στο Hostinger, ρύθμισε το **Document root** (ή «Domain root») να δείχνει στο **`src/public`** (όχι στη ρίζα του repo). Π.χ. αν το repo clone είναι στο `domains/example.com/public_html`, το document root να είναι `public_html/src/public`.

## PHP & Composer

- **CLI PHP** on Hostinger is 8.2.30. The project is locked to PHP 8.2 via `composer.json` platform config and Symfony 7.x so that `composer install --no-dev --optimize-autoloader` works on production.
- If your **web** PHP version differs, set it to 8.2 in Hostinger (PHP configuration) so it matches CLI and avoids runtime/CLI mismatches.

## Install on production

```bash
composer install --no-dev --optimize-autoloader
```

If you deploy before `.env` exists, run first:

```bash
composer install --no-dev --optimize-autoloader --no-scripts
```

Then after copying/creating `.env` and generating `APP_KEY`:

```bash
php artisan package:discover --ansi
```

If you deploy without running Composer on the server (e.g. you upload `vendor/` from your machine), ensure you built `vendor/` with PHP 8.2 platform:

```bash
composer config platform.php 8.2.30
composer install --no-dev --optimize-autoloader
```

## Migrations & `users.id`

- Production `users` table uses `id` as **INT(11)** (not BIGINT UNSIGNED).
- Migrations that reference `users.id` (e.g. `sessions.user_id`, `businesses.owner_id`, `media.user_id`) use `integer()` and explicit foreign keys so they match INT(11). Do **not** change `users.id` to bigint on production.

## Storage link (shared hosting)

`php artisan storage:link` often fails on shared hosting (e.g. `exec()` or symlinks disabled).

**Option A – Manual symlink (if allowed)**  
From your app root (e.g. `public_html` or `domains/yourdomain/public`):

```bash
ln -s ../storage/app/public public/storage
```

**Option B – No symlinks (copy or direct path)**  
- Copy contents of `storage/app/public` into `public/storage` (and re-copy when you add new uploads), or  
- Serve uploaded files from a different path and configure `config/filesystems.php` and `.env` `FILESYSTEM_DISK` / `APP_URL` accordingly.

After creating `public/storage`, ensure the web server can read it (e.g. same user as the app).

## Checklist

- [ ] PHP 8.2 for both CLI and web on Hostinger
- [ ] `.env` set (APP_KEY, DB_*, etc.)
- [ ] `composer install --no-dev --optimize-autoloader` runs without errors
- [ ] `php artisan migrate --force` (or run migrations manually)
- [ ] `public/storage` exists (symlink or copy) for user uploads
- [ ] Document root points to `public/` (or equivalent)

---

## Αν δεν πετύχει το deploy – γύρνα πίσω με GitKraken

1. **Πριν το πρώτο deploy:** Κάνε **commit** όλες τις αλλαγές (Hostinger fixes) με ένα ξεκάθαρο μήνυμα, π.χ. `Hostinger: PHP 8.2 + migrations users INT(11)`.
2. **Αν στο production σπάσει κάτι:** Άνοιξε το repo στο **GitKraken** → στο γράφημα (αριστερά) **δεξί κλικ** στο commit **πριν** τις αλλαγές Hostinger → **Reset current branch to this commit**.
3. Επίλεξε **Soft** (κρατά αλλαγές ως uncommitted) ή **Hard** (απορρίπτει πλήρως τις αλλαγές) ανάλογα με το τι θες.
4. Μετά το reset, κάνε **push** το branch (αν το remote έχει ήδη το “κακό” commit, θα χρειαστεί **Force push** – το GitKraken το ρωτάει).

Έτσι μπορείς πάντα να γυρίσεις στην προηγούμενη έκδοση αν κάτι πάει στραβά.
