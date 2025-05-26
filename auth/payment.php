<?php
    session_start();

    include("../config.php");

    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileDesk - Payment</title>
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
            <h1>Payment Details</h1>
            <form action="" method="post">
                <div class="form-group">
                    <label for="cardName">
                        <p class="form-p">Cardholder Name</p>
                        <input type="text" id="cardName" name="cardName" class="inputs" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="cardNumber">
                        <p class="form-p">Card Number</p>
                        <input type="text" id="cardNumber" name="cardNumber" class="inputs" pattern="[0-9]{16}" required placeholder="1234 5678 9012 3456">
                    </label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="expiry">
                            <p class="form-p">Expiry Date</p>
                            <input type="text" id="expiry" name="expiry" class="inputs" placeholder="MM/YY" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="cvv">
                            <p class="form-p">CVV</p>
                            <input type="text" id="cvv" name="cvv" class="inputs" pattern="[0-9]{3,4}" required placeholder="123">
                        </label>
                    </div>
                </div>

                <div class="payment-methods">
                    <p class="form-p">Accepted Payment Methods</p>
                    <div class="payment-icons">
                        <img src="https://staktv.com/wp-content/uploads/2024/01/Credit-Card.svg" alt="Methods-Accepted">
                    </div>
                </div>

                <button type="submit" class="button-form button-pay">Complete Payment</button>
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

                <div class="total-section">
                    <div class="subtotal">
                        <span>Subtotal</span>
                        <span>500 MAD</span>
                    </div>
                    <div class="tax">
                        <span>Tax</span>
                        <span>100 MAD</span>
                    </div>
                    <div class="total">
                        <span>Total to pay</span>
                        <span>600 MAD</span>
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
</body>
</html>