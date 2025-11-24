<?php

use App\Livewire\Pages\Cart;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Products\Show;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::get('/', Home::class)
        ->name('home');

    Route::get('products/{product}', Show::class)
        ->name('products.show');

    Route::get('cart', Cart::class)
        ->name('cart');

    Route::view('dashboard', 'dashboard')
        ->middleware(['verified'])
        ->name('dashboard');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    $twoFactorMiddleware = when(
        Features::canManageTwoFactorAuthentication()
            && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
        ['password.confirm'],
        [],
    );
    /** @var array<string> $twoFactorMiddleware */
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware($twoFactorMiddleware)
        ->name('two-factor.show');
});
