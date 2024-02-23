<?php

namespace App\Helper;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthHelper{

    public static function Auth($userData , $userId)
    {
        $user = User::find($userId);
        $url = 'https://api-v3.authenticating.com/user/create';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer LZRTMQDEMFCGWVSNEERVI2ZTMFXT43CMHFRV2JJUOQYUO23O',
            'Content-Type' => 'application/json',
        ])->post($url, $userData);
        
        if ($response->successful()) {
            $data = $response->json();
            $user->update(['userAccessCode'=>$data['userAccessCode']]);

            $urlConsent = 'https://api-v3.authenticating.com/user/consent';
            $dataConsent = [
                "userAccessCode"=>$data['userAccessCode'],
                "isBackgroundDisclosureAccepted"=> 1,
                "GLBPurposeAndDPPAPurpose"=> 1,
                "FCRAPurpose"=> 1,
                "fullName"=> $userData['firstName'].' '.$userData['lastName']
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer LZRTMQDEMFCGWVSNEERVI2ZTMFXT43CMHFRV2JJUOQYUO23O',
                'Content-Type' => 'application/json',
            ])->post($urlConsent, $dataConsent);
            if ($response->successful()) {
                $data = $response->json();
                return $data;
            } else {
                $error = $response->json();
                return $error;
            }
        } else {
            $error = $response->json();
            return $error;
        }
    }
    public static function UploadIds($idFrontBase64, $idBackBase64, $userId)
    {
        $user = User::find($userId);
        $url = 'https://api-v3.authenticating.com/identity/document/scan';
        $data = [
            "userAccessCode"=>$user['userAccessCode'],
            "idFront"=> $idFrontBase64,
            "idBack"=> $idBackBase64,
            "country"=> 0,  //USA = 0 , ASIA=5
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer LZRTMQDEMFCGWVSNEERVI2ZTMFXT43CMHFRV2JJUOQYUO23O',
            'Content-Type' => 'application/json',
        ])->post($url, $data);
        if ($response->successful()) {
            $data = $response->json();
            // Log::info($data);
           
        } else {
            $error = $response->json();
            // Log::info($error);
            return $error;
        }
    }
}