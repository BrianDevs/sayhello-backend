<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Account;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\OAuth\InvalidRequestException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\Token;

class PaymentController extends Controller
{
    public function checkout($id)
    {
        // echo"hello"; die;
        return view('payment.checkout', compact('id'));
    }
    public function paymentPost(Request $request)
    {
        // echo "checkout";
        try {
            // Retrieve JSON from POST body
            // $jsonStr = file_get_contents('php://input');
            // $jsonObj = json_decode($jsonStr);

            // Set your Stripe secret key from the secrets.php file
            Stripe::setApiKey(env('STRIPE_KEY'));

            // Create a PaymentIntent with amount and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => 1000,
                'currency' => 'inr',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            return response()->json($output);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    private function calculateOrderAmount(array $items): int
    {
        return 1400;
    }
    public function successPayment()
    {
        echo "success";
    }
    public function backMoney()
    {
        // Set your Stripe secret key
        \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));

        // Create a refund
        $refund = \Stripe\Refund::create([
            'payment_intent' => 'pi_3NGhGTSB4pLjzWKk0TQgGp4p',
            'amount' => 100, // Amount to refund in cents
        ]);
        print_r($refund);
        die;
    }
    public function createStripeAccount(Request $request)
    {
        // Set your Stripe API key
        Stripe::setApiKey(env('STRIPE_KEY'));

        // Create a Stripe account
        $account = Account::create([
            'type' => 'standard',
            'country' => 'IN',
            'email' => "singhSahab@gmail.com", // Provide the seller's email
        ]);

        // Generate a verification token
        $token = Token::create([
            'customer' => $account->id,
        ]);

        // Update the seller's Stripe account details
        // $seller->stripe_account_id = $account->id;
        // $seller->stripe_account_verification_token = $token->id;
        // $seller->save();
        print_r($account);
        echo "<pre>";
        print_r($token);
        die;
        // Redirect the seller to the verification page
        return redirect()->away($account->verification->url);
    }
    // public function processPayment(Request $request)
    // {
    //     Stripe::setApiKey(env('STRIPE_KEY'));

    //     $charge = Charge::create([
    //         'amount' => 1000,
    //         'currency' => 'inr',
    //         'source' => $request->input('token'),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Payment processed successfully.',
    //         'charge' => $charge,
    //     ]);


    //        // Set your Stripe API key
    //     //    Stripe::setApiKey(env('STRIPE_KEY'));

    //     //    // Get the payment method ID from the request (received from React Native)
    //     //    $paymentMethodId = $request->input('paymentMethodId');

    //     // //    try {
    //     //        // Create a PaymentIntent with the payment method ID and desired amount
    //     //        $paymentIntent = PaymentIntent::create([
    //     //            'amount' => 1000, // Amount in cents (e.g., $10.00)
    //     //            'currency' => 'inr',
    //     //            'payment_method' => $paymentMethodId,
    //     //            'confirm' => true,
    //     //        ]);

    //     //        // Handle successful payment
    //     //        // You can add your custom logic here

    //     //        return response()->json(['success' => true, 'message' => 'Payment succeeded' , 'data'=>$paymentIntent]);
    //     //    } catch (\Exception $e) {
    //     //        // Handle payment failure
    //     //        // You can add your custom error handling here

    //     //        return response()->json(['success' => false, 'message' => 'Payment failed']);
    //     //    }
    // }
    
    // public function processPayment(Request $request)
    // {
    //     Stripe::setApiKey(env('STRIPE_KEY'));

    //     try {
    //         // Get the client secret from the request
    //         $clientSecret = $request->input('client_secret');

    //         // Collect the card details from the user
    //         $cardToken = $request->input('card_token'); // Assuming you receive a token from the frontend or the actual card details

    //         // Confirm the payment with Stripe API
    //         $paymentIntent = PaymentIntent::confirm($clientSecret, [
    //             'payment_method' => $cardToken,
    //         ]);
    //         // $paymentIntent = PaymentIntent::create([
    //         //     'amount' => 1000,
    //         //     'currency' => 'inr',
    //         //     'automatic_payment_methods' => [
    //         //         'enabled' => true,
    //         //     ],
    //         // ]);

    //         // Check the payment status and return the response
    //         if ($paymentIntent->status === 'succeeded') {
    //             return response()->json(['status' => 'success']);
    //         } else {
    //             return response()->json(['status' => 'failed', 'message' => 'Payment confirmation failed.']);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
    //     }
    // }
    // public function processPayment(Request $request)
    // {
    //     // Set your Stripe API secret key
    //     Stripe::setApiKey(env('STRIPE_KEY'));

    //     // Get the paymentMethodId from the request body
    //     $paymentMethodId = $request->input('paymentMethodId');

    //     // Create a new payment intent using the paymentMethodId
    //     $paymentIntent = PaymentIntent::create([
    //         // 'email'=> ''
    //         'payment_method' => $paymentMethodId,
    //         'amount' => 1000, // Specify the amount in cents (e.g., $10.00)
    //         'currency' => 'inr',
    //         'description' => 'Payment for your product/service',
    //         // Additional parameters if needed
    //     ]);
    //     // echo"<pre>";print_r($paymentIntent);die;
    //     // Process the payment and handle any errors
    //     try {
    //             // Process the payment and handle any errors
    //     switch ($paymentIntent->status) {
    //         case 'requires_action':
    //             // Additional action required, such as 3D Secure authentication
    //             // Return the payment intent client secret to the client-side
    //             return response()->json(['status' => 'requires_action', 'client_secret' => $paymentIntent->client_secret]);
    //         case 'requires_payment_method':
    //             // Handle cases where the payment method is invalid or needs to be updated
    //             return response()->json(['status' => 'error', 'message' => 'Payment method requires update' , 'data'=>$paymentIntent]);
    //         case 'succeeded':
    //             // Payment confirmed successfully
    //             return response()->json(['status' => 'success', 'message' => 'Payment confirmed' , 'data'=>$paymentIntent]);
    //         default:
    //             // Handle other payment intent statuses
    //             return response()->json(['status' => 'error', 'message' => 'Payment status: ' . $paymentIntent->status]);
    //     }
    //     } catch (\Stripe\Exception\CardException $e) {
    //         // Handle card errors
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //     } catch (\Stripe\Exception\ApiErrorException $e) {
    //         // Handle other Stripe API errors
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //     }catch(\Stripe\Exception\InvalidRequestException $e){
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);

    //     }
    //      catch (\Exception $e) {
    //         // Handle general exceptions
    //         return response()->json(['status' => 'error', 'message' => 'An error occurred. Please try again.']);
    //     }
    // }
    public function processPayment(Request $request)
    {
         // Set your secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = Charge::create([
                'amount' => 1000, // amount in cents
                'currency' => 'INR',
                'source' => $request->input('token'), // the token obtained from the client-side
                'description' => 'Charge description', // optional
            ]);

            // Charge success, handle the result
            return response()->json(['message' => 'Charge successful']);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
