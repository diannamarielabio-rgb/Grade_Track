<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;

// ── Guest ──────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/',          [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login',     [AuthController::class, 'showLogin']);
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Logout (any auth) ──────────────────────────────────────────────────────
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Admin ──────────────────────────────────────────────────────────────────
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',            [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users',                [AdminController::class, 'users'])->name('users');
    Route::post('/users',               [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',    [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',         [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',      [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Approval actions
    Route::patch('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::patch('/users/{user}/reject',  [AdminController::class, 'rejectUser'])->name('users.reject');

    // View all grades
    Route::get('/grades',               [AdminController::class, 'grades'])->name('grades');
});

// ── Teacher ────────────────────────────────────────────────────────────────
Route::middleware(['auth','role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard',              [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/grades',                 [TeacherController::class, 'grades'])->name('grades');
    Route::post('/grades',                [TeacherController::class, 'storeGrade'])->name('grades.store');
    Route::get('/grades/{grade}/edit',    [TeacherController::class, 'editGrade'])->name('grades.edit');
    Route::put('/grades/{grade}',         [TeacherController::class, 'updateGrade'])->name('grades.update');
    Route::delete('/grades/{grade}',      [TeacherController::class, 'destroyGrade'])->name('grades.destroy');
});

// ── Student ────────────────────────────────────────────────────────────────
Route::middleware(['auth','role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/grades',    [StudentController::class, 'grades'])->name('grades');
});

// ── Profile (all roles) ────────────────────────────────────────────────────
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/',         [ProfileController::class, 'show'])->name('show');
    Route::get('/edit',     [ProfileController::class, 'edit'])->name('edit');
    Route::put('/',         [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    Route::post('/picture', [ProfileController::class, 'uploadPicture'])->name('picture');
});
