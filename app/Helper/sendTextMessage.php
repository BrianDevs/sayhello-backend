<?php
namespace App\Helper;

use Exception;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class sendTextMessage{
    public static function sendTextMessage($country_code,$number,$otp){
        // Log::info($otp);
        // Log::info($number);
        // Log::info($country_code);
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');
        
        $client = new Client($twilioSid, $twilioToken);
        // Log::info($client);
        try {
            // Log::info('Inside the try =-=====================');

            $client->messages->create(
                // '+1 (615) 612-8036', // Replace with the recipient's phone number
                // '+1 6156128036',
                $country_code.' '.$number,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => `Your one time OTP is $otp`
                ]
            );
            // Message sent successfully
            // Log::info('Text message sent successfully.');
            
            return response()->json(['message' => 'Text message sent.']);
        } catch (Exception $e) {
            // Log::info($e);
            // Error sending the message
            Log::error('Error sending text message: ' . $e->getMessage());
            
            return response()->json(['error' => 'Failed to send text message.']);
        }
    }
}