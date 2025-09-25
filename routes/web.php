<?php

use App\Livewire\Auth\LoginComponent;
use App\Livewire\Backend\Overview\IndexComponent as OverviewIndexComponent;
use App\Livewire\Backend\SystemSetting\IndexComponent as SystemSmtpIndexComponent;
use App\Livewire\Backend\User\DetailsComponent as UserDetailsComponent;
use App\Livewire\Backend\User\IndexComponent as UserIndexComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', LoginComponent::class)->name('home');
// auth routes
Route::get('login', LoginComponent::class)->name('login');
Route::middleware(['admin'])->group(function () {
    // Backend Overview
    Route::get('dashboard', OverviewIndexComponent::class)->name('dashboard');
    // Backend Users
    Route::get('users', UserIndexComponent::class)->name('users');
    Route::get('users/{user}', UserDetailsComponent::class)->name('users.details');
    // Smtp Server Settings
    Route::get('settings', SystemSmtpIndexComponent::class)->name('settings');
});

// API routes
require __DIR__.'/api.php';
