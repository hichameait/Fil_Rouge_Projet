<?php
session_start();

require_once '../dashboard/config/database.php';
require_once '../dashboard/includes/auth.php';

$erro_login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $erro_login = 'Veuillez entrer votre email et votre mot de passe';
    } else {
        if (login($email, $password)) {
            // Debug: log user_status
            error_log('User status after login: ' . ($_SESSION['user_status'] ?? 'NOT SET'));
            // Redirect admin to admin panel
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                header('Location: ../admin/');
                exit();
            }
            if ($_SESSION['user_status'] === 'active') {
                header('Location: ../dashboard/index.php');
                exit();
            } elseif ($_SESSION['user_status'] === 'inactive') { // was 'pending'
                header('Location: checkout.php');
                exit();
            } else {
                $erro_login = 'Votre compte n\'est pas actif (status: ' . htmlspecialchars($_SESSION['user_status'] ?? 'non défini') . ')';
                session_unset();
                // session_destroy(); // Optionally destroy session
            }
        } else {
            $erro_login = 'Email ou mot de passe invalide';
        }
    }
}

// Add debug output temporarily
if (isset($_SESSION)) {
    error_log('Session data: ' . print_r($_SESSION, true));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileDesk - Se Connecter</title>
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
                <li><a href="../index.php#tarifa">Tarification</a></li>
                <li><a href="./register.php"><button class="button-form">S'inscrire</button></a></li>
            </ul>
        </div>
    </header>

    <section class="forms">
        <form action="" method="post" class="formulaire">
            <h1 class="form-h1">Se connecter</h1>
            <?php if ($erro_login): ?>
                <div class="form-error" style="
                    background-color: #ff00001c;
                    padding: 15px;
                    color: red;
                    border: 1px solid #ff000014;
                    border-radius: 8px;
                    text-align: center;
                "><?= htmlspecialchars($erro_login) ?></div>
            <?php endif; ?>
            <label for="email">
                <p class="form-p">Email</p>
                <input type="email" id="email" name="email" class="inputs" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </label>
            <label for="password">
                <p class="form-p">Mot de passe</p>
                <input type="password" id="password" name="password" class="inputs" required>
            </label>
            <button type="submit" class="button-form">Se connecter</button>
            <a href="#" class="form-p forgot-password">Mot de passe oublié?</a>
        </form>
    </section>
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