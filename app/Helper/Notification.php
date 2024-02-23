<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;

class Notification{

    public static function Notification($device_token,$data)
    {
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        // Put your Server Key here
        $apiKey = "AAAAIkpMzKY:APA91bFcM5QTAmxxHtz0oAYZ5s1qZl_hu9AigP1YoMTDCqX05LWyR21RgaXBomgbEQqri6AdBjCJxFzZW87xF7Ht1TeBfGog7AcsW5Iq-YrcB2Z5APXIcwbDPRJuuWpK2YAOzWGy5Tyq";
        // Compile headers in one variable
        $headers = array (
          'Authorization:key=' . $apiKey,
          'Content-Type:application/json'
        );
        $message = array(
            'message' => $data,
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
        );
        $registration_ids =  $device_token;
        // Create the api body
        $apiBody =array(
          'notification' => $data,
          'time_to_live' => 600, // optional - In Seconds
          'to' => $registration_ids,
          'data' => $message,
        );
        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
        $result = curl_exec($ch);
        // print_r($result);
        // Log::info($data);
        // Log::info($result);
        curl_close($ch);
    }
}