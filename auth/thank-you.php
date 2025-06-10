<?php
    session_start();

    require_once '../dashboard/config/database.php';
    require_once '../dashboard/includes/auth.php';

    // if (isLoggedIn()) {
    //     header('Location: ../dashboard/index.php');
    //     exit;
    // }

    $orderNumber = 'DQFDHG5E0';
    $userName = 'Adam';
    $contactEmail = 'jordan.chen@domain.com';
    $billingName = 'Jordan Chen';
    $billingAddress = "151 O'Connor St\nOttawa, ON, K2P 2L8\nCanada";
    $landingPage = 'Dentist Landing Page v1';
    $cardType = 'Visa';
    $cardLast4 = '1234';
    $amount = '74.00';
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
        <div class="order-number">Confirmation #<?php echo $orderNumber; ?></div>
        <div class="thank-you">Thank you, <?php echo htmlspecialchars($userName); ?>!</div>
        <div class="card">
            <h3>Your order is confirmed</h3>
            <p>You'll get a confirmation email with your order number soon.</p>
        </div>
        <div class="card">
            <h3>Order details</h3>
            <div class="details-label">Contact information</div>
            <div class="details-value"><?php echo htmlspecialchars($contactEmail); ?></div>
            <div class="details-label">billing address</div>
            <div class="details-value" style="white-space: pre-line;"><?php echo htmlspecialchars($billingName . "\n" . $billingAddress); ?></div>
            <div class="details-label">Landing Page</div>
            <div class="details-value"><?php echo htmlspecialchars($landingPage); ?></div>
            <div class="details-label">Payment method</div>
            <div class="payment-method">
                <span class="visa-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa" style="height:16px;">
                </span>
                <span><?php echo $cardType; ?> •••• <?php echo $cardLast4; ?> - $<?php echo $amount; ?></span>
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