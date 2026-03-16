<?php

namespace App\Providers;

use App\Models\BusinessCategory;
use App\Models\PlaceCategory;
use App\Models\Setting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $settings = \Schema::hasTable('settings')
                    ? Setting::getSettingsObject()
                    : (object) [];
            } catch (\Throwable) {
                $settings = (object) [];
            }
            $view->with('settings', $settings);
        });

        View::composer('partials.header', function ($view) {
            try {
                $placeCategories = PlaceCategory::orderBy('sort_order')->orderBy('name')->get(['slug', 'name']);
            } catch (QueryException) {
                $placeCategories = collect();
            }
            try {
                $businessCategories = BusinessCategory::orderBy('sort_order')->orderBy('name')->get(['slug', 'name']);
            } catch (QueryException) {
                $businessCategories = collect();
            }
            $view->with([
                'placeCategories' => $placeCategories,
                'businessCategories' => $businessCategories,
            ]);
        });
    }
}
