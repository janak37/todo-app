<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tasks');

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

    Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
});

Route::prefix('livewire')->name('livewire.')->group(function () {
    Route::livewire('hello', 'pages::hello');
});

Route::prefix('livewire')->name('livewire.')->middleware('auth')->group(function () {
    Route::livewire('tasks', 'pages::tasks.index')->name('tasks.index');
    Route::livewire('tasks/create', 'pages::tasks.create')->name('tasks.create');
    Route::livewire('tasks/{task}', 'pages::tasks.show')->name('tasks.show');
    Route::livewire('tasks/{task}/edit', 'pages::tasks.edit')->name('tasks.edit');
});

Route::prefix('livewire')->name('livewire.')->middleware('guest')->group(function () {
    Route::livewire('register', 'pages::auth.register')->name('register');
});