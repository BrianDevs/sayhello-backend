<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
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
    if(!Auth::check()){
        return view('login');
    }else{
        return redirect()->route('index');
    }
})->name('login');

Route::post('/admin-login' , [AdminController::class , 'adminLogin'])->name('admin-login');

Route::middleware([Authenticate::class])->group(function (){

    Route::get('/dashboard', [AdminController::class , 'index'])->name('index');
    Route::get('/logout',[AdminController::class , 'logout'])->name('logout');
    Route::get('/users', [AdminController::class , 'getUsers'])->name('users');
    Route::get('/banner', [AdminController::class , 'getBanner'])->name('banner');
    Route::get('/sponsers', [AdminController::class , 'getSponsers'])->name('sponsers');
    Route::get('/questions', [AdminController::class , 'getQuestions'])->name('questions');
    Route::get('/user-detail/{id}', [AdminController::class , 'userDetails'])->name('user-detail');
    Route::get('/user-question-answer-list/{id}' , [AdminController::class , 'userQuestionAnswerList'])->name('user-question-answer-list');
    Route::get('/add-banner', [AdminController::class , 'addBanner'])->name('add-banner');
    Route::get('add-home-banner',[AdminController::class , 'addHomeBanner'])->name('add-home-banner');
    Route::post('add-form-banner', [AdminController::class , 'addFormBanner'])->name('add-form-banner');
    Route::get('/bannerActiveDeactive/{id}',[AdminController::class , 'bannerActiveDeactive']);
    Route::get('/edit-banner/{id}', [AdminController::class , 'editBanner'])->name('edit-banner');
    Route::post('/edit-banner-form/{id}', [AdminController::class , 'editBannerForm'])->name('edit-banner-form');
    Route::get('deleteBanner/{id}', [AdminController::class , 'deleteBanner']);
    Route::get('/add-sponser', [AdminController::class , 'addSponserPage'])->name('add-sponser');
    Route::post('add-sponsers-form', [AdminController::class , 'addSponsersForm'])->name('add-sponsers-form');
    Route::get('/sponsersAcDac/{id}', [AdminController::class , 'sponsersAcDac']);
    Route::get('/deleteSponser/{id}' , [AdminController::class , 'deleteSponser']);
    Route::get('/edit-sponser/{id}' , [AdminController::class , 'editSponser'])->name('edit-sponser');
    Route::post('/edit-sponser-form/{id}', [AdminController::class , 'editSponserForm'])->name('edit-sponser-form');
    Route::get('/questionAcDac/{id}', [AdminController::class , 'questionAcDac']);
    Route::post('questionForm',[AdminController::class , 'questionForm'] );
    Route::get('/deleteQuestion/{id}', [AdminController::class , 'deleteQuestion']);
    Route::get('/QuestionEdit/{id}' , [AdminController::class ,'QuestionEdit']);
    Route::post('/updateQuestionForm', [AdminController::class , 'updateQuestionForm']);
    Route::get('/search/{search}', [AdminController::class , 'search']);
    Route::get('/is_varified_user/{id}', [AdminController::class , 'varifiedUser']);
    Route::get('/delete_User/{id}', [AdminController::class , 'deleteUser']);

});

Route::get('getDevide/{referral_code}', [UserController::class , 'getDevide']);


Route::get('checkout/user_id={id}' , [PaymentController::class , 'checkout']);
Route::post('paymentPost', [PaymentController::class , 'paymentPost'])->name('payment_post');
Route::get('success-payment',[PaymentController::class , 'successPayment'])->name('success_payment');
Route::get('backMoney', [PaymentController::class , 'backMoney']);


Route::get('connectSeller', [PaymentController::class , 'createStripeAccount']);


// Route::post('upload_image', [UserController::class , 'uploadImage'])->name('upload_image');
Route::get('upload_image', [UserController::class , 'uploadImage']);
