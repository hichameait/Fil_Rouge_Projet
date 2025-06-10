<?php
require_once '../dashboard/config/database.php';

$erro_name = $erro_email = $error_pass = $error_confirm = "";

if (isset($_POST["signup"])) {

    if (empty($_POST["username"])) {
        $erro_name = "<p style='color:red;'>le nom est obligatoir</p>";
    } else {
        $name = htmlspecialchars($_POST["username"]);
    }

    if (empty($_POST["email"])) {
        $erro_email = "<p style='color:red;'>le email est obligatoir</p>";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $error_pass = "<p style='color:red;'>mot de pass est obligatoir</p>";
    } elseif (strlen($_POST["password"]) < 8) {
        $error_pass = "<p style='color:red;'>le mot de pass au moins 8 caractéres</p>";
    } else {
        $passw = $_POST["password"];
    }

    if ($_POST["password"] !== $_POST["co-password"]) {
        $error_confirm = "<p style='color:red;'>Les mots de passe ne correspondent pas</p>";
    }

    if (empty($erro_name) && empty($erro_email) && empty($error_pass) && empty($error_confirm)) {
        try {
            $hashed = password_hash($passw, PASSWORD_DEFAULT);
            $sql = "INSERT INTO Dentist (nom, email, mot_de_pass) VALUES (:nom, :email, :motpass)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":nom" => $name,
                ":email" => $email,
                ":motpass" => $hashed
            ]);
            header('Location: login.php');
            exit;
        } catch (PDOException $er) {
            echo ("error : $er");
        }
    }
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
                <li><a href="../pricing.php">Tarification</a></li>
                <li><a href="register.php">S'inscrire</a></li>
                <li><button class="button-form">Start Now</button></li>
            </ul>
        </div>
    </header>

    <section class="forms">
        <form action="" method="post" class="formulaire">
            <h1 class="form-h1">Commencer</h1>
            <?php
                echo $erro_name;
                echo $erro_email;
                echo $error_pass;
                echo $error_confirm;
            ?>
            <label for="email">
                <p class="form-p">Email</p>
                <input type="email" id="email" name="email" class="inputs" required>
            </label>
            <label for="username">
                <p class="form-p">Nom d'utilisateur</p>
                <input type="text" id="username" name="username" class="inputs" required>
            </label>
            <label for="password">
                <p class="form-p">Mot de passe</p>
                <input type="password" id="password" name="password" class="inputs" required>
            </label>
            <label for="co-password">
                <p class="form-p">Confirmer le Mot de Passe</p>
                <input type="password" id="co-password" name="co-password" class="inputs" required>
            </label>
            <button type="submit" class="button-form" name="signup">Créer un compte</button>
            <a href="#" class="form-p2"> En vous inscrivant, vous acceptez <u>les Conditions d'utilisation</u> et <u>la Politique de confidentialité</u> de DentCare.</a>
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