<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\FrontProjectController;
use App\Http\Controllers\FrontServiceController;
use App\Http\Controllers\FrontBlogController;
use App\Http\Controllers\PortfolioChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController as UserProfileController;
use App\Http\Controllers\ResumeController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/projects', [FrontProjectController::class, 'index'])->name('projects');
Route::get('/projects/{slug}', [FrontProjectController::class, 'show'])->name('projects.show');
Route::get('/services', [FrontServiceController::class, 'index'])->name('services');
Route::get('/blog', [FrontBlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [FrontBlogController::class, 'show'])->name('blog.show');
Route::get('/resume', [ResumeController::class, 'index'])->name('resume');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'store'])->name('contact.store');
Route::post('/chatbot/message', PortfolioChatController::class)->name('chatbot.message');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('profiles', ProfileController::class);
    Route::resource('home-sections', HomeSectionController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('social-links', SocialLinkController::class);
    Route::resource('settings', SettingController::class);


});

require __DIR__.'/auth.php';



