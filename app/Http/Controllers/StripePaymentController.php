<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Redirect;

class StripePaymentController extends Controller
{
    public function index()
    {
        return view('stripe.index');
    }

    public function store(Request $request)
    {
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET'));

            $customer = $stripe->customers->create([
                'name' => $request->name,
                'address' => [
                    'line1' => 'Ahmedabad',
                    'postal_code' => '380001',
                    'city' => 'Ahmedabad',
                    'state' => 'GUJ',
                    'country' => 'IN',
                ],
            ]);

            //paymentIntialize
            // with this in stripe you can find out all details of payment like customer data, payment details using payment _id
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => 12345,
                'currency' => 'usd',
                'payment_method' => $request->payment_method,
                'description' => 'Demo payment with stripe',
                'payment_method_types' => ['card'],
                'receipt_email' => $request->email,
                'confirm' => true,
                'return_url' => route('payment.success'),
                'customer' => $customer->id
            ]);
            dd($paymentIntent);

        } catch (CardException $th) {
            throw new Exception("There was a problem processing your payment", 1);
        }

        return back()->withSuccess('Payment done.');

    }
}
