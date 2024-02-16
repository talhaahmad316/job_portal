<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[JobsController::class,'index'])->name('jobs');

Route::group(['user'],function(){
    Route::group(['middleware'=>'guest'],function(){
        Route::get('user/register',[AuthController::class,'register'])->name('user.register');
        Route::post('user/process-register',[AuthController::class,'processregistretion'])->name('user.processregistretion');
        Route::get('user/login',[AuthController::class,'login'])->name('user.login');
        Route::post('user/auth',[AuthController::class,'auth'])->name('user.auth'); 
    });
    Route::group(['middleware'=>'auth'],function(){
        Route::get('user/profile',[AuthController::class,'profile'])->name('user.profile');
        Route::put('user/updated-profile',[AuthController::class,'updateProfile'])->name('user.updateprofile');
        Route::get('user/logout',[AuthController::class,'logout'])->name('user.logout');
        Route::post('user/updated-profile-pic',[AuthController::class,'updateProfilePic'])->name('user.updateprofilepic');
        Route::get('user/create-job',[JobController::class,'createJob'])->name('user.createJob');
        Route::post('user/save-job',[JobController::class,'saveJob'])->name('user.saveJob');
        Route::get('user/my-jobs',[JobController::class,'myJobs'])->name('user.myJobs');
        Route::get('user/my-jobs/edit/{jobId}',[JobController::class,'editJobs'])->name('user.editJobs');
        Route::post('user/update-job/{jobId}',[JobController::class,'updateJob'])->name('user.updateJob');
        Route::post('user/delete-job',[JobController::class,'deleteJob'])->name('user.deleteJob');
    });
});