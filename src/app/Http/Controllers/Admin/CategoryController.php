<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Legacy categories entrypoint. Redirect to business categories.
 */
class CategoryController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.business-categories.index');
    }
}
