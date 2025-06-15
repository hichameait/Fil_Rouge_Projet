<?php

session_start();
require_once '../dashboard/api/vendor/autoload.php'; 
require_once '../dashboard/config/database.php';

\Stripe\Stripe::setApiKey('sk_test_51RYmSVRuYmOMaUOhPOG69YgXqQOG9uefxPizc3nC8GVL2FToqNbV94AWR65Jl9WoXAopWqxrdsgn9pyBijAgumZf00Kl4F3TkE');
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $billingDetails = $input['billing_details'] ?? [];
    $planName = $input['plan_name'] ?? 'Plan Essentiel';
    $planid = isset($input['plan_id']) ? intval($input['plan_id']) : 1;
    $planPrice = isset($input['plan_price']) ? floatval($input['plan_price']) : 149;

    // If this is a request to save the subscription after payment succeeded
    if (
        isset($input['payment_intent_id'], $input['payment_status']) &&
        $input['payment_status'] === 'succeeded' &&
        isset($_SESSION['user_id'])
    ) {
        // Get plan by plan_id (not by name)
        $stmt = $pdo->prepare("SELECT id, duration_months FROM subscription_plans WHERE id = ?");
        $stmt->execute([$planid]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($plan) {
            $user_id = $_SESSION['user_id'];
            $plan_id = $plan['id'];
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+{$plan['duration_months']} months"));
            $status = 'active';
            $payment_method = 'stripe';
            $transaction_id = $input['payment_intent_id'];

            // Prevent duplicate subscriptions for the same transaction
            $check = $pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE transaction_id = ?");
            $check->execute([$transaction_id]);
            if ($check->fetchColumn() == 0) {
                $insert = $pdo->prepare("INSERT INTO subscriptions (user_id, plan_id, start_date, end_date, status, payment_method, transaction_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
                $insert->execute([$user_id, $plan_id, $start_date, $end_date, $status, $payment_method, $transaction_id]);
            }
        }
        echo json_encode(['success' => true]);
        exit;
    }

    $amount = intval($planPrice * 100);

    $intent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'mad',
        'automatic_payment_methods' => ['enabled' => true],
        'metadata' => [
            'user_email' => $_SESSION['email'] ?? '',
            'product' => $planName,
            'billing_name' => $billingDetails['name'] ?? '',
            'billing_address' => json_encode($billingDetails['address'] ?? [])
        ]
    ]);
    echo json_encode(['clientSecret' => $intent->client_secret]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}