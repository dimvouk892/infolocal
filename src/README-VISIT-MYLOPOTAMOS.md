# Visit Mylopotamos – Travel Portal

Travel portal with **Places Blog** (Σημεία στον Μυλοπόταμο) and **Business Directory** (Τοπικές επιχειρήσεις), admin panel, business dashboard, and subscriptions.

## Setup

1. **Migrations** (requires PHP 8.4+ or adjust `composer.json` platform):

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

   This creates:
   - Admin user: `admin@visitmylopotamos.local` / `password`
   - Place categories (Caves, Villages, Beaches, Coast, Archaeological sites, Towns)
   - Business categories (Hotels, Restaurants, etc.)
   - One subscription plan (Annual)

2. **First admin**: Log in at `/login` with the seeded admin. Users can register at `/register` (role = user). Assign a business to a user in Admin → Users.

3. **Image uploads**: Images are stored under `storage/app/public`. Create the public link so they are accessible:

   ```bash
   php artisan storage:link
   ```

   Uploaded paths are stored in the DB (e.g. `businesses/xyz.jpg`, `blocks/abc.jpg`). JPG/JPEG only, max 2MB. Old images are deleted when replaced or when the business/block is deleted.

## Structure

### 1. Places Blog (Σημεία στον Μυλοπόταμο)

- **Public**: `/destinations` (list), `/destinations/{slug}` (article).
- **Model**: `Place` – title, slug, featured_image, gallery (JSON), short_description, full_content, place_category_id, coordinates (JSON), status (draft/published), seo_title, seo_description. Pivot: place_tag, place_place (related), place_business (nearby businesses).
- **Admin**: `/admin` → Places / Articles (placeholder CRUD to be implemented).

### 2. Business Directory

- **Public**: `/businesses` (filter by category), `/businesses/{slug}`.
- **Model**: `Business` – name, slug, business_category_id, description, logo, featured_image, gallery, address, phone, email, website, opening_hours (JSON), map_location (JSON), social_links (JSON), status (pending/approved/published), owner_id (User), featured.
- **Admin**: `/admin` → Businesses (placeholder CRUD).

### 3. User Roles

- **Admin** (`role = admin`): Full access to `/admin` (dashboard, places, businesses, categories, tags, media, users, subscriptions, settings).
- **User** (`role = user`): Access only to `/dashboard` (My Business, Subscription status). Ownership: can manage only the business where `owner_id = auth()->id()` (enforced by `BusinessPolicy`).

### 4. Authentication

- Login: `/login`, Register: `/register`, Logout: `POST /logout`.
- Password reset: `/forgot-password`, `/reset-password/{token}`.
- Middleware: `auth`, `admin`, `business`, `subscription` (optional: block dashboard actions when subscription expired).

### 5. Subscriptions

- **Models**: `SubscriptionPlan`, `BusinessSubscription` (business_id, plan_id, start_date, end_date, status).
- **Middleware**: `EnsureSubscriptionActive` – redirects business users without an active subscription to `/dashboard/subscription`.
- Admin can create plans and assign subscriptions to businesses (CRUD to be implemented in admin).

### 6. Frontend

- **Home**: Uses `Place::published()` and `Business::published()` with featured; falls back to in-memory data if DB is empty.
- **Destinations**: From `Place::published()` or fallback.
- **Businesses**: From `Business::published()` with category filter; categories from `BusinessCategory` or fallback.
- Views support both Eloquent models and arrays for backward compatibility.

### 7. Multilingual

- Existing `resources/lang/{en,el,de,fr,ru}/messages.php` and `auth.php` (en, el).
- Use `__('messages.*')` and `__('auth.*')` in views.

## Routes Summary

| Route | Description |
|-------|-------------|
| `GET /` | Home |
| `GET /destinations` | Places list |
| `GET /destinations/{slug}` | Place article |
| `GET /businesses` | Business directory |
| `GET /businesses/{slug}` | Business profile |
| `GET /login`, `POST /login` | Login |
| `GET /register`, `POST /register` | Register |
| `POST /logout` | Logout |
| `GET /admin` | Admin dashboard |
| `GET /admin/places` | Admin places (placeholder) |
| `GET /admin/businesses` | Admin businesses (placeholder) |
| … | Other admin sections |
| `GET /dashboard` | Business dashboard |
| `GET /dashboard/subscription` | Subscription status |

## Next Steps (CRUD)

- Admin: full CRUD for Places, Businesses, Categories, Tags, Users, Subscription plans and assignments, Media uploads, Settings.
- Dashboard: business user edit form (description, contact, hours, gallery), submit for approval.
- Optional: block dashboard edit when subscription expired using `subscription` middleware on edit routes.
