<?php
session_start();

include("../config.php");
$erro_login = "";

if (isset($_POST["login"])) {
    if (!empty($_POST["email"] || !empty($_POST["password"]))) {

        $email = htmlspecialchars($_POST['email']);
        $passw = htmlspecialchars($_POST['password']);

        $sql = "SELECT mot_de_pass FROM utilisateurs WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($passw, $result['mot_de_pass'])) {

            $sql = "SELECT * FROM utilisateurs WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $res['id'];
            $_SESSION['name'] = $res['nom'];

            header("location: index.php");

            exit();
        } else {
            $erro_login = "Email ou mot de passe incorrects";
        }
    } else {
        $erro_login = "Email ou mot de pass incorrects";
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
            <h1 class="form-h1">Se connecter</h1>
            <label for="email">
                <p class="form-p">Email</p>
                <input type="email" id="email" name="email" class="inputs" required>
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