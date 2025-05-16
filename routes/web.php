<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('welcome');
})->name('home');

Route::get('/accordion', function () {
    return view('accordion');
})->name('accordion');

Route::get('/carousel', function () {
    return view('carousel');
})->name('carousel');

Route::get('/modal', function () {
    return view('modal');
})->name('modal');

Route::get('/collapse', function () {
    return view('collapse');
})->name('collapse');

Route::get('/dial', function () {
    return view('dial');
})->name('dial');

Route::get('/dismiss', function () {
    return view('dismiss');
})->name('dismiss');

Route::get('/drawer', function () {
    return view('drawer');
})->name('drawer');

Route::get('/dropdown', function () {
    return view('dropdown');
})->name('dropdown');

Route::get('/popover', function () {
    return view('popover');
})->name('popover');

Route::get('/tooltip', function () {
    return view('tooltip');
})->name('tooltip');

Route::get('/input-counter', function () {
    return view('input-counter');
})->name('input-counter');

Route::get('/tabs', function () {
    return view('tabs');
})->name('tabs');

Route::get('/datepicker', function () {
    return view('datepicker');
})->name('datepicker');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');
// Route::view('roles', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('roles');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('roles', \App\Livewire\RoleList::class)->name('roles');
    Route::get('roles/form', \App\Livewire\RoleForm::class)->name('roles.form');
    Route::get('roles/edit/{role}', \App\Livewire\RoleForm::class)->name('roles.edit');

    Route::get('users', \App\Livewire\UserList::class)->name('users');
    Route::get('users/form', \App\Livewire\userForm::class)->name('users.form');
    Route::get('users/edit/{user}', \App\Livewire\userForm::class)->name('users.edit');

    Route::get('dorms', \App\Livewire\DormList::class)->name('dorms');
    Route::get('dorms/form', \App\Livewire\DormForm::class)->name('dorms.form');
    Route::get('dorms/edit/{dorm}', \App\Livewire\DormForm::class)->name('dorms.edit');
    Route::get('dorms/member/{dorm}', \App\Livewire\DormMember::class)->name('dorms.member');

    Route::get('class', \App\Livewire\IslamicClassList::class)->name('class');
    Route::get('class/form', \App\Livewire\IslamicClassForm::class)->name('class.form');
    Route::get('class/edit/{islamicClass}', \App\Livewire\IslamicClassForm::class)->name('class.edit');
    Route::get('class/member/{islamic_class}', \App\Livewire\IslamicClassMember::class)->name('class.member');

    Route::get('students', \App\Livewire\StudentList::class)->name('students');
    Route::get('students/form', \App\Livewire\StudentForm::class)->name('students.form');
    Route::get('students/edit/{student}', \App\Livewire\StudentForm::class)->name('students.edit');
    Route::get('students/detail/{student}', \App\Livewire\StudentDetail::class)->name('students.detail');

    Route::get('academic-years', \App\Livewire\AcademicYearList::class)->name('academic-years');
    Route::get('academic-years/form', \App\Livewire\AcademicYearForm::class)->name('academic-years.form');
    Route::get('academic-years/edit/{academicYear}', \App\Livewire\AcademicYearForm::class)->name('academic-years.edit');

    Route::get('permits', \App\Livewire\PermitList::class)->name('permits');
    Route::get('permits/form', \App\Livewire\PermitForm::class)->name('permits.form');
    Route::get('permits/edit/{permit}', \App\Livewire\AcademicYearForm::class)->name('permits.edit');
});

require __DIR__ . '/auth.php';
