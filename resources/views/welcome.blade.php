<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make A Payment</title>
</head>
<body class="antialiased">
    <div class="panel panel-default">
        <div class="panel-body">
            <h1 class="text-3xl md:text-5xl font-extrabold text-center uppercase mb-12 bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-600 bg-clip-text text-transparent transform -rotate-2">Make A Payment</h1>
            @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
            @endif
            <form action="{{ route('stripe.storenew') }}" method="POST" id="card-form">
                @csrf
                <div class="mb-3">
                    <label for="card-name" class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Your name</label>
                    <input type="text" name="name" id="card-name" class="border-2 border-gray-200 h-11 px-4 rounded-xl w-full">
                </div>
                <div class="mb-3">
                    <label for="email" class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Email</label>
                    <input type="email" name="email" id="email" class="border-2 border-gray-200 h-11 px-4 rounded-xl w-full">
                </div>
                <div class="mb-3">
                    <label for="card" class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Card details</label>

                    <div class="bg-gray-100 p-6 rounded-xl">
                        <div id="card-element">
                            <!-- Stripe Elements Placeholder -->
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full bg-indigo-500 uppercase rounded-xl font-extrabold text-white px-6 h-12">Pay 👉</button>
            </form>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <!-- Your HTML file -->

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Set your Stripe publishable key
            const stripe = Stripe('pk_test_51P27HwSHTyiLZUYjBiJuBlbf23csodgFA5t1z0QKIs9ffPrzLjPN377pdoe3URmOFf9xB0Phb1gQwdZnlQkJm98v00FStSfNZd');

            // Create an instance of Elements
            const elements = stripe.elements();

            // Create a card Element and add it to the DOM
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            // Handle the form submission
            const form = document.getElementById('card-form');
            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                // Disable the submit button to prevent multiple submissions
                form.querySelector('button[type="submit"]').disabled = true;

                // Create a PaymentIntent
                const response = await fetch('/create-payment-intent', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                    }
                    , body: JSON.stringify({
                        email: document.getElementById('email').value
                        , name: document.getElementById('card-name').value // Pass the name as description
                    })
                });

                const data = await response.json();
                if (data.hasOwnProperty('clientSecret')) {
                    const result = await stripe.confirmCardPayment(data.clientSecret, {
                        payment_method: {
                            card: cardElement
                        , }
                    , });
                    if (result.error) {
                        // Show error to your customer
                        console.error(result.error.message);
                        // Re-enable the submit button
                        form.querySelector('button[type="submit"]').disabled = false;
                    } else {
                        // The payment succeeded, submit the form
                        form.submit();
                    }
                } else {
                    console.error('Unexpected response structure from server');
                    // Re-enable the submit button
                    form.querySelector('button[type="submit"]').disabled = false;
                }
            });
        });

    </script>

</body>
</html>
