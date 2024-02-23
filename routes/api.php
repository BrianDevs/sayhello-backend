<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HobbyController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZodiacController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['localization']], function () {
    Route::any('/login', [UserController::class , 'Login']);
    Route::get('/userProfile' , [UserController::class , 'userProfile']);
    Route::get('/getPets', [PetController::class ,'getPets']);
    Route::get('/getZodiac', [ZodiacController::class ,'getZodiac']);
    Route::get('/getHobbies', [HobbyController::class ,'getHobbies']);
    Route::get('/getQuestions', [QuestionController::class , 'getQuestions']);
    Route::any('/matchOtp', [UserController::class , 'MatchOtp']);
    Route::any('/updateLatLng', [UserController::class , 'updateLatLng']);
    Route::any('/updateUserPersonalInformation', [UserController::class , 'updateUserPersonalInformation']);
    Route::any('/updateUserDocsImageAndProfileImage', [UserController::class , 'updateUserDocsImageAndProfileImage']);
    Route::any('/updateUserDocsImageAndProfileImageInBase64',[UserController::class , 'updateUserDocsImageAndProfileImageInBase64']);
    Route::any('/attemptQuestionByUser',[UserController::class , 'attemptQuestionByUser']);
    Route::get('/getAttemptQuestionByUser',[UserController::class , 'getAttemptQuestionByUser']);
    Route::get('/home', [UserController::class , 'home']);
    Route::get('/matchStangers', [UserController::class , 'matchStangers']);
    Route::get('/showStrangersProfile', [UserController::class , 'showStrangersProfile']);
    Route::get('/getBanners', [UserController::class, 'getBanners']);
    Route::get('/remove_answers' , [UserController::class , 'removeAnswers']);
    Route::any('/acceptStranger', [UserController::class , 'acceptStranger']);
    Route::any('/deniedStranger', [UserController::class , 'deniedStranger']);
    Route::get('/nearByCouples',[UserController::class , 'nearByCouples']);
    Route::post('/TakeSelfi' , [UserController::class , 'TakeSelfi']);
    Route::get('/chatList', [UserController::class , 'chatList']);
    Route::get('/temporaryChatList', [UserController::class , 'temporaryChatList']);
    Route::get('/chatBetweenRoomID', [UserController::class , 'chatBetweenRoomID']);
    Route::post('/memberConnection' , [UserController::class , 'memberConnection']);
    Route::get('/getCoupleData' , [UserController::class , 'getCoupleData']);
    Route::get('/paymentUrl', [UserController::class , 'paymentUrl']);
    Route::get('/GetPoiuntWithUserId', [UserController::class , 'GetPoiuntWithUserId']);
    Route::post('/sendImageFromChat', [UserController::class, 'sendImageFromChat']);
    Route::post('/temporarySendImageFromChat', [UserController::class, 'temporarySendImageFromChat']);
    Route::get('/getCouples', [UserController::class , 'getCouples']);
    Route::post('/changeNotification', [UserController::class , 'changeNotification']);
    // this all done by singh


    Route::post('/uploadIds' , [UserController::class , 'uploadIds']);
    Route::get('/CheckUploadID', [UserController::class , 'CheckUploadID']);
    Route::post('/chat', [UserController::class , 'chat']);
    Route::get('/getMessageCount', [UserController::class , 'getMessageCount']);
    Route::get('/getTemporaryMessageCount', [UserController::class , 'getTemporaryMessageCount']);
    Route::post('/TemporaryChat', [UserController::class , 'TemporaryChat']);
    Route::post('addImage', [UserController::class , 'addImage']);


    Route::get('sendTextMessage', [AdminController::class , 'sendTextMessage']);    


    // payment handle routes
    Route::post('processPayment', [PaymentController::class , 'processPayment']);
    Route::get('getLinkAuthenticationClientSecret', [PaymentController::class , 'getLinkAuthenticationClientSecret']);
});


Route::post('chatNotification', [UserController::class , 'chatNotification']);