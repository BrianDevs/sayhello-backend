<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Accept a payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('payment/checkout.css')}}">
    <script src="https://js.stripe.com/v3/"></script>
    <script async src="https://js.stripe.com/v3/pricing-table.js"></script>
    <stripe-pricing-table pricing-table-id="prctbl_1NQn9XSB4pLjzWKksAHc44wf"
    publishable-key="pk_test_51Mx1vCSB4pLjzWKkpz54QGtrbTn9AyVfJWExkJtR0ZggnmbUuPu7kvPjJst3ycukIuSTwjMZZCplSom3jV9cOF6e00Css0rDZp">
    </stripe-pricing-table>
    </head>

<body>
    <!-- Display a payment form -->
    <form id="payment-form">
        @csrf
        <div id="link-authentication-element">
            <!--Stripe.js injects the Link Authentication Element-->
        </div>
        <div id="payment-element">
            <!--Stripe.js injects the Payment Element-->
        </div>
        <button id="submit">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Pay $24.99</span>
        </button>
        <div id="payment-message" class="hidden"></div>
    </form>

   
    <script>
        // This is your test publishable API key.

        // Client test key
        const stripe = Stripe(
            "pk_test_51Mx1vCSB4pLjzWKkpz54QGtrbTn9AyVfJWExkJtR0ZggnmbUuPu7kvPjJst3ycukIuSTwjMZZCplSom3jV9cOF6e00Css0rDZp"
        );
        // The items the customer wants to buy
        const items = [{
            amount: "1000",
        }];

        let elements;

        initialize();
        checkStatus();

        document
            .querySelector("#payment-form")
            .addEventListener("submit", handleSubmit);

        let emailAddress = '';
        // Fetches a payment intent and captures the client secret
        async function initialize() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const {
                clientSecret
            } = await fetch("{{ route('payment_post') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    items
                }),
            }).then((r) => r.json());

            elements = stripe.elements({
                clientSecret
            });

            const linkAuthenticationElement = elements.create("linkAuthentication");
            linkAuthenticationElement.mount("#link-authentication-element");

            const paymentElementOptions = {
                layout: "tabs",
            };

            const paymentElement = elements.create("payment", paymentElementOptions);
            paymentElement.mount("#payment-element");

            console.log("paymentElement==========",paymentElement);
        }

        async function handleSubmit(e) {
            e.preventDefault();
            setLoading(true);

            const {
                error
            } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Make sure to change this to your payment completion page
                    return_url: "{{ route('success_payment') }}",
                    receipt_email: emailAddress,
                },
            });

            if (error.type === "card_error" || error.type === "validation_error") {
                showMessage(error.message);
            } else {
                showMessage("An unexpected error occurred.");
            }

            setLoading(false);
        }

        // Fetches the payment intent status after payment submission
        async function checkStatus() {
            const clientSecret = new URLSearchParams(window.location.search).get(
                "payment_intent_client_secret"
            );

            if (!clientSecret) {
                return;
            }

            const {
                paymentIntent
            } = await stripe.retrievePaymentIntent(clientSecret);

            switch (paymentIntent.status) {
                case "succeeded":
                    showMessage("Payment succeeded!");
                    break;
                case "processing":
                    showMessage("Your payment is processing.");
                    break;
                case "requires_payment_method":
                    showMessage("Your payment was not successful, please try again.");
                    break;
                default:
                    showMessage("Something went wrong.");
                    break;
            }
        }

        // ------- UI helpers -------

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function() {
                messageContainer.classList.add("hidden");
                messageText.textContent = "";
            }, 4000);
        }

        // Show a spinner on payment submission
        function setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }
    </script>
</body>

</html>
