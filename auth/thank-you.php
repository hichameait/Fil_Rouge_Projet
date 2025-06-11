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
$orderDone = false;
$cardFunding = '';
$cardCountry = '';

$cardLogos = [
    'Visa' => 'https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png',
    'Mastercard' => 'https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png',
    'Amex' => 'https://upload.wikimedia.org/wikipedia/commons/3/30/American_Express_logo_%282018%29.svg',
    'Discover' => 'https://upload.wikimedia.org/wikipedia/commons/5/5f/Discover_Card_logo.svg'
];
$logoUrl = null;

$userRow = null;
if (isset($_SESSION['email'])) {
    $stmt = $pdo->prepare("SELECT first_name, last_name, address, email FROM users WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($paymentIntentId) {
    \Stripe\Stripe::setApiKey('sk_test_51RYmSVRuYmOMaUOhPOG69YgXqQOG9uefxPizc3nC8GVL2FToqNbV94AWR65Jl9WoXAopWqxrdsgn9pyBijAgumZf00Kl4F3TkE');
    try {
        $intent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        
        if ($intent->status === 'succeeded') {
            $orderDone = true;
            $_SESSION['payment_completed'] = true;
            $_SESSION['is_logged'] = true; // Set login status
            
            // Get the user email from metadata
            $userEmail = $intent->metadata['user_email'] ?? $_SESSION['email'] ?? null;
            
            if ($userEmail) {
                try {
                    // Update user status and ensure billing details are saved
                    $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE email = ?");
                    $stmt->execute([$userEmail]);

                    // Store user data in session
                    $_SESSION['user_status'] = 'active';
                    $_SESSION['auth'] = true;
                    
                    // Double-check if address exists, if not save it from metadata
                    if (isset($intent->metadata['billing_address'])) {
                        $billingAddress = json_decode($intent->metadata['billing_address'], true);
                        $fullAddress = implode("\n", array_filter([
                            $billingAddress['line1'] ?? '',
                            $billingAddress['line2'] ?? '',
                            ($billingAddress['city'] ?? '') . ', ' . ($billingAddress['postal_code'] ?? '')
                        ]));
                        
                        $stmt = $pdo->prepare("UPDATE users SET address = ? WHERE email = ? AND (address IS NULL OR address = '')");
                        $stmt->execute([$fullAddress, $userEmail]);
                    }
                } catch (PDOException $e) {
                    error_log("Database error in thank-you.php: " . $e->getMessage());
                }
            }
        } else {
            // Payment not succeeded
            header('Location: checkout.php');
            exit;
        }
        
        $charges = $intent->charges->data ?? [];
        if (is_array($charges) && count($charges) > 0) {
            $charge = $charges[0];
            $orderNumber = $intent->id;
            $amount = number_format($intent->amount_received / 100, 2);
            $contactEmail = $charge->billing_details->email ?? ($userRow['email'] ?? '');
            $billingName = $charge->billing_details->name ?? (($userRow['first_name'] ?? '') . ' ' . ($userRow['last_name'] ?? ''));
            $billingAddress = '';
            if (isset($charge->billing_details->address)) {
                $addr = $charge->billing_details->address;
                $billingAddress = trim(
                    ($addr->line1 ?? '') . "\n" .
                    ($addr->line2 ?? '') . "\n" .
                    ($addr->city ?? '') . ', ' . ($addr->state ?? '') . ', ' . ($addr->postal_code ?? '') . "\n" .
                    ($addr->country ?? '')
                );
            } else {
                $billingAddress = $userRow['address'] ?? '';
            }
            $userName = $billingName ?: 'Customer';
            $landingPage = 'SmileDesk Standar Plan';

            $cardObj = null;
            if (isset($charge->payment_method) && $charge->payment_method->type === 'card') {
                $cardObj = $charge->payment_method->card;
            } elseif (isset($intent->payment_method) && $intent->payment_method->type === 'card') {
                $cardObj = $intent->payment_method->card;
            } elseif (isset($charge->payment_method_details->card)) {
                $cardObj = $charge->payment_method_details->card;
            }

            if ($cardObj) {
                $cardType = ucfirst($cardObj->brand ?? '');
                $cardLast4 = $cardObj->last4 ?? '';
                $cardFunding = $cardObj->funding ?? '';
                $cardCountry = $cardObj->country ?? '';
                $logoUrl = $cardLogos[$cardType] ?? null;
            } else {
                $cardType = '';
                $cardLast4 = '';
                $cardFunding = '';
                $cardCountry = '';
                $logoUrl = null;
            }
        } else {
            // Payment not succeeded
            $orderDone = false;
            $orderNumber = $intent->id;
            $userName = (($userRow['first_name'] ?? '') . ' ' . ($userRow['last_name'] ?? ''));
            $contactEmail = $userRow['email'] ?? '';
            $billingName = $userName;
            $billingAddress = $userRow['address'] ?? '';
            $landingPage = 'SmileDesk Standar Plan';
            $cardType = '';
            $cardLast4 = '';
            $cardFunding = '';
            $cardCountry = '';
            $logoUrl = null;
            $amount = '';
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Log or report to admin
        error_log("Stripe error: " . $e->getMessage());
        $orderDone = false;
        $orderNumber = $paymentIntentId;
        $userName = (($userRow['first_name'] ?? '') . ' ' . ($userRow['last_name'] ?? ''));
        $contactEmail = $userRow['email'] ?? '';
        $billingName = $userName;
        $billingAddress = $userRow['address'] ?? '';
        $landingPage = 'SmileDesk Standar Plan';
        $cardType = '';
        $cardLast4 = '';
        $cardFunding = '';
        $cardCountry = '';
        $logoUrl = null;
        $amount = '';
        // User status remains inactive
    }
} else {
    // No payment intent, redirect to checkout
    header('Location: checkout.php');
    exit;
}

// Redirect if trying to access without payment
if (!isset($_SESSION['payment_completed']) || !$_SESSION['payment_completed']) {
    header('Location: checkout.php');
    exit;
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
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
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
            <div class="details-value"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
            <div class="details-label">Billing address</div>
            <div class="details-value" style="white-space: pre-line;">
                <?php echo htmlspecialchars($billingName . "\n" . $billingAddress); ?></div>
            <div class="details-label">Subscription Plan</div>
            <div class="details-value"><?php echo htmlspecialchars($landingPage); ?></div>
            
        </div>
        <?php if ($orderDone): ?>
            <form action="../dashboard/index.php" method="POST">
                <input type="hidden" name="payment_verified" value="true">
                <button class="dashboard-btn" type="submit">Continue to dashboard</button>
            </form>
        <?php else: ?>
            <button class="dashboard-btn" onclick="window.location.href='../dashboard/index.php'">Continue to Dashboard</button>
        <?php endif; ?>
        <div class="help">
            Need help? <a href="#">Contact us</a>
        </div>
    </div>
</body>

</html>