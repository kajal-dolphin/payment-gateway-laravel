<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $stripe = new StripeClient('sk_test_51P27HwSHTyiLZUYjC1DyIstykQN0vWcaZnBP2NpfK7ETtTM40CsSExepU2cJLm8bUdDzEJxOYix6jIV9vmYaM6Gb00ZpQW5reS');

        $customer = $stripe->customers->create([
            'name' => "TEST",
            'address' => [
                'line1' => 'Ahmedabad',
                'postal_code' => '380001',
                'country' => 'IN',
            ],
        ]);

        // Create a PaymentIntent associated with the customer
        $paymentIntent = $stripe->paymentIntents->create([
            'description' => 'Warranty Vault',
            'amount' => 1000, // Amount in cents
            'currency' => 'INR',
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
        ]);

        // Log the PaymentIntent data
        Log::info('PaymentIntent data:', ['paymentIntent' => $paymentIntent]);

        $clientSecret = $paymentIntent->client_secret;

        return response()->json(['clientSecret' => $clientSecret]);
    }

    public function storeNewPayment(Request $request)
    {
        // Handle the payment confirmation and other operations if necessary
        return redirect()->back()->with('success', 'Payment successful!');
    }
}
