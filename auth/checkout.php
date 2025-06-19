<?php
session_start();

require_once '../dashboard/config/database.php';
require_once '../dashboard/includes/auth.php';


$userEmail = '';
$userName = '';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header('Location: ./login.php');
    exit;
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT email, first_name, last_name FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if ($user) {
        $userEmail = $user['email'];
        $userName = trim($user['first_name'] . ' ' . $user['last_name']);
    }
} elseif (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
}

// Get plan_id from GET or POST
$plan_id = $_GET['plan_id'] ?? $_POST['plan_id'] ?? 1;
$plan = fetchOne("SELECT * FROM subscription_plans WHERE id = ? AND is_active = 1", [$plan_id]);
if (!$plan) {
    die('Invalid plan selected.');
}
$plan_name = $plan['name'];
$plan_price = $plan['price'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_success'])) {
    // Payment was successful, save subscription
    $user_id = $_SESSION['user_id'];
    $plan_id = $_POST['plan_id'];
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime("+{$plan['duration_months']} months"));
    $payment_method = $_POST['payment_method'] ?? null;
    $transaction_id = $_POST['transaction_id'] ?? null;
    executeQuery("INSERT INTO subscriptions (user_id, plan_id, start_date, end_date, status, payment_method, transaction_id, created_at, updated_at) VALUES (?, ?, ?, ?, 'active', ?, ?, NOW(), NOW())", [$user_id, $plan_id, $start_date, $end_date, $payment_method, $transaction_id]);
    // Redirect or show thank you
    header('Location: thank-you.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['checkout'] = [
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'address' => $_POST['address'],
        'apartment' => $_POST['apartment'],
        'city' => $_POST['city'],
        'postal' => $_POST['postal'],
    ];

    $email = $userEmail;
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal = $_POST['postal'];
    $apartment = $_POST['apartment'];

    $full_address = $address;
    if (!empty($apartment)) {
        $full_address .= "\n" . $apartment;
    }
    $full_address .= "\n" . $city . ', ' . $postal;

    try {
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, address = ? WHERE email = ?");
        if (!$stmt->execute([$first_name, $last_name, $full_address, $email])) {
            error_log("Failed to update user data for email: $email");
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileDesk - Checkout</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <header>
        <div class="left-items">
            <div class="logo">
                <a href="../index.php">
                    <img src="../assets/logo/logo.png" alt="SmileDesk-Logo">
                </a>
            </div>
        </div>
        <!-- <div class="right-items">
            <ul id="menu-list">
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../pricing.php">Tarification</a></li>
                <li><a href="register.php">S'inscrire</a></li>
                <li><button class="button-form">Start Now</button></li>
            </ul>
        </div> -->
    </header>

    <main class="checkout-container">
        <section class="checkout-form">
            <h1>Checkout</h1>
            <form id="checkout-form" action="" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">
                            <p class="form-p">First name</p>
                            <input type="text" id="firstname" name="firstname" class="inputs" value="<?php echo htmlspecialchars($_SESSION['checkout']['firstname'] ?? $_SESSION['name'] ?? ''); ?>">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="lastname">
                            <p class="form-p">Last name</p>
                            <input type="text" id="lastname" name="lastname" class="inputs" value="<?php echo htmlspecialchars($_SESSION['checkout']['lastname'] ?? ''); ?>" required>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">
                        <p class="form-p">Address</p>
                        <input type="text" id="address" name="address" class="inputs" value="<?php echo htmlspecialchars($_SESSION['checkout']['address'] ?? ''); ?>" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="apartment">
                        <p class="form-p">Apartment, suite, etc. (optional)</p>
                        <input type="text" id="apartment" name="apartment" class="inputs" value="<?php echo htmlspecialchars($_SESSION['checkout']['apartment'] ?? ''); ?>">
                    </label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">
                            <p class="form-p">City</p>
                            <input type="text" id="city" name="city" class="inputs" value="<?php echo htmlspecialchars($_SESSION['checkout']['city'] ?? ''); ?>" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="postal">
                            <p class="form-p">Postal code</p>
                            <input type="text" id="postal" name="postal" class="inputs" value="<?php echo htmlspecialchars($_SESSION['checkout']['postal'] ?? ''); ?>" required>
                        </label>
                    </div>
                </div>

                <div id="payment-element" class="form-group"></div>
                <div id="payment-error" style="color:red;margin-top:10px;"></div>
                <button type="submit" class="button-form button-pay">Payez</button>
            </form>
        </section>

        <section class="order-summary">
            <h2>Order summary</h2>
            <div class="order-details">
                <div class="product-info">
                    <div>
                        <h3><?php echo htmlspecialchars($plan_name); ?></h3>
                        <p>One-Time</p>
                    </div>
                    <div class="price"><?php echo number_format($plan_price, 0, '', ' '); ?> MAD</div>
                </div>
                <?php if (isset($_GET['plan_id']) && $_GET['plan_id'] == 1): ?>
                <div class="mb-4">
                    <button class="button-form button-pay">
                        <a href="checkout.php?plan_id=2" style="text-decoration:none; color:#fff;">
                            Choisir le plan Pro
                        </a>
                    </button>

                </div>
                <?php endif; ?>
                <?php if (!isset($_GET['plan_id']) && !isset($_POST['plan_id'])): ?>
                <div class="mb-4">
                    <a href="../pricing.php" class="button-form bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mt-2 inline-block text-center w-full">
                        Choisir un autre plan
                    </a>
                </div>
                <?php endif; ?>

                <div class="discount-section">
                    <input type="text" placeholder="Discount code" class="inputs">
                    <button class="button-apply">Apply</button>
                </div>

                <div class="total-section">
                    <div class="subtotal">
                        <span>Subtotal</span>
                        <span><?php echo number_format($plan_price, 0, '', ' '); ?> MAD</span>
                    </div>
                    <div class="total">
                        <span>Total</span>
                        <span><?php echo number_format($plan_price, 0, '', ' '); ?> MAD</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="main-footer">
        <div class="footer-container">
            <div class="upline">
                <div class="logo">
                    <a href="../index.php">
                        <img src="../assets/logo/logo.png" alt="SmileDesk-Logo">
                    </a>
                </div>
                <div class="icons">
                    <ul id="sc-icons">
                        <li><a href="#"><img src="../assets/icons/linkedin.svg" alt="LinkedIn"></a></li>
                        <li><a href="#"><img src="../assets/icons/instagram.svg" alt="Instagram"></a></li>
                        <li><a href="#"><img src="../assets/icons/x.svg" alt="X.com"></a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="downline">
                <div class="copyright">
                    <p>Droit d'auteur © SmileDesk. Tous droits réservés.</p>
                </div>
                <div class="terms-list">
                    <a href="./index.php">Termes et conditions</a>
                    <a href="./index.php">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
const stripe = Stripe('pk_test_51RYmSVRuYmOMaUOhQP6lYiBcuZE3a45zGjJCn4E5XToF9t7IOwH7t90fmNSNOBRb0D12v7QcBstgeC73zGtcSaQ100T7lhpETh');
let elements;
let paymentElement;

const PLAN_NAME = <?php echo json_encode($plan_name); ?>;
const PLAN_PRICE = <?php echo json_encode($plan_price); ?>;
const USER_EMAIL = <?php echo json_encode($userEmail); ?>;
const USER_NAME = <?php echo json_encode($userName); ?>;
const PLAN_ID = <?php echo json_encode($plan_id); ?>;

// Create payment intent first, then initialize elements
async function createPaymentIntent() {
    const response = await fetch('payment-intent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            billing_details: {
                name: document.getElementById('firstname').value + ' ' + document.getElementById('lastname').value,
                email: USER_EMAIL
            },
            plan_name: PLAN_NAME,
            plan_price: PLAN_PRICE,
            plan_id:PLAN_ID
        })
    });
    
    if (!response.ok) {
        throw new Error('Failed to create payment intent');
    }
    
    return await response.json();
}

