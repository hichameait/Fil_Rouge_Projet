<?php

session_start();
require_once '../dashboard/api/vendor/autoload.php'; 
require_once '../dashboard/config/database.php';


\Stripe\Stripe::setApiKey('sk_test_51RYmSVRuYmOMaUOhPOG69YgXqQOG9uefxPizc3nC8GVL2FToqNbV94AWR65Jl9WoXAopWqxrdsgn9pyBijAgumZf00Kl4F3TkE');
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $billingDetails = $input['billing_details'] ?? [];
    $planName = $input['plan_name'] ?? 'Starter Plan';
    $planPrice = isset($input['plan_price']) ? floatval($input['plan_price']) : 149;

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