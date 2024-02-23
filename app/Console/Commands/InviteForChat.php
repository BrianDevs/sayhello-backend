<?php

namespace App\Console\Commands;

use App\Helper\Notification;
use App\Models\ChatBox;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InviteForChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:InviteForChat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ChatBoxes = ChatBox::where('status' , 0)->get();
        // Log::info("Ha chl rha huu");
        if(count($ChatBoxes)>0){
            foreach($ChatBoxes as $Chat){
                date_default_timezone_set('asia/kolkata');
                $dateTime = $Chat->dateTime;
                $timeWithoutLastThreeChars = substr($dateTime, 0, -3);
                //chnage this +10 minutes into +12 hours after test
                $timeAfterOneHourOfBookingTime = date("Y-m-d H:i:s", strtotime("+10 minutes", strtotime($timeWithoutLastThreeChars)));

                // Log::info($timeWithoutLastThreeChars);
                
                // Log::info($timeAfterOneHourOfBookingTime);

                if(strtotime(date('Y-m-d H:i:s')) > strtotime($timeAfterOneHourOfBookingTime)){
                    $data['greater'] = 'yes';
                    $data['id'] = $Chat->id;
                    $users = User::where('id', $Chat->my_id)->orWhere('id', $Chat->stranger_id)->get();
                    foreach($users as $user)
                    {
                        $title = "SayHello";
                        $body = "Invite For Chat!";
                        $apiKey = "AAAAIkpMzKY:APA91bFcM5QTAmxxHtz0oAYZ5s1qZl_hu9AigP1YoMTDCqX05LWyR21RgaXBomgbEQqri6AdBjCJxFzZW87xF7Ht1TeBfGog7AcsW5Iq-YrcB2Z5APXIcwbDPRJuuWpK2YAOzWGy5Tyq";
                        
                            $registration_ids =  $user->device_token;
                            // Log::info($registration_ids);
                            $client = new Client();
                            $response = $client->post('https://fcm.googleapis.com/fcm/send', [
                                'headers' => [
                                    'Authorization' => 'key= '. $apiKey,
                                    'Content-Type' => 'application/json',
                                ],
                                'json' => [
                                    'to' => $registration_ids,
                                    'notification' => [
                                        'title'           => $title,
                                        'body'            => $body,
                                        "clickEvent"      => "InviteForChat",
                                        "id"              => $user->id,
                                        'otp'             => null,
                                    ]
                                ]
                            ]);
                            $res = $response->getBody()->getContents();
                            // Log::info("Response of notification ".$res);
                    }
                }
            }
        }else{
            // Log::info("pr data nhi mere pass");
        }
    }
}
