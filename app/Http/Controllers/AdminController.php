<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Question;
use App\Models\Sponser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;



class AdminController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::check()){
            $TotalUsers = User::where('user_type' , 0)
                        ->withTrashed()
                        ->get();
            $totalSponsers = Sponser::whereNull('deleted_at')->get();
            return view('index' , compact('TotalUsers' , 'totalSponsers'));
        }else{
            return redirect()->route('login');
        }
    }
    public function adminLogin(Request $request)
    {
        $ValidateFailed = $request->validate([
                                'email' => ['required', 'string', 'email', 'max:255'],
                                'password' => 'required|string|min:8',
                            ]);                          
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('index');
        }else{
            return back()->withErrors([
                'credentials' => 'The provided credentials do not match our records.',
            ]);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function getUsers()
    {
        $users = User::where('user_type' , 0)->get();        
        return view('users' , compact('users'));
    }
    public function getBanner()
    {
        $banners = Banner::where('deleted_at', null)->where('home_on_not' , 0)->get();
        $home = Banner::where('deleted_at', null)->where('home_on_not' , 1)->get();

        return view('banner' , compact('banners' , 'home'));
    } 
    public function getSponsers()
    {
        $sponsers = Sponser::where('deleted_at' , null)->get();
        return view('sponsers' , compact('sponsers'));
    }
    public function getQuestions()
    {
        $Questions = Question::where('deleted_at' , null)->simplePaginate(10);
        return view('questions' , compact('Questions'));
    }
    public function userDetails($id)
    {
        $user = User::where('user_type' , 0)->find($id);
        if($user){
            return view('user-details' , compact('user'));
        }
        return back();
    }
    public function userQuestionAnswerList($id)
    {
        $users = User::rightJoin('user_question_answers' , 'user_question_answers.user_id' , 'users.id')
                    ->leftJoin('questions' , 'user_question_answers.question_id' , 'questions.id')
                    ->select('question' , 'answer' , 'users.name')
                    ->where('user_type' , 0)
                    ->where('users.id', $id)
                    ->get();
                    $userName = User::find($id);
        // echo"<pre>"; print_r($users[0]->name); die;                    
        return view('user-question-answer-list' , compact('users', 'userName'));
    }
    public function addBanner()
    {
        $home = 0;
        return view('add-banner', compact('home'));
    }
    public function addHomeBanner()
    {
        $home = 1;
        return view('add-banner', compact('home'));
    }
    public function addFormBanner(Request $request)
    {
        $request->validate([
            'title' => ['required', 'unique:banners,title', 'max:255'],
            'file' => 'required|mimes:png,jpg,jpeg|max:5120',
        ]); 
        if($request->hasFile('file')){
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().".".$extension;
            $file->move(public_path('uploads/banners/') ,$fileName);
            $request['image'] = $fileName;
        }
        Banner::create($request->all());
        return back()->withErrors([
            'Success' => 'Banner has been created successfully!.',
        ]);
    }
    public function bannerActiveDeactive($id)
    {
        $banner = Banner::find($id);
        if($banner->is_active == 1){
            $banner->is_active = 0; 
            $banner->update();
            return response()->json(['status'=>true , 'message'=>'active']);
        }else{
            $banner->is_active = 1;
            $banner->update();
            return response()->json(['status'=>true , 'message'=>'deactive']);
        }
    }
    public function editBanner($id)
    {
        $banner = Banner::find($id);
        if($banner){
            return view('edit-banner' , compact('banner'));
        }else{
            return back();
        }
    }
    public function editBannerForm($id, Request $request)
    {
        $request->validate([
            'title' => ['required', 'unique:banners,title,'.$id, 'max:255'],
            // 'file' => 'required|mimes:png,jpg,jpeg|max:5120',
        ]); 
        $banner = Banner::find($id);
        if($request->hasFile('file')){
            $fileExixts = public_path('uploads/banners/'.$banner->image);
            if(File::exists($fileExixts)){
                File::delete($fileExixts);
            }
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().".".$extension;
            $file->move(public_path('uploads/banners/') ,$fileName);
            $request['image'] = $fileName;
        }
        $data = $request->all();
        $banner->update($data); 
        return back()->withErrors([
            'Success' => 'Banner has been updated successfully!.',
        ]);
    }
    public function deleteBanner($id)
    {
        $banner = Banner::find($id);
        if($banner){
            // $banner->is_active = 0; 
            $banner->delete();
            return response()->json(['status'=>true , 'message'=>'delete']);
        }
    }
    public function addSponserPage()
    {
        return view('add-Sponser-Page');
    }
    public function addSponsersForm(Request $request)
    {
        $request->validate([
            'title'         => ['required', 'unique:sponsers,title', 'max:255'],
            'file'          => 'required|mimes:png,jpg,jpeg|max:5120',
            'url'           => 'required|max:255',
            'description'   => 'required',
        ]); 
        if($request->hasFile('file')){
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().".".$extension;
            $file->move(public_path('uploads/sponsers/') ,$fileName);
            $request['image'] = $fileName;
        }
        Sponser::create($request->all());
        return back()->withErrors([
            'Success' => 'Sponser has been created successfully!.',
        ]);
    }
    public function sponsersAcDac($id)
    {
        $sponsers = Sponser::find($id);
        if($sponsers->is_active == 0){
            $sponsers->update(['is_active'=>1]);
            return response()->json(['status'=>true , 'message'=>"deactive"]);
        }else{
            $sponsers->update(['is_active'=>0]);
            return response()->json(['status'=>true , 'message'=>"active"]);
        }
    }
    public function deleteSponser($id)
    {
        $sponser = Sponser::find($id);
        if($sponser){
            $sponser->delete();
            return response()->json(['status'=>true , 'message'=>'delete']);
        }
    }
    public function editSponser($id ,Request $request)
    {
        $Sponser = Sponser::find($id);
        if($Sponser){
            return view('edit-sponser', compact('Sponser'));
        }else{
            return back();
        }
    }
    public function editSponserForm($id ,Request $request)
    {
        $request->validate([
            'title'         => ['required', 'unique:sponsers,title,'.$id, 'max:255'],
            'url'           => 'required|max:255',
            'description'   => 'required',
        ]); 
        $Sponser = Sponser::find($id);
        if($Sponser){
            if($request->hasFile('file')){
                $fileExixts = public_path('uploads/sponsers/'.$Sponser->image);
                if(File::exists($fileExixts)){
                    File::delete($fileExixts);
                }
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().".".$extension;
                $file->move(public_path('uploads/sponsers/') ,$fileName);
                $request['image'] = $fileName;
            }
            $Sponser->update($request->all());
            return back()->withErrors([
                'Success' => 'Sponser has been updated successfully!.',
            ]);
        }
    }
    public function questionAcDac($id)
    {
        $Question = Question::find($id);
        if($Question->is_active == 1){
            $Question->update(['is_active'=>0]);
            return response()->json(['status'=>true , 'message'=>'active']);
        }else{
            $Question->update(['is_active'=>1]);
            return response()->json(['status'=>true , 'message'=>'deactive']);
        }

    }
    public function questionForm(Request $request)
    {
        $translate = new GoogleTranslate();
        $translations = [
            'zh' => $translate->setSource('en')->setTarget('zh')->translate($request->question), // Translate to Chinese
            'es' => $translate->setSource('en')->setTarget('es')->translate($request->question), // Translate to Spanish
            'fr' => $translate->setSource('en')->setTarget('fr')->translate($request->question), // Translate to French
            'de' => $translate->setSource('en')->setTarget('de')->translate($request->question), // Translate to German
            'it' => $translate->setSource('en')->setTarget('it')->translate($request->question), // Translate to Italian
            'ru' => $translate->setSource('en')->setTarget('ru')->translate($request->question), // Translate to Russian
            'pt' => $translate->setSource('en')->setTarget('pt')->translate($request->question), // Translate to Portuguese
        ];

        foreach ($translations as $language => $translation) {
            if($language == 'zh'){
                $request['question_ch'] = $translation;
            }else if($language == 'es'){
                $request['question_es'] = $translation;
            }else if($language == 'fr'){
                $request['question_fr'] = $translation;
            }else if($language == 'de'){
                $request['question_de'] = $translation;
            }else if($language == 'it'){
                $request['question_it'] = $translation;
            }else if($language == 'ru'){
                $request['question_ru'] = $translation;
            }else{
                $request['question_pt'] = $translation;
            }
        }
        Question::create($request->all());
        return response()->json(['status'=>true , 'message'=>'added']);
    }
    public function deleteQuestion($id)
    {
        $Question = Question::find($id);
        if($Question){
            $Question->delete();
            return response()->json(['status'=>true , 'message'=>'delete']);
        }else{
            return response()->json(['status'=>false , 'message'=>'NotDelete']);
        }
    }
    public function QuestionEdit($id)
    {
        $Question = Question::find($id);
        if($Question){
            return response()->json(['status'=>200 , 'data'=>$Question]);
        }
    }
    public function updateQuestionForm(Request $request)
    {
        $Question = Question::find($request->id);
        if($Question){
            $translate = new GoogleTranslate();
            $translations = [
                'zh' => $translate->setSource('en')->setTarget('zh')->translate($request->question), // Translate to Chinese
                'es' => $translate->setSource('en')->setTarget('es')->translate($request->question), // Translate to Spanish
                'fr' => $translate->setSource('en')->setTarget('fr')->translate($request->question), // Translate to French
                'de' => $translate->setSource('en')->setTarget('de')->translate($request->question), // Translate to German
                'it' => $translate->setSource('en')->setTarget('it')->translate($request->question), // Translate to Italian
                'ru' => $translate->setSource('en')->setTarget('ru')->translate($request->question), // Translate to Russian
                'pt' => $translate->setSource('en')->setTarget('pt')->translate($request->question), // Translate to Portuguese
            ];
    
            foreach ($translations as $language => $translation) {
                if($language == 'zh'){
                    $request['question_ch'] = $translation;
                }else if($language == 'es'){
                    $request['question_es'] = $translation;
                }else if($language == 'fr'){
                    $request['question_fr'] = $translation;
                }else if($language == 'de'){
                    $request['question_de'] = $translation;
                }else if($language == 'it'){
                    $request['question_it'] = $translation;
                }else if($language == 'ru'){
                    $request['question_ru'] = $translation;
                }else{
                    $request['question_pt'] = $translation;
                }
            }
            $Question->update($request->all());
            return response()->json(['status'=>true , 'message'=>'updated']);
        }
    }
    public function search($search = null)
    {
        if($search == null || $search === 'undefined'){
            $users = User::where('user_type', 0)->get();
            if(count($users) > 0){
                return response()->json(['status'=>200 , 'data'=>$users]);
            }else{
    
            }
        }else{
            $users = User::where('name', 'LIKE', '%'.$search.'%')->where('user_type', 0)->get();
            if(count($users) > 0){
                return response()->json(['status'=>200 , 'data'=>$users]);
            }else{
    
            }

        }
        // echo"<pre>"; print_r($search); die;
    }
    public function varifiedUser($id)
    {
        $user = User::find($id);
        if($user->is_active == 1){
            $user->update(['is_active'=>0]);
            return response()->json(['status'=>true , 'message'=>'active']);
        }else{
            $user->update(['is_active'=>1]);
            return response()->json(['status'=>true , 'message'=>'deactive']);
        }
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json(['status'=>true , 'message'=>'delete']);
        }
    }

    public function sendTextMessage(Request $request)
    {
        $translate = new GoogleTranslate();

        $translations = [
            'zh' => $translate->setSource('en')->setTarget('zh')->translate('Hello, how are you?'), // Translate to Chinese
            'es' => $translate->setSource('en')->setTarget('es')->translate('Hello, how are you?'), // Translate to Spanish
            'fr' => $translate->setSource('en')->setTarget('fr')->translate('Hello, how are you?'), // Translate to French
            'de' => $translate->setSource('en')->setTarget('de')->translate('Hello, how are you?'), // Translate to German
            'it' => $translate->setSource('en')->setTarget('it')->translate('Hello, how are you?'), // Translate to Italian
            'ru' => $translate->setSource('en')->setTarget('ru')->translate('Hello, how are you?'), // Translate to Russian
            'pt' => $translate->setSource('en')->setTarget('pt')->translate('Hello, how are you?'), // Translate to Portuguese
        ];

        foreach ($translations as $language => $translation) {
            if($language == 'zh'){

            }else if($language == 'es'){

            }else if($language == 'fr'){

            }else if($language == 'de'){

            }else if($language == 'it'){

            }else if($language == 'ru'){

            }else{
                // pt

            }
            echo "Translation to $language: $translation" . PHP_EOL;
        }

    }

}