<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Auth (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', fn () => redirect()->route('login'))->name('register');
    Route::post('/register', fn () => redirect()->route('login'));
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Language switcher (store in session, redirect back)
Route::get('/language/{locale}', function (string $locale) {
    $locales = config('locales.available', ['en' => 'English', 'el' => 'Ελληνικά']);
    if (isset($locales[$locale])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

$tours = [
    [
        'slug' => 'cretan-village-walk',
        'title' => 'Cretan Village Walk',
        'excerpt' => 'A relaxed guided walk through traditional villages, local workshops and hidden alleys.',
        'image' => 'https://images.pexels.com/photos/672532/pexels-photo-672532.jpeg',
        'duration' => 'Half day',
    ],
    [
        'slug' => 'coastal-boat-escape',
        'title' => 'Coastal Boat Escape',
        'excerpt' => 'Discover small coves, clear waters and secret swimming spots along the coast.',
        'image' => 'https://images.pexels.com/photos/1001682/pexels-photo-1001682.jpeg',
        'duration' => 'Full day',
    ],
    [
        'slug' => 'wine-olive-tasting',
        'title' => 'Wine & Olive Tasting',
        'excerpt' => 'Taste local wines, olive oil and authentic products in a countryside setting.',
        'image' => 'https://images.pexels.com/photos/1407846/pexels-photo-1407846.jpeg',
        'duration' => '3 hours',
    ],
];

$businesses = [
    [
        'slug' => 'azure-breeze-hotel',
        'title' => 'Azure Breeze Hotel',
        'category' => 'Hotels',
        'description' => 'Boutique seafront hotel with panoramic views and curated local experiences.',
        'address' => 'Seaside Avenue 12, Sunset Bay',
        'phone' => '+30 210 000 0000',
        'email' => 'info@azurebreezehotel.com',
        'website' => 'https://azurebreezehotel.com',
        'featured_image' => 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg',
        'featured' => true,
        'opening_hours' => 'Daily 07:00 – 23:00',
        'social' => [
            'facebook' => '#',
            'instagram' => '#',
            'tripadvisor' => '#',
        ],
        'category_slug' => 'hotels',
    ],
    [
        'slug' => 'harbor-taste-tavern',
        'title' => 'Harbor Taste Tavern',
        'category' => 'Restaurants / Taverns',
        'description' => 'Family-run tavern serving fresh seafood and traditional recipes.',
        'address' => 'Harbor Street 5, Old Port',
        'phone' => '+30 210 000 0001',
        'email' => 'hello@harbortaste.gr',
        'website' => 'https://harbortaste.gr',
        'featured_image' => 'https://images.pexels.com/photos/260922/pexels-photo-260922.jpeg',
        'featured' => true,
        'opening_hours' => 'Daily 12:00 – 00:00',
        'social' => [
            'facebook' => '#',
            'instagram' => '#',
        ],
        'category_slug' => 'restaurants-taverns',
    ],
    [
        'slug' => 'olive-grove-retreat-villas',
        'title' => 'Olive Grove Retreat Villas',
        'category' => 'Apartments / Villas',
        'description' => 'Elegant villas tucked among olive trees with private pools.',
        'address' => 'Olive Valley Road 21, Hillside',
        'phone' => '+30 210 000 0002',
        'email' => 'stay@olivegroveretreat.com',
        'website' => 'https://olivegroveretreat.com',
        'featured_image' => 'https://images.pexels.com/photos/2406773/pexels-photo-2406773.jpeg',
        'featured' => false,
        'opening_hours' => 'Check-in 15:00 – Check-out 11:00',
        'social' => [
            'instagram' => '#',
        ],
        'category_slug' => 'apartments-villas',
    ],
];

$businessCategories = [
    'hotels' => 'Hotels',
    'apartments-villas' => 'Apartments / Villas',
    'restaurants-taverns' => 'Restaurants / Taverns',
    'cafes-bars' => 'Cafes / Bars',
    'car-rentals' => 'Car Rentals',
    'boat-rentals' => 'Boat Rentals',
    'travel-agencies' => 'Travel Agencies',
    'local-shops' => 'Local Shops / Traditional Products',
    'activities-excursions' => 'Activities / Excursions',
    'wellness-spa' => 'Wellness / Spa',
];

// Home
Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

// Tours (optional, keep in-memory for now)
Route::get('/tours', function () use ($tours) {
    return view('tours', ['tours' => $tours]);
})->name('tours');

// Business directory
Route::get('/businesses', [\App\Http\Controllers\BusinessController::class, 'index'])->name('businesses');
Route::get('/businesses-on-map', [\App\Http\Controllers\BusinessController::class, 'onMap'])->name('businesses.on_map');
Route::get('/businesses/{slug}', [\App\Http\Controllers\BusinessController::class, 'show'])->name('businesses.show');
Route::post('/businesses/{slug}/reviews', [\App\Http\Controllers\BusinessReviewController::class, 'store'])->name('businesses.reviews.store');

// Places to visit (public, read-only)
Route::get('/places', [\App\Http\Controllers\PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{slug}', [\App\Http\Controllers\PlaceController::class, 'show'])->name('places.show');

// Static pages
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/discover', 'discover')->name('discover');

// Legal pages (content from settings, EN/EL)
Route::get('/privacy-policy', function () {
    $settings = \App\Models\Setting::getSettingsObject();
    $content = app()->getLocale() === 'el'
        ? ($settings->privacy_policy_el ?? $settings->privacy_policy ?? '')
        : ($settings->privacy_policy ?? '');
    return view('privacy-policy', ['content' => $content]);
})->name('privacy');
Route::get('/terms-of-use', function () {
    $settings = \App\Models\Setting::getSettingsObject();
    $content = app()->getLocale() === 'el'
        ? ($settings->terms_of_use_el ?? $settings->terms_of_use ?? '')
        : ($settings->terms_of_use ?? '');
    return view('terms-of-use', ['content' => $content]);
})->name('terms');

// Admin panel (auth + admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('businesses', \App\Http\Controllers\Admin\BusinessController::class)->except('show');
    Route::get('/business-reviews', [\App\Http\Controllers\Admin\BusinessReviewController::class, 'index'])->name('business-reviews.index');
    Route::patch('/business-reviews/{businessReview}', [\App\Http\Controllers\Admin\BusinessReviewController::class, 'update'])->name('business-reviews.update');
    Route::delete('/business-reviews/{businessReview}', [\App\Http\Controllers\Admin\BusinessReviewController::class, 'destroy'])->name('business-reviews.destroy');

    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::resource('business-categories', \App\Http\Controllers\Admin\BusinessCategoryController::class)->except('show');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except('show');

    Route::resource('subscription-plans', \App\Http\Controllers\Admin\SubscriptionPlanController::class)->except('show');
    Route::resource('subscriptions', \App\Http\Controllers\Admin\BusinessSubscriptionController::class)->except('show');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::get('/statistics', [\App\Http\Controllers\Admin\VisitorStatisticsController::class, 'index'])->name('statistics.index');
    Route::post('/statistics/reset', [\App\Http\Controllers\Admin\VisitorStatisticsController::class, 'reset'])->name('statistics.reset');

    Route::resource('place-categories', \App\Http\Controllers\Admin\PlaceCategoryController::class)->except('show');
    Route::resource('villages', \App\Http\Controllers\Admin\VillageController::class)->except('show');
    Route::resource('places', \App\Http\Controllers\Admin\PlaceController::class)->except('show');

    Route::get('/media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('media.store');
    Route::delete('/media', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('/media/move', [\App\Http\Controllers\Admin\MediaController::class, 'moveImage'])->name('media.move');
    Route::get('/media/download', [\App\Http\Controllers\Admin\MediaController::class, 'download'])->name('media.download');
    Route::post('/media/folders', [\App\Http\Controllers\Admin\MediaController::class, 'createFolder'])->name('media.folders.create');
    Route::put('/media/folders', [\App\Http\Controllers\Admin\MediaController::class, 'renameFolder'])->name('media.folders.rename');
    Route::delete('/media/folders', [\App\Http\Controllers\Admin\MediaController::class, 'destroyFolder'])->name('media.folders.destroy');
    Route::post('/media/folders/delete', [\App\Http\Controllers\Admin\MediaController::class, 'destroyFolder'])->name('media.folders.destroy.post');

    // Page texts (Text CMS)
    Route::get('/page-texts', [\App\Http\Controllers\Admin\PageTextController::class, 'index'])->name('page-texts.index');
    Route::post('/page-texts', [\App\Http\Controllers\Admin\PageTextController::class, 'store'])->name('page-texts.store');
});

// User dashboard (auth + user role)
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'business'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/business/{business}/edit', [DashboardController::class, 'editBusiness'])->name('business.edit');
    Route::put('/business/{business}', [DashboardController::class, 'updateBusiness'])->name('business.update');
    Route::get('/business/reviews', [DashboardController::class, 'businessReviews'])->name('business.reviews.index');
    Route::patch('/business/reviews/{business_review}', [DashboardController::class, 'updateBusinessReview'])->name('business.reviews.update');
    Route::delete('/business/reviews/{business_review}', [DashboardController::class, 'destroyBusinessReview'])->name('business.reviews.destroy');
    Route::get('/subscription', [DashboardController::class, 'subscription'])->name('subscription');
});