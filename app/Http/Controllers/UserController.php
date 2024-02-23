<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Couple;
use App\Models\User;
use App\Models\Points;
use App\Models\UserQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use ErrorException;
use Illuminate\Database\QueryException;
use App\Helper\Notification;
use App\Models\Chat;
use App\Models\ChatMessageCount;
use App\Models\ChatBox;
use App\Helper\AuthHelper;
use App\Helper\sendTextMessage;
use App\Models\Referral;
use App\Models\TemporaryChat;
use Carbon\Carbon;

class UserController extends Controller
{
    public function calculateYearsBetweenDates($dob)
    {
        $date1 = Carbon::parse($dob);
        $date2 = Carbon::parse(date('d-m-Y'));
        $years = $date1->diffInYears($date2);
        return $years;
    }
    public function generateReferralCode($length = 10)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $referralCode = '';

        $characterCount = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $referralCode .= $characters[rand(0, $characterCount - 1)];
        }

        return $referralCode;
    }
    public function callNotification($id, $title, $body)
    {
        $user_one = User::find($id);
        $device_token = $user_one->device_token;
        if (!empty($device_token)) {
            $data = [
                'title'                     => $title,
                'body'                      => $body,
                "clickEvent"                => "accepted",
                "stranger_id"               => $id,
                'martched_percentage'       => null,
            ];
            return Notification::Notification($device_token, $data);
        }
    }
    public function SendChatNotification($id, $image, $name,  $message, $room_id ,$clickEvent, $sender_id)
    {
        $user_one = User::find($id);
        $device_token = $user_one->device_token;
        if (!empty($device_token)) {
            $data = [
                'title'         => $name,
                'body'          => $message,
                'user_image'    => $image,
                "clickEvent"    => $clickEvent,
                "stranger_id"   => $sender_id,
                'room_id'       => $room_id,
            ];
            return Notification::Notification($device_token, $data);
        }
    }
    public function Login(Request $request)
    {
        $langData   = trans('messages');
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric'
        ], [
            'phone.required' => $langData['phoneRequired'],
            'phone.numeric' => $langData['phoneNumeric'],
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        //check that referralCode is not null
        if ($request->referral_code != null || $request->referral_code != '') {
            $Referrals = Referral::where('referral_id', $request->referral_code)->get();
            $getUserIfExists = User::where('phone', $request->phone)->withTrashed()->select('id', 'phone', 'otp', 'otp_matched', 'deleted_at', 'is_active', 'referralCode')->first();
            if (count($Referrals) > 5) {
                // only logged in with his crediancials 
                if ($getUserIfExists) {

                    if ($getUserIfExists['deleted_at'] != null) {
                        //users account deleted by admin
                        return response()->json(['status' => false, 'message' => $langData["Account_deleted"], 'userCome' => 2, 'data' => []]);
                    } elseif ($getUserIfExists['is_active'] == 1) {
                        //users account deactived by admin
                        return response()->json(['status' => false, 'message' => $langData["Account_deactived"], 'userCome' => 2, 'data' => []]);
                    } elseif (!empty($getUserIfExists) && ($getUserIfExists->otp_matched == 1)) {
                        // user is exists, show him to home page
                        return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 1, 'data' => $getUserIfExists]);
                    } elseif (!empty($getUserIfExists) && ($getUserIfExists->otp_matched == 0)) {
                        // user is exists but not filled otp so show him to otp matched page
                        $otp = rand(11111, 99999);
                        sendTextMessage::sendTextMessage($request->country_code, $request->phone, $otp);
                        $getUserIfExists->update(['otp' => $otp,  'country_code' => $request->country_code, 'device_token' => $request->device_token, 'device_type' => $request->device_type, 'lng' => $request->lng, 'lat' => $request->lat]);
                        return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 0, 'data' => $getUserIfExists]);
                    }
                } else {
                    // user not is exists so show him to otp matched page
                    $otp = rand(11111, 99999);
                    $request['otp'] = $otp;
                    sendTextMessage::sendTextMessage($request->country_code, $request->phone, $otp);
                    // Example usage
                    $referralCode = $this->generateReferralCode();
                    // echo $referralCode;
                    $request['points']  = 200;
                    $request['referralCode'] = $referralCode;
                    $user = User::create($request->all());
                    Points::create(['user_id' => $user->id, 'point' => 200, 'title' => "Registration Points"]);

                    //create qr for users
                    $string = env('APP_URL') . "api/userProfile?id=" . $user->id;
                    $id = $user->id;
                    $referralData = [
                        'referral_id' => $request->referral_code,
                        'user_id' => $id,
                    ];
                    Referral::create($referralData);
                    $qrcode = 'qr-code-a-' . $id;
                    $renderer = new ImageRenderer(
                        new RendererStyle(200),
                        new ImagickImageBackEnd()
                    );
                    $writer = new Writer($renderer);
                    $writer->writeFile($string,  'public/uploads/qrcode/' . $qrcode . '.png');
                    $user->update(['qrcode' => $qrcode . '.png']);
                    return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 0, 'data' => $user]);
                }
            } else {
                // only logged in with his crediancials 
                if ($getUserIfExists) {
                    if ($getUserIfExists['deleted_at'] != null) {
                        //users account deleted by admin
                        return response()->json(['status' => false, 'message' => $langData["Account_deleted"], 'userCome' => 2, 'data' => []]);
                    } elseif ($getUserIfExists['is_active'] == 1) {
                        //users account deactived by admin
                        return response()->json(['status' => false, 'message' => $langData["Account_deactived"], 'userCome' => 2, 'data' => []]);
                    } elseif (!empty($getUserIfExists) && ($getUserIfExists->otp_matched == 1)) {
                        // user is exists, show him to home page
                        return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 1, 'data' => $getUserIfExists]);
                    } elseif (!empty($getUserIfExists) && ($getUserIfExists->otp_matched == 0)) {
                        // user is exists but not filled otp so show him to otp matched page
                        $otp = rand(11111, 99999);
                        sendTextMessage::sendTextMessage($request->country_code, $request->phone, $otp);

                        // $request['otp'] = $request->device_token;
                        // echo"<pre>";print_r($getUserIfExists); die;
                        $getUserIfExists->update(['otp' => $otp, 'country_code' => $request->country_code, 'device_token' => $request->device_token, 'device_type' => $request->device_type, 'lng' => $request->lng, 'lat' => $request->lat]);
                        return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 0, 'data' => $getUserIfExists]);
                    }
                } else {
                    // user not is exists so show him to otp matched page
                    $otp = rand(11111, 99999);
                    $request['otp'] = $otp;
                    sendTextMessage::sendTextMessage($request->country_code, $request->phone, $otp);
                    // Example usage
                    $referralCode = $this->generateReferralCode();
                    // echo $referralCode;
                    $request['points']  = 200;
                    $request['referralCode'] = $referralCode;
                    $user = User::create($request->all());
                    Points::create(['user_id' => $user->id, 'point' => 200, 'title' => "Registration Points"]);
                    //create qr for users
                    $string = env('APP_URL') . "api/userProfile?id=" . $user->id;
                    $id = $user->id;
                    $referralData = [
                        'referral_id' => $request->referral_code,
                        'user_id' => $id,
                    ];
                    Referral::create($referralData);
                    $qrcode = 'qr-code-a-' . $id;
                    $renderer = new ImageRenderer(
                        new RendererStyle(200),
                        new ImagickImageBackEnd()
                    );
                    $writer = new Writer($renderer);
                    $writer->writeFile($string,  'public/uploads/qrcode/' . $qrcode . '.png');
                    $user->update(['qrcode' => $qrcode . '.png']);
                    return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 0, 'data' => $user]);
                }
            }
        } else {
            // fetch user that already exists 

            $getUserIfExists = User::where('phone', $request->phone)->withTrashed()->select('id', 'phone', 'otp', 'otp_matched', 'deleted_at', 'is_active', 'referralCode')->first();
            if ($getUserIfExists) {
                if ($getUserIfExists['deleted_at'] != null) {
                    //users account deleted by admin
                    return response()->json(['status' => false, 'message' => $langData["Account_deleted"], 'userCome' => 2, 'data' => []]);
                } elseif ($getUserIfExists['is_active'] == 1) {
                    //users account deactived by admin
                    return response()->json(['status' => false, 'message' => $langData["Account_deactived"], 'userCome' => 2, 'data' => []]);
                } elseif (!empty($getUserIfExists) && ($getUserIfExists->otp_matched == 1)) {
                    // user is exists, show him to home page
                    return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 1, 'data' => $getUserIfExists]);
                } elseif (!empty($getUserIfExists) && ($getUserIfExists->otp_matched == 0)) {
                    // user is exists but not filled otp so show him to otp matched page
                    $otp = rand(11111, 99999);
                    sendTextMessage::sendTextMessage($request->country_code, $request->phone, $otp);
                    // $request['otp'] = $request->device_token;
                    // echo"<pre>";print_r($getUserIfExists); die;
                    $getUserIfExists->update(['otp' => $otp, 'country_code' => $request->country_code, 'device_token' => $request->device_token, 'device_type' => $request->device_type, 'lng' => $request->lng, 'lat' => $request->lat]);
                    return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 0, 'data' => $getUserIfExists]);
                }
            } else {
                // user not is exists so show him to otp matched page
                $otp = rand(11111, 99999);
                $request['otp'] = $otp;
                sendTextMessage::sendTextMessage($request->country_code, $request->phone, $otp);

                // Example usage
                $referralCode = $this->generateReferralCode();
                // echo $referralCode;
                $request['points']  = 200;
                $request['referralCode'] = $referralCode;
                $user = User::create($request->all());
                Points::create(['user_id' => $user->id, 'point' => 200, 'title' => "Registration Points"]);
                //create qr for users
                $string = env('APP_URL') . "api/userProfile?id=" . $user->id;
                $id = $user->id;
                $qrcode = 'qr-code-a-' . $id;
                $renderer = new ImageRenderer(
                    new RendererStyle(200),
                    new ImagickImageBackEnd()
                );
                $writer = new Writer($renderer);
                $writer->writeFile($string,  'public/uploads/qrcode/' . $qrcode . '.png');
                $user->update(['qrcode' => $qrcode . '.png']);
                return response()->json(['status' => true, 'message' => $langData["Login_successfully"], 'userCome' => 0, 'data' => $user]);
            }
        }
    }
    public function userProfile(Request $request)
    {
        $langData = trans('messages');
        $user = User::find($request->id);
        if ($user) {
            $user['image']           = asset("uploads/user/" . $user->image);
            $user['qrcode']          = asset("uploads/qrcode/" . $user->qrcode);
            $user['front_doc_image'] = asset("uploads/userDocs/" . $user->front_doc_image);
            $user['back_doc_image']  = asset("uploads/userDocs/" . $user->back_doc_image);
            return response()->json(['status' => true, 'message' => $langData["user_profile"], 'data' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["user_not_found"]]);
        }
    }
    public function MatchOtp(Request $request)
    {
        $langData = trans('messages');
        $user = User::find($request->id);
        if ($user) {
            if ($user->otp == (int)$request->otp) {
                $user->update([
                    'otp_matched'  => 1,
                    'device_type'  => $request->device_type,
                    'device_token' => $request->device_token
                ]);
                return response()->json(['status' => true, 'message' => $langData["otp_matched"], 'data' => $user]);
            } else {
                return response()->json(['status' => false, 'message' => $langData["otp_does_not_matched"]]);
            }
        } else {
            return response()->json(['status' => false, 'message' => $langData["user_not_found"]]);
        }
    }
    public function updateLatLng(Request $request)
    {
        $langData = trans('messages');
        $user = User::find($request->id);
        if ($user) {
            $user->update(['lat' => $request->lat, 'lng' => $request->lng]);
            return response()->json(['status' => true, 'message' => $langData["address_updated"], 'data' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["user_not_found"]]);
        }
    }
    public function updateUserPersonalInformation(Request $request)
    {
        $langData = trans('messages');
        $user = User::find($request->id);
        if ($user) {
            if ($request->hobby) {
                foreach ($request->hobby as $ho) {
                    $hobbies[] = $ho;
                }
                $request['hobbies'] = json_encode($hobbies);
            }
            $userData = [
                'firstName' => $request->name,
                'lastName'  => $request->lastName,
                'email'     => $request->email,
                'dob'       => $request->dob // '20-03-1997'
            ];
            // for authentication we have saved data and transfer the data to authentications apis
            $data = AuthHelper::Auth($userData, $request->id);
            if(!is_null($request->dob)){
                $request['age'] = $this->calculateYearsBetweenDates($request->dob);
            }
            $user->update($request->all());
            return response()->json(['status' => true, 'message' => $langData["user_Data"], 'data' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["user_not_found"]]);
        }
    }
    public function updateUserDocsImageAndProfileImage(Request $request)
    {
        $langData = trans('messages');
        $user = User::find($request->id);
        if ($user) {
            if ($request->hasFile('front_doc')) {
                $image_path = public_path("uploads/userDocs/") . $user->front_doc_image;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $file = $request->file('front_doc');
                // $extension = $file->getClientOriginalExtension();
                $fileName = time() . '-1.png';
                $file->move(public_path("uploads/userDocs/"), $fileName);
                $user->front_doc_image = $fileName;
                $idFrontBase64 = $request->front_doc;
                $user->idFrontBase64 = $idFrontBase64;
            }
            if ($request->hasFile('back_doc')) {
                $image_path = public_path("uploads/userDocs/") . $user->back_doc_image;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $file = $request->file('back_doc');
                // $extension = $file->getClientOriginalExtension();
                $fileName = time() . '-2.png';
                $file->move(public_path("uploads/userDocs/"), $fileName);
                $user->back_doc_image = $fileName;
                $idBackBase64 = $request->back_doc;
                $user->idBackBase64 = $idBackBase64;
            }
            if ($request->hasFile('photo')) {
                $image_path = public_path("uploads/user/") . $user->image;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $file = $request->file('photo');
                // $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.png';
                $file->move(public_path("uploads/user/"), $fileName);
                $user->image = $fileName;
            }
            $user->page = $request->page;
            $user->update($request->all());

            if ($user->image) {
                $user->image = asset('uploads/user/' . $user->image);
            }
            if ($user->front_doc_image) {
                $user->front_doc_image = asset('uploads/userDocs/' . $user->front_doc_image);
            }
            if ($user->back_doc_image) {
                $user->back_doc_image = asset('uploads/userDocs/' . $user->back_doc_image);
            }

            return response()->json(['status' => true, 'message' => $langData["user_Data"], 'data' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["user_not_found"]]);
        }
    }
    public function updateUserDocsImageAndProfileImageInBase64(Request $request)
    {
        $langData = trans('messages');
        $user = User::find($request->id);
        if ($user) {
            $idFrontBase64 = $request->front_doc_64;
            $idBackBase64 = $request->back_doc_64;
            // Log::info('updateUserDocsImageAndProfileImageInBase64 is working');
            AuthHelper::UploadIds($idFrontBase64, $idBackBase64, $user->id);
            return response()->json(['status' => true, 'message' => $langData["user_Data"], 'data' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["user_not_found"]]);
        }
    }
    public function attemptQuestionByUser(Request $request)
    {
        $langData = trans('messages');
        try {
            $exists = UserQuestionAnswer::where('question_id', $request->question_id)->where('user_id', $request->user_id)->first();
            if ($exists) {
                $exists->update(['answer' => $request->answer]);
                return response()->json(['status' => true, 'message' => $langData['Added'], 'data' => $exists]);
            } else {
                $answeredQuestion = UserQuestionAnswer::create($request->all());
                return response()->json(['status' => true, 'message' =>  $langData['Added'], 'data' => $answeredQuestion]);
            }
        } catch (\Exception $e) {
            if ($e instanceof \InvalidArgumentException) {
                // Handle invalid argument exception
                return response()->json(['status' => false, 'message' => $langData['Invalid_arguments']]);
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exception
                return response()->json(['status' => false, 'message' => $langData['Invalid_arguments']]);
            } else {
                // Handle any other exception
                return response()->json(['status' => false, 'message' => $langData['Invalid_arguments']]);
            }
        }
    }
    public function getAttemptQuestionByUser(Request $request)
    {
        $langData = trans('messages');
        $answeredQuestion = UserQuestionAnswer::leftJoin('questions', 'questions.id', 'user_question_answers.question_id');
        if ($request->lang == 'ru') {
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer', 'questions.question_ru as question');
        } elseif ($request->lang == 'pt') {
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer', 'questions.question_pt as question');
        } elseif ($request->lang == 'ch') {
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer', 'questions.question_ch as question');
        } elseif ($request->lang == 'es') {
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer', 'questions.question_es as question');
        } elseif ($request->lang == 'fr') {
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer', 'questions.question_fr as question');
        } elseif ($request->lang == 'de') {
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer', 'questions.question_de as question');
        } elseif ($request->lang == 'it') {
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer',  'questions.question_it as question');
        } else {
            //en
            $answeredQuestion->select('user_question_answers.id', 'user_question_answers.user_id', 'user_question_answers.question_id', 'user_question_answers.answer', 'questions.question');
        }
        $answeredQuestion = $answeredQuestion->where('user_id', $request->user_id)
            // ->where('user_question_answers.deleted_at' , null)
            ->get();
        if (count($answeredQuestion) > 0) {
            return response()->json(['status' => true, 'message' => $langData['List_of_question_answers'], 'data' => $answeredQuestion]);
        } else {
            return response()->json(['status' => false, 'message' => $langData['No_List'], 'data' => []]);
        }
    }
    public function home(Request $request)
    {
        $users = User::where('deleted_at', null)->get();
        $Banners = Banner::where('is_active', 0)->select('image')->where('deleted_at', null)->where('home_on_not', 1)->latest()->first();
        if ($Banners) {
            $Banners['image'] =  !empty($Banners->image) ?  asset('uploads/banners/' . $Banners->image) : asset('assets/media/images/restaurant.jpg');
        }
        if (count($users) > 0) {
            return response()->json(['status' => true, 'Banners' => $Banners, 'total_users' => count($users), 'count_down' => 10000000 - count($users), 'your_shares' => 0, 'your_signups' => 0]);
        }
    }
    public function matchStangers(Request $request)
    {
        // Log::info(date('Y-m-d H:i:s'));
        $langData = trans('messages');
        $user_one = User::find($request->id);
        $user_one->update(['lat' => $request->lat, 'lng' => $request->lng]);
        
        $interested_in = $user_one->interested_in == 1 ? 2 : 1;
        
        $haversine = "(6371 * acos(cos(radians($user_one->lat)) 
                * cos(radians(lat)) 
                * cos(radians(lng) 
                - radians($user_one->lng)) 
                + sin(radians($user_one->lat)) 
                * sin(radians(lat))))";
        $Couple = Couple::select('my_id', 'stranger_id')
                            ->where('my_id', $user_one->id)
                            ->orWhere('stranger_id', $user_one->id)
                            ->get();

        $all_different_gender_users = User::select('id', 'device_token', 'is_user_varified', 'user_type', 'name', 'start_age', 'end_age', 'my_likes', 'my_dis_likes', 'hobbies', 'pets', 'alcohol', 'smoking', 'interested_in', 'zodiac')
            ->where('interested_in', $interested_in)
            ->where('is_user_varified', 1)
            ->where('user_type', 0)
            ->where('is_active', 0)
            ->where('id', '!=', $request->id)
            ->where('age', '>=', $user_one->start_age)
            ->whereOr('age', '<=', $user_one->end_age)
            ->selectRaw("{$haversine} AS distance")
            ->orderBy('distance', 'asc');
            if(count($Couple) > 0){
                $CoupleExcludeIds = $Couple->pluck('my_id')->merge($Couple->pluck('stranger_id'))->unique()->toArray();
                if (count($CoupleExcludeIds) > 0) {
                    $all_different_gender_users->whereNotIn('id', $CoupleExcludeIds);
                }
            }
            $all_deffirent_gander_users = $all_different_gender_users->get();
            // echo"<pre>";print_r($all_deffirent_gander_users); die;
        if (count($all_deffirent_gander_users) > 0) {
            foreach ($all_deffirent_gander_users as $all_deffirent_gander_user) {

                // if column match of both genders then add 1 in matchFilled variable
                $matchFilled = 0;

                // this variable is used for calculate the percentage 
                $matchPercentage = 0;

                // this variable is used for count that how much fields i have put values   
                $howManyColumnIWantToMatchInStrangers = 0;

                $array = array();

                if ($all_deffirent_gander_user->zodiac == $user_one->zodiac) {
                    $matchFilled += 1;
                }
                if ($all_deffirent_gander_user->pets == $user_one->pets) {
                    $matchFilled += 1;
                }
                if ($all_deffirent_gander_user->smoking == $user_one->smoking) {
                    $matchFilled += 1;
                }
                if ($all_deffirent_gander_user->alcohol == $user_one->alcohol) {
                    $matchFilled += 1;
                }
                //start matched hobbies here 
                //first users hobbies
                $first_user_hobbies = $user_one->hobbies;
                $first_user_hobbies_Explode = explode(',', $first_user_hobbies);

                //difrent gender users hobbies
                $all_different_user_hobbies = $all_deffirent_gander_user->hobbies;
                $all_different_user_hobbies_Explode = explode(',', $all_different_user_hobbies);

                if ($user_one->hobbies != 0) {
                    if ($matchingHobbies = array_intersect($first_user_hobbies_Explode, $all_different_user_hobbies_Explode)) {
                        $matchFilled += (count($matchingHobbies) / count($first_user_hobbies_Explode));
                    }
                }
                //end matched hobbies here 

                //start matched my_likes here 
                //first users my_likes
                $first_user_my_likes = $user_one->my_likes;
                $first_user_my_likes_Explode = explode(',', $first_user_my_likes);

                //difrent gender users my_likes
                $all_different_user_my_likes = $all_deffirent_gander_user->my_likes;
                $all_different_user_my_likes_Explode = explode(',', $all_different_user_my_likes);

                if ($user_one->my_likes != null) {
                    if ($matched_my_likes = array_intersect($first_user_my_likes_Explode, $all_different_user_my_likes_Explode)) {
                        $matchFilled += (count($matched_my_likes) / count($first_user_my_likes_Explode));
                    }
                }
                //end matched my_likes here 

                //first users my_dis_likes
                $first_user_my_dis_likes = $user_one->my_dis_likes;
                $first_user_my_dis_likes_Explode = explode(',', $first_user_my_dis_likes);

                //difrent gender users my_dis_likes
                $all_different_user_my_dis_likes = $all_deffirent_gander_user->my_dis_likes;
                $all_different_user_my_dis_likes_Explode = explode(',', $all_different_user_my_dis_likes);

                if ($user_one->my_dis_likes != null) {
                    if ($matched_my_dis_likes = array_intersect($first_user_my_dis_likes_Explode, $all_different_user_my_dis_likes_Explode)) {
                        $matchFilled += (count($matched_my_dis_likes) / count($first_user_my_dis_likes_Explode));
                    }
                }

                // increase by 1 if fileds which i want to match is not null and zero 
                $howManyColumnIWantToMatchInStrangers += ($user_one->my_dis_likes != null) ? 1 : 0;
                $howManyColumnIWantToMatchInStrangers += ($user_one->my_likes != null) ? 1 : 0;
                $howManyColumnIWantToMatchInStrangers += ($user_one->hobbies != 0) ? 1 : 0;
                $howManyColumnIWantToMatchInStrangers += ($user_one->zodiac != 0) ? 1 : 0;
                $howManyColumnIWantToMatchInStrangers += ($user_one->pets != 0) ? 1 : 0;
                $howManyColumnIWantToMatchInStrangers += ($user_one->smoking != 0) ? 1 : 0;
                $howManyColumnIWantToMatchInStrangers += ($user_one->alcohol != 0) ? 1 : 0;

                // get percentage on the based count of $howManyColumnIWantToMatchInStrangers column 

                $matchPercentage = ($matchFilled / $howManyColumnIWantToMatchInStrangers) * 100;
                $MoreThenTweentyFive = number_format($matchPercentage, 2);
                // if ($MoreThenTweentyFive > 25) {
                    $array['my_id']         = $request->id;
                    $array['stranger_id']   = $all_deffirent_gander_user->id;
                    $array['stranger_name'] = $all_deffirent_gander_user->name == null ? "Stranger" : $all_deffirent_gander_user->name;
                    $array['matchFilled']   = $matchFilled;
                    // $array['howManyColumnIWantToMatchInStrangers']   = $howManyColumnIWantToMatchInStrangers;
                    $array['matchPercentage'] = number_format($matchPercentage, 2);
                    //sending notification to Logged user
                    $device_token = $user_one->device_token;
                    if (!empty($device_token)) {
                        $data = [
                            'title'                     => "SayHello Notification",
                            'body'                      => "Users Matched!",
                            "clickEvent"                => "Matched",
                            "id"                        => $all_deffirent_gander_user->id,
                            'martched_percentage'       => $array['matchPercentage'],
                        ];
                        Notification::Notification($device_token, $data);
                    }
                    $list[] = $array;
                    return response()->json(['status' => true,  'message' => $langData["Matched_Stangers_list"], 'data' => $list]);
                // }else{
                //     return response()->json(['status' => true,  'message' => $langData["Matched_Stangers_list"], 'data' => []]);
                // }
            }
            // return response()->json(['status' => true,  'message' => $langData["Matched_Stangers_list"], 'data' => $list]);
        } else {
            return response()->json(['status' => false, 'message' =>  $langData["Not_Matched_Stangers"],  'data' => []]);
        }
    }
    public function showStrangersProfile(Request $request)
    {
        $langData = trans('messages');
        $user = User::leftJoin('pets', 'pets.id', 'users.pets')->select('users.id', 'image', 'about_me', 'smoking', 'alcohol', 'pets.name as pet_name')->find($request->stranger_id);
        if ($user) {
            $user['stranger_id'] = $user->id;
            $user['image']       = $user->image == null ? asset('uploads/default_user.png') : asset('uploads/user/' . $user->image);
            return response()->json(['status' => true, 'message' => $langData["Stranger_profile"], 'data' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["Stranger_not_found"]]);
        }
    }
    public function getBanners(Request $request)
    {
        $langData = trans('messages');
        $Banners = Banner::where('is_active', 0)->where('deleted_at', null)->where('home_on_not', 0)->get();
        if (count($Banners) > 0) {
            foreach ($Banners as $Banner) {
                $Banner->image = asset("uploads/banners/" . $Banner['image']);
            }
            return response()->json(['status' => true, 'message' => $langData["Banners_found"], 'data' => $Banners]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["Banners_not_found"]]);
        }
    }
    public function removeAnswers(Request $request)
    {
        $langData = trans('messages');
        $answer = UserQuestionAnswer::find($request->answer_id);
        if ($answer) {
            $answer->delete();
            return response()->json(['status' => true, 'message' => $langData["User_answer_deleted"]]);
        } else {
            return response()->json(['status' => false, 'message' => $langData["Use_correct_answer_id"]]);
        }
    }
    public function acceptStranger(Request $request)
    {
        $langData = trans('messages');
        $alreadyExists = Couple::where(function ($query) use ($request) {
                            $query->where('my_id', $request->my_id)
                                ->where('stranger_id', $request->stranger_id);
                        })->orWhere(function ($query) use ($request) {
                            $query->where('stranger_id', $request->my_id)
                                ->where('my_id', $request->stranger_id);
                        })->first();
        if ($alreadyExists){
                // ...........both accept to each other........
                $title = "SayHello Notification!";
                $body =  "Stranger has been accepted your request!";
                $this->callNotification($request->stranger_id, $title, $body);
                $alreadyExists->update(['status' => 2]);

                $users = User::select('id', 'points')->where('id', $request->my_id)->orWhere('id', $request->stranger_id)->get();
                foreach ($users as $user) {
                    $user['points'] = ($user->points + 100);
                    $user->update();
                    Points::create(['user_id' => $user->id, 'point' => 100, 'title' => "Accepted points"]);
                }
                // code for insert data in chatbox and after 12  hours send notification to continue chat
                date_default_timezone_set('asia/kolkata');
                $data = [
                    'my_id'       => $request->my_id,
                    'stranger_id' => $request->stranger_id,
                    'dateTime'    => date('Y-m-d H:i:s'),
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s')
                ];
                ChatBox::insert($data);
                return response()->json(['status' => true, 'message' => $langData["Stranger_has_been_accepted_you"], 'data' => $alreadyExists]);
        
        } else {
            // ...........one of them has been accepted..........
            $request['status'] = 1;
            $couple = Couple::create($request->all());
            $title = "SayHello Notification!";
            $body =  'Stranger has been accepted your request!';
            $this->callNotification($request->stranger_id, $title, $body);
            return response()->json(['status'  => true, 'message' => 'Stranger has been accepted you',    'data' => $couple]);
        }
    }
    public function deniedStranger(Request $request)
    {
        $langData = trans('messages');
        $alreadyExists = Couple::where(function ($query) use ($request) {
                                $query->where('my_id', $request->my_id)
                                    ->where('stranger_id', $request->stranger_id);
                            })->orWhere(function ($query) use ($request) {
                                $query->where('stranger_id', $request->my_id)
                                    ->where('my_id', $request->stranger_id);
                            })->first();
         // ...........Someone has denied to meet........
         if($alreadyExists){
             $title = "SayHello Notification!";
             $body =  "Stranger has been denied your request!";
             $this->callNotification($request->stranger_id, $title, $body);
             $alreadyExists->update(['status' => 3]);
             return response()->json(['status' => true, 'message' => $langData["Stranger_has_been_denied_your_request"],  'data' => $alreadyExists]);
        }else{
            return response()->json(['status' => false, 'message' => 'something went wrong!',  'data' => []]);
         }
    }
    public function nearByCouples(Request $request)
    {
        $langData = trans('messages');

        try {
            // Retrieve the profile data of the target user and the multiple other users
            $user_one = User::find($request->id);
            $user_one->update(['lat' => $request->lat, 'lng' => $request->lng]);
            // get the lat lng of first user
            $haversine = "(6371 * acos(cos(radians($user_one->lat)) 
                        * cos(radians(accepted_lat)) 
                        * cos(radians(accepted_lng) 
                        - radians($user_one->lng)) 
                        + sin(radians($user_one->lat)) 
                        * sin(radians(accepted_lat))))";
            // get the all couples and get nearest user here
            $couples = Couple::select('id', 'accepted_lat', 'accepted_lng')
                ->selectRaw("{$haversine} AS distance")
                // change 500 to as per as client requirement. 
                ->whereRaw("{$haversine} < ?", [500])
                ->where('status', 1)
                ->orderBy('distance', 'asc')
                ->get();
            if (count($couples) > 0) {
                foreach ($couples as $couple) {
                    $couple['lat'] = $couple->accepted_lat;
                    $couple['long'] = $couple->accepted_lng;
                    unset($couple->accepted_lat);
                    unset($couple->accepted_lng);
                }
                return response()->json(['status'  => true, 'message' =>  $langData["Data_found"], 'data' => $couples]);
            } else {
                return response()->json(['status'  => false, 'message' =>  $langData["Data_not_found"]]);
            }
        } catch (QueryException $e1) {
            return response()->json(['status'  => false, 'message' => $langData["Location_is_incorrect"]]);
        } catch (ErrorException $ex) {
            return response()->json(['status' => false, 'message' => $langData["Incorrect_Id"]]);
        }
    }
    public function TakeSelfi(Request $request)
    {
        $Couple = Couple::find($request->couple_id);
        if ($Couple) {
            if ($request->hasFile('selfie')) {
                $selfie = public_path("uploads/couple/selfie/") . $Couple->selfie;
                if (File::exists($selfie)) {
                    File::delete($selfie);
                }
                $file = $request->file('selfie');
                $fileName = time() . '.png';
                $file->move(public_path("uploads/couple/selfie/"), $fileName);
                $Couple->selfie = $fileName;
                $Couple->update();
            }
            $Couple['selfie'] = isset($Couple->selfie) ? asset("uploads/couple/selfie/" . $Couple->selfie) : asset("assets/media/images/restaurant.jpg");
            return response()->json(['status' => true, 'message' => 'Selfie uploaded.', 'data' => $Couple]);
        } else {
            return response()->json(['status' => false, 'message' => 'Couple id is not matched.']);
        }
    }
    public function chatList(Request $request)
    {
        $chats = Chat::select('room_id')
            ->where('sender_id', $request->id)
            ->orWhere('receiver_id', $request->id)
            ->groupBy('room_id')
            ->orderBy('created_at', 'desc')
            ->get();

        if (count($chats) > 0) {
            foreach ($chats as $chat) {
                $ChatMessageCount = ChatMessageCount::where('room_id', $chat->room_id)->where('receiver_id', $request->id)->get();
                $getChat = Chat::where('room_id', $chat->room_id)->latest()->first();
                if ($getChat) {

                    $chat['id']           = $getChat['id'];
                    $chat['message']      = $getChat['message'];
                    $chat['receiver_id']  = $request->id == $getChat['sender_id'] ? $getChat['receiver_id'] : $getChat['sender_id'];
                    $chat['sender_id']    = $request->id;
                    $chat['created_at']   = $getChat['created_at'];
                    $chat['message_type'] = $getChat['message_type'];
                    $chat['room_id']      = $getChat['room_id'];
                    $chat['chat_image']   = ($getChat->image == null) ? null : asset('uploads/chat/' . $getChat->image);
                    $chat['formerted_created_at'] = date_format($getChat['created_at'], 'd/m/Y');
                    $chat['unread_messages'] = count($ChatMessageCount) > 0 ? count($ChatMessageCount) : 0;
                    if ($getChat->sender_id == $request->id) {
                        $user = User::find($getChat['receiver_id']);
                        $chat['name'] = ($user->name == null ? 'Known' : $user->name);
                        $chat['image'] = ($user->image == null) ? asset('uploads/default_user.png') : asset('uploads/user/' . $user->image);
                        $chat['photo_option'] = $user->photo_option;
                    } else {
                        $user = User::find($getChat['sender_id']);
                        $chat['name'] = ($user->name == null ? 'Known' : $user->name);
                        $chat['image'] = ($user->image == null ? asset('uploads/default_user.png') : asset('uploads/user/' . $user->image));
                        $chat['photo_option'] = $user->photo_option;
                    }
                }
            }
            return response()->json(['status' => true, 'data' => $chats]);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }
    public function chat(Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y-m-d');

        $usersData = $request->chatData;
        // Log::info("per-----------chat");
        // Log::info($usersData);
        $preparedData = [];
        foreach ($usersData as $userData) {
            $this->chatNotification($userData['sender_id'], $userData['message'], $userData['room_id'], $tem_or_permanent='permanent', $userData['receiver_id']);
            $preparedData[] = [
                'sender_id' => $userData['sender_id'],
                'receiver_id' => $userData['receiver_id'],
                'message' => $userData['message'],
                'image'    =>  $userData['image'],
                'message_type' => $userData['message_type'],
                'room_id' => $userData['room_id'],
                'chat_type' => $userData['chat_type'],
                'start_chat_date' => $formattedDate,
            ];
        }

        Chat::insert($preparedData);
        return response()->json([
            'status' => true,
            'message' => 'success',
        ]);
    }
    public function getMessageCount(Request $request)
    {
        $count = Chat::where('room_id')->get();
        return response()->json(['status'=>true , 'count'=>count($count) > 0 ? count($count) : 0 ]);
    }
    public function getTemporaryMessageCount(Request $request)
    {
        $count = TemporaryChat::where('room_id')->get();
        return response()->json(['status'=>true , 'count'=>count($count) > 0 ? count($count) : 0 ]);
    }
    public function TemporaryChat(Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y-m-d');

        $usersData = $request->chatData;
        // Log::info("tem-----------chat");
        // Log::info($usersData);
        $preparedData = [];
        foreach ($usersData as $userData) {
            $this->chatNotification($userData['sender_id'], $userData['message'], $userData['room_id'], $tem_or_permanent='temporary', $userData['receiver_id']);
            $preparedData[] = [
                'sender_id' => $userData['sender_id'],
                'receiver_id' => $userData['receiver_id'],
                'message' => $userData['message'],
                'image'    =>  $userData['image'],
                'message_type' => $userData['message_type'],
                'room_id' => $userData['room_id'],
                'start_chat_date' => $formattedDate,
            ];
        }

        TemporaryChat::insert($preparedData);
        return response()->json([
            'status' => true,
            'message' => 'success',
        ]);

    }
    public function chatBetweenRoomID(Request $request)
    {
        $chats = Chat::select('id', 'room_id', 'sender_id', 'receiver_id', 'message', 'message_type', 'image', 'created_at')
            ->where('room_id', $request->room_id)
            ->orderBy('created_at', 'asc')
            ->get();
        if (count($chats) > 0) {
            return response()->json(['status' => true, 'data' => $chats]);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }
    public function memberConnection(Request $request)
    {
        $langData = trans('messages');
        $Couple = Couple::find($request->couple_id);
        if ($Couple) {
            $existingLinks = json_decode($Couple->youtube_link, true);
            if (!is_array($existingLinks)) {
                $existingLinks = []; // Initialize as an empty array if not already an array
            }
            $newLink = $request->youtube_link;

            $updatedLinks = array_merge($existingLinks, [$newLink]);
            $imageData = json_encode($updatedLinks);

            $Couple->update(['youtube_link' => $imageData]);

            $Couple['youtube_link'] = $updatedLinks;

            return response()->json(['status' => true, 'message' => $langData['Link_Uploaded'], 'data' => $Couple]);
        } else {
            return response()->json(['status' => false, 'message' => $langData['Couple_id_is_not_matched']]);
        }
    }
    public function getCoupleData(Request $request)
    {
        $langData = trans('messages');

        if ($request->filter === '0' || $request->filter == 0)
            $Couple = Couple::get();
        else
            $Couple = Couple::where('id', $request->couple_id)->get();

        if (count($Couple) > 0) {
            foreach ($Couple as $Coupl) {
                $Coupl['youtube_link'] = json_decode($Coupl->youtube_link);
            }
            return response()->json(['status' => true, 'message' =>  $langData['Link_Uploaded'], 'data' => $Couple]);
        } else {
            return response()->json(['status' => false, 'message' =>  $langData['Couple_id_is_not_matched']]);
        }
    }
    public function paymentUrl(Request $request)
    {
        return response()->json(['status' => true, 'payment_url' => env('APP_URL') . "checkout/user_id=" . $request->id]);
    }
    public function GetPoiuntWithUserId(Request $request)
    {
        $langData = trans('messages');

        $Points = Points::where('user_id', $request->user_id)->get();
        if (count($Points) > 0) {
            return response()->json(['status' => true, 'message' => $langData['Get_Points'], 'data' => $Points]);
        }
        return response()->json(['status' => false, 'message' => $langData['Data_not_found'], 'data' => []]);
    }
    public function sendImageFromChat(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '.png';
            $file->move(public_path("uploads/chat/"), $fileName);
        }
        // asset('uploads/chat/' . $getChat->image)
        return response()->json(['status' => true, 'message' => 'Send Image!', 'data' => asset('uploads/chat/' . $fileName)]);
    }
    public function temporarySendImageFromChat(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '.png';
            $file->move(public_path("uploads/chat/"), $fileName);
        }
        return response()->json(['status' => true, 'message' => 'Send Image!', 'data' => asset('uploads/chat/' . $fileName)]);
    }
    public function getCouples(Request $request)
    {
        $Couple = Couple::leftJoin('users', 'users.id', 'couples.stranger_id')
            ->select('couples.*', 'users.name', 'users.image')
            ->where('my_id', $request->user_id)
            ->orWhere('stranger_id', $request->user_id)
            ->get();
        if (count($Couple) > 0) {
            foreach ($Couple as $Coupl) {
                $Coupl['youtube_link'] = json_decode($Coupl->youtube_link);
                $Coupl['name']         = $Coupl->name == null ? 'Known' : $Coupl->name;
                $Coupl['image']        = $Coupl->image == null ? asset('uploads/default_user.png') : asset('uploads/user/' . $Coupl->image);
            }
            return response()->json(['status' => true, 'message' => 'Couple List!', 'data' => $Couple]);
        }
        return response()->json(['status' => false, 'message' => 'No List!']);
    }
    public function getDevide(Request $request, $referral_code)
    {
        $referral_code = $referral_code;
        return view('referral_code_page', compact('referral_code'));
    }
    public function changeNotification(Request $request)
    {
        $User = User::find($request->id);
        if ($User) {
            $User->update(['notification' => $request->notification, 'device_token' => is_null($request->device_token) ? null : $request->device_token]);
            return response()->json(['status' => true, 'message' => 'update', 'data' => $User]);
        }
        return response()->json(['status' => false, 'message' => 'Not Found!']);
    }
    public function uploadIds(Request $request)
    {
        $sender_id = $request->sender_id;
        $receiver_id = $request->receiver_id;

        $chats = Chat::where(function ($query) use ($sender_id) {
            $query->where('sender_id', $sender_id)
                ->orWhere('receiver_id', $sender_id);
        })
            ->where(function ($query) use ($receiver_id) {
                $query->where('sender_id', $receiver_id)
                    ->orWhere('receiver_id', $receiver_id);
            })
            ->latest()
            ->first();

        if (!empty($chats)) {
            $request['room_id'] = $chats['room_id'];
            $request['start_chat_date'] = date('Y-m-d');
            $create = Chat::insert($request->all());
            return response()->json(['message' => 'update', 'data' => $create]);
        } else {
            $request['room_id'] = rand('100000', '999999999999');
            $request['start_chat_date'] = date('Y-m-d');
            Chat::insert($request->all());
            return response()->json(['message' => 'create']);
        }
    }
    public function CheckUploadID(Request $request)
    {
        //check status of ids
        //  $status_url = 'https://api-v3.authenticating.com/identity/document/scan/status';
        //  $data_AccessCode = [
        //      "userAccessCode"=>'63721d06-b4b6-4776-96c3-f7034b8a08cf',
        //  ];
        //  $response = Http::withHeaders([
        //      'Authorization' => 'Bearer LZRTMQDEMFCGWVSNEERVI2ZTMFXT43CMHFRV2JJUOQYUO23O',
        //      'Content-Type' => 'application/json',
        //  ])->post($status_url, $data_AccessCode);
        //  if ($response->successful()) {
        //      $data_response = $response->json();
        //      Log::info($data_response);
        //      return $data_response;
        //  } else {
        //      $error = $response->json();
        //      return $error;
        //  }


        $url = 'https://api-v3.authenticating.com/identity/document/scan/data';
        $data = [
            "userAccessCode" => '63721d06-b4b6-4776-96c3-f7034b8a08cf',
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer LZRTMQDEMFCGWVSNEERVI2ZTMFXT43CMHFRV2JJUOQYUO23O',
            'Content-Type' => 'application/json',
        ])->post($url, $data);
        if ($response->successful()) {
            $data = $response->json();
            // Log::info($data);
            return response()->json(['status' => $data]);
            // return $data;
        } else {
            $error = $response->json();
            return response()->json(['status' => $error]);

            return $error;
        }
    }
    public function addImage(Request $request)
    {
        // $file1 = file_get_contents($request->front_doc_image);
        // $file2 = file_get_contents($request->back_doc_image);
        // // $BACK = public_path("uploads/userDocs/") .$user->;
        // // $FRONT = public_path("uploads/userDocs/") .$user->front_doc_image;

        // $base64 = base64_encode($file1);
        // $FRONT64 = base64_encode($file2);

        // AuthHelper::UploadIds($FRONT64 , $base64 ,  20);

    }
    public function chatNotification($sender_id, $message, $room_id, $tem_or_permanent, $receiver_id)
    {
        $user = User::find($sender_id);
        $image  = asset("uploads/user/" . $user->image);
        $message =  $message;
        $room_id =  $room_id;
        $clickEvent = $tem_or_permanent;
        Log::info($sender_id);
        Log::info($image);
        Log::info($user->name);
        $data = $this->SendChatNotification($receiver_id, $image, $user->name, $message, $room_id ,$clickEvent, $sender_id );
        return response()->json(['data'=>$data]);
    }
}
// this all done by singh