<?php
session_start();

require_once '../dashboard/config/database.php';
require_once '../dashboard/includes/auth.php';
require_once '../vendor/autoload.php'; 

$paymentIntentId = isset($_GET['payment_intent']) ? $_GET['payment_intent'] : null;

$orderNumber = '';
$userName = '';
$contactEmail = '';
$billingName = '';
$billingAddress = '';
$landingPage = '';
$cardType = '';
$cardLast4 = '';
$amount = '';

if ($paymentIntentId) {
    \Stripe\Stripe::setApiKey('sk_test_51RYmSVRuYmOMaUOhPOG69YgXqQOG9uefxPizc3nC8GVL2FToqNbV94AWR65Jl9WoXAopWqxrdsgn9pyBijAgumZf00Kl4F3TkE'); 
    try {
        $intent = \Stripe\PaymentIntent::retrieve($paymentIntentId, []);
        $charges = isset($intent->charges) && isset($intent->charges->data) ? $intent->charges->data : [];
        if (is_array($charges) && count($charges) > 0) {
            $charge = $charges[0];
            $orderNumber = $intent->id;
            $amount = number_format($intent->amount_received / 100, 2);
            $contactEmail = $charge->billing_details->email ?? '';
            $billingName = $charge->billing_details->name ?? '';
            $billingAddress = '';
            if (isset($charge->billing_details->address)) {
                $addr = $charge->billing_details->address;
                $billingAddress = trim(
                    ($addr->line1 ?? '') . "\n" .
                    ($addr->line2 ?? '') . "\n" .
                    ($addr->city ?? '') . ', ' . ($addr->state ?? '') . ', ' . ($addr->postal_code ?? '') . "\n" .
                    ($addr->country ?? '')
                );
            }
            $userName = $billingName ?: 'Customer';
            $landingPage = 'SmileDesk Checkout';
            if (isset($charge->payment_method_details->card)) {
                $cardType = ucfirst($charge->payment_method_details->card->brand);
                $cardLast4 = $charge->payment_method_details->card->last4;
            }
        }
    } catch (\Exception $e) {
        $orderNumber = $paymentIntentId;
        $userName = 'Customer';
        $contactEmail = '';
        $billingName = '';
        $billingAddress = '';
        $landingPage = 'SmileDesk Checkout';
        $cardType = '';
        $cardLast4 = '';
        $amount = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <!-- ...existing code for CSS includes... -->
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', Arial, sans-serif;
        }
        .container {
            max-width: 420px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            padding: 32px 24px 24px 24px;
        }
        .checkmark {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 2px solid #1e90ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px auto;
        }
        .checkmark svg {
            color: #1e90ff;
            width: 32px;
            height: 32px;
        }
        .order-number {
            color: #888;
            font-size: 15px;
            text-align: center;
            margin-bottom: 4px;
        }
        .thank-you {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 24px;
        }
        .card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px 18px;
            margin-bottom: 18px;
            border: 1px solid #e5e7eb;
        }
        .card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .card p {
            margin: 0 0 8px 0;
            color: #555;
            font-size: 15px;
        }
        .details-label {
            color: #888;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 2px;
        }
        .details-value {
            font-size: 15px;
            margin-bottom: 8px;
        }
        .payment-method {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }
        .visa-logo {
            width: 36px;
            height: 24px;
            background: #fff;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dashboard-btn {
            width: 100%;
            background: #1e90ff;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            padding: 14px 0;
            margin-top: 18px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .dashboard-btn:hover {
            background: #1877cc;
        }
        .help {
            text-align: center;
            margin-top: 18px;
            color: #555;
            font-size: 15px;
        }
        .help a {
            color: #1e90ff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checkmark">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="order-number">Confirmation #<?php echo htmlspecialchars($orderNumber); ?></div>
        <div class="thank-you">Thank you, <?php echo htmlspecialchars($userName); ?>!</div>
        <div class="card">
            <h3>Your order is confirmed</h3>
            <p>You'll get a confirmation email with your order number soon.</p>
        </div>
        <div class="card">
            <h3>Order details</h3>
            <div class="details-label">Contact information</div>
            <div class="details-value"><?php echo htmlspecialchars($contactEmail); ?></div>
            <div class="details-label">Billing address</div>
            <div class="details-value" style="white-space: pre-line;"><?php echo htmlspecialchars($billingName . "\n" . $billingAddress); ?></div>
            <div class="details-label">Landing Page</div>
            <div class="details-value"><?php echo htmlspecialchars($landingPage); ?></div>
            <div class="details-label">Payment method</div>
            <div class="payment-method">
                <span class="visa-logo">
                    <?php if ($cardType): ?>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="<?php echo htmlspecialchars($cardType); ?>" style="height:16px;">
                    <?php endif; ?>
                </span>
                <span><?php echo htmlspecialchars($cardType); ?> •••• <?php echo htmlspecialchars($cardLast4); ?> - $<?php echo htmlspecialchars($amount); ?></span>
            </div>
        </div>
        <form action="../dashboard/index.php">
            <button class="dashboard-btn" type="submit">Continue to dashboard</button>
        </form>
        <div class="help">
            Need help? <a href="#">Contact us</a>
        </div>
    </div>
</body>
</html>