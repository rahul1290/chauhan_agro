<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Models\Language;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirection to active locale
Route::get('/', function () {
    $locale = session('locale', config('app.locale', 'en'));
    return redirect("/{$locale}");
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Protected Panel Routes (RBAC)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Language Management
        Route::middleware('role:super-admin,content-manager')->group(function () {
            Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
            Route::post('/languages', [LanguageController::class, 'store'])->name('languages.store');
            Route::put('/languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
            Route::post('/languages/{language}/toggle-status', [LanguageController::class, 'toggleStatus'])->name('languages.toggleStatus');
            Route::post('/languages/{language}/set-default', [LanguageController::class, 'setDefault'])->name('languages.setDefault');
        });

        // CMS Page Management & Sections
        Route::middleware('role:super-admin,content-manager')->group(function () {
            Route::resource('pages', AdminPageController::class);
            Route::get('/pages/{page}/sections', [PageSectionController::class, 'index'])->name('pages.sections.index');
            Route::get('/pages/{page}/sections/create', [PageSectionController::class, 'create'])->name('pages.sections.create');
            Route::post('/pages/{page}/sections', [PageSectionController::class, 'store'])->name('pages.sections.store');
            Route::get('/pages/{page}/sections/{section}/edit', [PageSectionController::class, 'edit'])->name('pages.sections.edit');
            Route::put('/pages/{page}/sections/{section}', [PageSectionController::class, 'update'])->name('pages.sections.update');
            Route::post('/pages/{page}/sections/{section}/reorder/{direction}', [PageSectionController::class, 'reorder'])->name('pages.sections.reorder');
            Route::delete('/pages/{page}/sections/{section}', [PageSectionController::class, 'destroy'])->name('pages.sections.destroy');

            // Banners & Sliders
            Route::resource('banners', BannerController::class);

            // Promotional Popup
            Route::get('/popup', [PopupController::class, 'index'])->name('popup.index');
            Route::post('/popup', [PopupController::class, 'update'])->name('popup.update');
            Route::post('/popup/toggle-status', [PopupController::class, 'toggleStatus'])->name('popup.toggleStatus');

            // Menus
            Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
            Route::post('/menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
            Route::delete('/menu-items/{item}', [MenuController::class, 'deleteItem'])->name('menus.items.destroy');
        });

        // Product & Category Management
        Route::middleware('role:super-admin,product-manager')->group(function () {
            Route::resource('categories', CategoryController::class);
            Route::resource('products', AdminProductController::class);
        });

        // Media Library
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
        Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

        // Enquiry Inbox & CRM
        Route::middleware('role:super-admin,enquiry-manager')->group(function () {
            Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
            Route::get('/enquiries/export/csv', [EnquiryController::class, 'exportCsv'])->name('enquiries.export');
            Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
            Route::put('/enquiries/{enquiry}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.updateStatus');
            Route::post('/enquiries/{enquiry}/notes', [EnquiryController::class, 'addNote'])->name('enquiries.addNote');
        });

        // User Administration & Site Settings (Super Admin Only)
        Route::middleware('role:super-admin')->group(function () {
            Route::resource('users', UserController::class);
            Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Public Multilingual Routes
|--------------------------------------------------------------------------
*/
Route::prefix('{locale}')->where(['locale' => '[a-zA-Z]{2}'])->group(function () {
    // Homepage
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Products Catalogue & Detail
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

    // Contact Us & Enquiry
    Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contact', [ContactController::class, 'submit'])->middleware('throttle:10,1')->name('contact.submit');

    // Dynamic CMS Pages (About Us, Dealer Network, Warranty, Privacy, etc.)
    Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');
});

/*
|--------------------------------------------------------------------------
| Non-Prefixed Fallback Redirects (SEO & Direct Link Friendly)
|--------------------------------------------------------------------------
| Automatically redirects non-prefixed URLs like /about-us, /products to
| /{locale}/about-us based on session/default locale instead of returning 404.
*/
Route::get('/products', function () {
    $locale = session('locale', config('app.locale', 'en'));
    $query = request()->getQueryString();
    return redirect("/{$locale}/products" . ($query ? '?' . $query : ''));
});

Route::get('/products/{slug}', function ($slug) {
    $locale = session('locale', config('app.locale', 'en'));
    return redirect("/{$locale}/products/{$slug}");
});

Route::get('/contact', function () {
    $locale = session('locale', config('app.locale', 'en'));
    return redirect("/{$locale}/contact");
});

Route::get('/{slug}', function ($slug) {
    $locale = session('locale', config('app.locale', 'en'));
    return redirect("/{$locale}/{$slug}");
})->where('slug', '[a-zA-Z0-9\-_]+');
