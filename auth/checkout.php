<?php
session_start();

require_once '../dashboard/config/database.php';
require_once '../dashboard/includes/auth.php';


$userEmail = '';
$userName = '';

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
    $userName = $_SESSION['name'] ?? '';
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
                            <p class="form-p">First name (optional)</p>
                            <input type="text" id="firstname" name="firstname" class="inputs">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="lastname">
                            <p class="form-p">Last name</p>
                            <input type="text" id="lastname" name="lastname" class="inputs" required>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">
                        <p class="form-p">Address</p>
                        <input type="text" id="address" name="address" class="inputs" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="apartment">
                        <p class="form-p">Apartment, suite, etc. (optional)</p>
                        <input type="text" id="apartment" name="apartment" class="inputs">
                    </label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">
                            <p class="form-p">City</p>
                            <input type="text" id="city" name="city" class="inputs" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="postal">
                            <p class="form-p">Postal code</p>
                            <input type="text" id="postal" name="postal" class="inputs" required>
                        </label>
                    </div>
                </div>

                <div id="payment-element" class="form-group"></div>
                <div id="payment-error" style="color:red;margin-top:10px;"></div>
                <button type="submit" class="button-form button-pay">Pay now</button>
            </form>
        </section>

        <section class="order-summary">
            <h2>Order summary</h2>
            <div class="order-details">
                <div class="product-info">
                    <div>
                        <h3>Starter Plan</h3>
                        <p>One-Time</p>
                    </div>
                    <div class="price">500 MAD</div>
                </div>

                <div class="discount-section">
                    <input type="text" placeholder="Discount code" class="inputs">
                    <button class="button-apply">Apply</button>
                </div>

                <div class="total-section">
                    <div class="subtotal">
                        <span>Subtotal</span>
                        <span>500 MAD</span>
                    </div>
                    <div class="total">
                        <span>Total</span>
                        <span>500 MAD</span>
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

fetch('create-payment-intent.php', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
        elements = stripe.elements({ clientSecret: data.clientSecret });
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');
    });

document.getElementById('checkout-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    // Get address fields from form
    const billingDetails = {
        name: USER_NAME,
        email: USER_EMAIL,
        address: {
            line1: document.getElementById('address').value,
            line2: document.getElementById('apartment').value,
            city: document.getElementById('city').value,
            postal_code: document.getElementById('postal').value,
        }
    };

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: window.location.origin + '/Fil_Rouge_Projet/auth/thank-you.php',
            payment_method_data: {
                billing_details: billingDetails
            }
        },
    });
    if (error) {
        document.getElementById('payment-error').textContent = error.message;
    }
});

    const USER_EMAIL = <?php echo json_encode($userEmail); ?>;
    const USER_NAME = <?php echo json_encode($userName); ?>;
</script>
</body>

</html>