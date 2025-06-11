<?php

require_once '../vendor/autoload.php'; 

\Stripe\Stripe::setApiKey('sk_test_51RYmSVRuYmOMaUOhPOG69YgXqQOG9uefxPizc3nC8GVL2FToqNbV94AWR65Jl9WoXAopWqxrdsgn9pyBijAgumZf00Kl4F3TkE');
header('Content-Type: application/json');

try {
    $amount = 50000; 
    $intent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'mad',
        'automatic_payment_methods' => ['enabled' => true],
    ]);
    echo json_encode(['clientSecret' => $intent->client_secret]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}