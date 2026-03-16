# Testing – Έλεγχος λειτουργίας site

Ο φάκελος `tests/Testing` περιέχει feature tests που ελέγχουν ότι το site λειτουργεί σωστά (public σελίδες, auth, admin, user dashboard).

## Τι ελέγχεται

- **Public:** Αρχική, login, register, about, contact, discover, businesses, places, privacy, terms
- **Auth:** Login, register, logout
- **Admin:** Πρόσβαση dashboard, users, businesses, subscriptions, settings· μη-admin δεν μπαίνει στο admin
- **User dashboard:** Χρήστης με επιχείρηση μπαίνει στο dashboard και στη σελίδα subscription· guest ανακατεύθυνση στο login
- **Business listing:** Εμφάνιση published business

## Πώς τρέχεις τα tests

**Μέσα στο Docker (συνιστάται):**

```bash
# Από το root του project (laravel-docker)
docker compose exec app php artisan test tests/Testing/SiteTest.php
```

**Μόνο το suite "Testing":**

```bash
docker compose exec app php artisan test --testsuite=Testing
```

**Όλα τα tests του project:**

```bash
docker compose exec app php artisan test
```

Τα tests τρέχουν με SQLite in-memory και `RefreshDatabase` (migrations τρέχουν πριν από κάθε test).
