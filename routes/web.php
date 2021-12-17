<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
     return redirect(route('login'));
});
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::get('login', 'Auth\LoginController@login')->name('login');
Route::post('login', 'Auth\LoginController@authenticate')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('forget-password', 'Auth\ForgotPasswordController@showForgetPasswordForm')->name('forget.password.get');
Route::post('forget-password', 'Auth\ForgotPasswordController@submitForgetPasswordForm')->name('forget.password.post'); 
Route::get('reset-password/{token}', 'Auth\ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
Route::post('reset-password', 'Auth\ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');


Auth::routes([
    'register' => false, // Registration Routes...
    'login' => false,
    'logout' => false,
]);


Route::prefix(\Config::get('constants.admin_url.admin'))->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/profile', 'ProfileController@profile')->name('profile');
    Route::post('/profile-update', 'ProfileController@profileUpdate')->name('profile.update');
    Route::post('/profile-email-update', 'ProfileController@profileEmailUpdate')->name('profile.email.update');
    Route::post('/profile-change-password', 'ProfileController@profileChangePassword')->name('profile.change.password');

    // ...................Manage Roles.........................
    Route::get('roles', ['as' => "roles", 'uses' => 'RolesController@index'])->middleware('can:roles');
    Route::get('roles/add', ['as' => "roles.add", 'uses' => 'RolesController@add'])->middleware('can:roles.add');
    Route::post('roles/add', ['as' => "roles.store", 'uses' => 'RolesController@store'])->middleware('can:roles.add');
    Route::get('roles/edit/{id}', ['as' => "roles.edit", 'uses' => 'RolesController@edit'])->middleware('can:roles.edit');
    Route::post('roles/update/{id}', ['as' => "roles.update", 'uses' => 'RolesController@update'])->middleware('can:roles.edit');
    Route::post('roles/name-exist', ['as' => "roles.name-exist", 'uses' => 'RolesController@nameExist']);
    Route::post('roles/display-name-exist', ['as' => "roles.display-name-exist", 'uses' => 'RolesController@displayNameExist']);
    // .............Manage Roles...............................
    // .............Common Work...................
    Route::post('status-change', ['as' => 'status-change', 'uses' => 'CommonController@ChangeStatus']);
    Route::post('feature-change', ['as' => 'feature-change', 'uses' => 'CommonController@ChangeFeature']);
    Route::post('delete-all', ['as' => 'delete-all', 'uses' => 'CommonController@Destroy']);
    // .............Common Work...................

    // ...................Manage Users.........................
    Route::get('users', ['as' => "users", 'uses' => 'UsersController@index'])->middleware('can:users');
    Route::get('users/add', ['as' => "users.add", 'uses' => 'UsersController@add'])->middleware('can:users.add');
    Route::post('users/add', ['as' => "users.store", 'uses' => 'UsersController@store'])->middleware('can:users.add');
    Route::get('users/edit/{id}', ['as' => "users.edit", 'uses' => 'UsersController@edit'])->middleware('can:users.edit');
    Route::post('users/update/{id}', ['as' => "users.update", 'uses' => 'UsersController@update'])->middleware('can:users.edit');
    Route::post('users/username-exist', ['as' => "users.username-exist", 'uses' => 'UsersController@userNameExist']);
    Route::post('users/email-exist', ['as' => "users.email-exist", 'uses' => 'UsersController@emailExist']);
    Route::post('users/phone-exist', ['as' => "users.phone-exist", 'uses' => 'UsersController@phoneExist']);
    // .............Manage Users...............................

    // ...................Manage Customers.........................
    Route::get('customers', ['as' => "customers", 'uses' => 'CustomerController@index'])->middleware('can:customers');
    Route::get('customers/add', ['as' => "customers.add", 'uses' => 'CustomerController@add'])->middleware('can:customers.add');
    Route::post('customers/add', ['as' => "customers.store", 'uses' => 'CustomerController@store'])->middleware('can:customers.add');
    Route::get('customers/edit/{id}', ['as' => "customers.edit", 'uses' => 'CustomerController@edit'])->middleware('can:customers.edit');
    Route::post('customers/update/{id}', ['as' => "customers.update", 'uses' => 'CustomerController@update'])->middleware('can:customers.edit');
    // .............Manage Customers...............................

    // ...................Manage Cms Pages.........................
    Route::get('cms-pages', ['as' => "cms.pages", 'uses' => 'CmsController@index'])->middleware('can:cms.pages');
    Route::get('cms-pages/add', ['as' => "cms.pages.add", 'uses' => 'CmsController@add']);
    Route::post('cms-pages/add', ['as' => "cms.pages.store", 'uses' => 'CmsController@store']);
    Route::get('cms-pages/edit/{id}', ['as' => "cms.pages.edit", 'uses' => 'CmsController@edit'])->middleware('can:cms.pages.edit');
    Route::post('cms-pages/update/{id}', ['as' => "cms.pages.update", 'uses' => 'CmsController@update'])->middleware('can:cms.pages.edit');
    Route::post('cms-pages/name-exist', ['as' => "cms.pages.name-exist", 'uses' => 'CmsController@nameExist']);
    Route::post('cms-pages/title-exist', ['as' => "cms.pages.title-exist", 'uses' => 'CmsController@titleExist']);
    // .............Manage Cms Pages...............................

    // .............Manage General Settings...............................
    Route::get('general-settings', ['as' => "general.settings", 'uses' => 'GeneralSettingsController@index'])->middleware('can:general.settings');
    Route::get('general-settings/edit/{id}', ['as' => "general.settings.edit", 'uses' => 'GeneralSettingsController@edit'])->middleware('can:general.settings.edit');
    Route::post('general-settings/update/{id}', ['as' => "general.settings.update", 'uses' => 'GeneralSettingsController@update'])->middleware('can:general.settings.edit');
    // .............Manage General Settings...............................

    // ...................Manage Categories.........................
    Route::get('categories', ['as' => "categories", 'uses' => 'CategoryController@index'])->middleware('can:categories');
    Route::get('categories/add', ['as' => "categories.add", 'uses' => 'CategoryController@add'])->middleware('can:categories.add');
    Route::post('categories/add', ['as' => "categories.store", 'uses' => 'CategoryController@store'])->middleware('can:categories.add');
    Route::get('categories/edit/{id}', ['as' => "categories.edit", 'uses' => 'CategoryController@edit'])->middleware('can:categories.edit');
    Route::post('categories/update/{id}', ['as' => "categories.update", 'uses' => 'CategoryController@update'])->middleware('can:categories.edit');
    Route::post('categories/title-exist', ['as' => "categories.title-exist", 'uses' => 'CategoryController@titleExist']);
    // .............Manage Categories...............................

    // ...................Manage Categories.........................
    Route::get('videos', ['as' => "videos", 'uses' => 'VideoController@index'])->middleware('can:videos');
    Route::get('videos/add', ['as' => "videos.add", 'uses' => 'VideoController@add'])->middleware('can:videos.add');
    Route::post('videos/add', ['as' => "videos.store", 'uses' => 'VideoController@store'])->middleware('can:videos.add');
    Route::get('videos/edit/{id}', ['as' => "videos.edit", 'uses' => 'VideoController@edit'])->middleware('can:videos.edit');
    Route::post('videos/update/{id}', ['as' => "videos.update", 'uses' => 'VideoController@update'])->middleware('can:videos.edit');
    Route::post('videos/title-exist', ['as' => "videos.title-exist", 'uses' => 'VideoController@titleExist']);
    Route::post('approve-reject', ['as' => 'approve-reject', 'uses' => 'VideoController@videoApproveReject']);

    // .............Manage Categories...............................

    // ...................Manage Banners.........................
    Route::get('banners', ['as' => "banners", 'uses' => 'BannersController@index'])->middleware('can:banners');
    Route::get('banners/add', ['as' => "banners.add", 'uses' => 'BannersController@add'])->middleware('can:banners.add');
    Route::post('banners/add', ['as' => "banners.store", 'uses' => 'BannersController@store'])->middleware('can:banners.add');
    Route::get('banners/edit/{id}', ['as' => "banners.edit", 'uses' => 'BannersController@edit'])->middleware('can:banners.edit');
    Route::post('banners/update/{id}', ['as' => "banners.update", 'uses' => 'BannersController@update'])->middleware('can:banners.edit');
    Route::post('banners/name-exist', ['as' => "banners.name-exist", 'uses' => 'BannersController@nameExist']);


    // ...................Manage Plans.........................
    Route::get('plans', ['as' => "plans", 'uses' => 'PlansController@index'])->middleware('can:plans');
    Route::get('plans/add', ['as' => "plans.add", 'uses' => 'PlansController@add'])->middleware('can:plans.add');
    Route::post('plans/add', ['as' => "plans.store", 'uses' => 'PlansController@store'])->middleware('can:plans.add');
    Route::get('plans/edit/{id}', ['as' => "plans.edit", 'uses' => 'PlansController@edit'])->middleware('can:plans.edit');
    Route::post('plans/update/{id}', ['as' => "plans.update", 'uses' => 'PlansController@update'])->middleware('can:plans.edit');
    Route::post('plans/title-exist', ['as' => "plans.title-exist", 'uses' => 'PlansController@titleExist']);


    // Question - Answer Routes
    Route::get('question-answer', ['as' => "question-answer", 'uses' => 'QuestionAnswersController@index'])->middleware('can:question-answer');

    Route::post('question-answer/video-approve-reject', ['as' => "question-answer.video-approve-reject", 'uses' => 'QuestionAnswersController@videoApproveReject']);

    Route::post('question-answer/save-form', ['as' => "question-answer.save-form", 'uses' => 'QuestionAnswersController@saveForm']);

    Route::get('question-answer/get-answerlist', ['as' => "question-answer.get-answerlist", 'uses' => 'QuestionAnswersController@GetAnswerList']);

    // Route::post('question-answer', ['as' => "question-answer.store", 'uses' => 'QuestionAnswersController@store'])->middleware('can:question-answer.add');

    // Route::get('/home', 'HomeController@index')->name('home');
});

