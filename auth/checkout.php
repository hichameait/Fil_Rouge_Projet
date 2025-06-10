<?php
session_start();

require_once '../dashboard/config/database.php';
require_once '../dashboard/includes/auth.php';

if (isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileDesk - Checkout</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
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
        <div class="right-items">
            <ul id="menu-list">
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../pricing.php">Tarification</a></li>
                <li><a href="register.php">S'inscrire</a></li>
                <li><button class="button-form">Start Now</button></li>
            </ul>
        </div>
    </header>

    <main class="checkout-container">
        <section class="checkout-form">
            <h1>Checkout</h1>
            <form action="" method="post">
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

                <button type="submit" class="button-form button-pay">Pay now</button>
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
</body>

</html>