async function initializePayment() {
    try {
        if (paymentElement) {
            paymentElement.destroy();
        }
        if (elements) {
            elements.clear();
        }

        // Get the client secret
        const { clientSecret } = await createPaymentIntent();
        
        // Create elements instance
        elements = stripe.elements({
            clientSecret,
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#1e90ff',
                }
            }
        });

        // Create and mount the payment element
        paymentElement = elements.create('payment');
        await paymentElement.mount('#payment-element');
        
        // Clear any existing error messages
        document.getElementById('payment-error').textContent = '';
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('payment-error').textContent = 'Failed to load payment form. Please refresh the page.';
    }
}

// Call initialize on page load
window.addEventListener('load', initializePayment);

// Handle form submission
document.getElementById('checkout-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Processing...';

    try {
        // Save form data
        const formData = new FormData(e.target);
        await fetch('', {
            method: 'POST',
            body: formData
        });

        // Confirm payment
        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: window.location.origin + '/Fil_Rouge_Projet/auth/thank-you.php'
            },
            redirect: "if_required"
        });

        if (error) {
            document.getElementById('payment-error').textContent = error.message;
            submitButton.disabled = false;
            submitButton.textContent = 'Payez';
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            // Save subscription in DB after payment success
            await fetch('payment-intent.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    payment_intent_id: paymentIntent.id,
                    payment_status: paymentIntent.status,
                    plan_id: PLAN_ID,
                    plan_name: PLAN_NAME,
                    plan_price: PLAN_PRICE
                })
            });
            window.location.href = '/Fil_Rouge_Projet/auth/thank-you.php?payment_intent=' + encodeURIComponent(paymentIntent.id);
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('payment-error').textContent = 'An error occurred. Please try again.';
        submitButton.disabled = false;
        submitButton.textContent = 'Pay now';
    }
});
    </script>
</body>

</html>