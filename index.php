<?php
// Load subscription plans from DB
require_once __DIR__ . '/dashboard/config/database.php';
$plans = fetchAll("SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY price ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileDesk</title>
    <link rel="stylesheet" href="./assets/css/v2.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="./assets/logo/logo.png" alt="SmileDesk Logo">
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <style>
            #list-menu li a {
                text-decoration: none;
                color: var(--eerie-black);
                transition: color 0.2s;
            }   
        </style>
        <div class="list">
            <ul id="list-menu">
                <li><a href="./guide.php">Guide</a></li>
                <li><a href="#tarifa">Tarification</a></li>
                <li><a href="./auth/login.php">Se connecter</a></li>
                <li><a href="./auth/register.php"><button id="nav-btn">Start Now</button></a></li>
            </ul>
        </div>
    </header>
    <main>
        <div class="hero">
            <p class="header_para">
                La gestion dentaire moderne commence ici <img
                    src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-3.svg"
                    alt="" width="20px" height="20px">
            </p>
            <h1 id="hero-title">Une solution digitale complète
                pour les dentistes marocains</h1>
            <p id="hero-subtitle">Rendez-vous, paiements, patients et documents…
                Tout en un seul outil 100% digital.</p>
            <div class="btn-groups">
                <button id="nav-btn">Essai Gratuit 7 Jours</button>
                <button id="btn-prix">Prix <img
                        src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-3.svg"
                        alt="" width="20px" height="20px"></button>
            </div>
            <p id="subtitle-small">Votre cabinet, votre rythme — notre technologie</p>
            <br>
            <hr id="dev_line">
            <img class="img-dash" src="./assets/dash.svg" alt="" width="90%">
        </div>
        <section class="main-2">
            <div class="head-sc2">
                <h1>Optimisez votre temps, <span>développez</span> votre activité</h1>
                <p>Ne perdez plus d'heures sur des tâches répétitives.
                    Confiez la gestion à notre plateforme intelligente.</p>
            </div>
            <div class="sc2-container">
                <div class="payment-section">
                    <div class="payment-header">
                        <div class="icon-title">
                            <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-46.svg"
                                alt="Payment Icon" class="payment-icon">
                            <h2>SUIVI AUTOMATIQUE DES PAIEMENTS</h2>
                        </div>
                        <p class="payment-description">
                            Chaque transaction est automatiquement suivie, classée par statut, et synchronisée avec
                            votre dashboard en temps réel.
                        </p>
                    </div>

                    <div class="payment-list">
                        <div class="payment-item">
                            <span class="date">hier</span>
                            <span class="name">Adam Bouzine</span>
                            <div class="status received">reçues</div>
                            <span class="amount positive">+250 MAD</span>
                        </div>

                        <div class="payment-item">
                            <span class="date">hier</span>
                            <span class="name">Amine Aboumacha</span>
                            <div class="status received">reçues</div>
                            <span class="amount positive">+500 MAD</span>
                        </div>

                        <div class="payment-item">
                            <span class="date">aujourd'hui</span>
                            <span class="name">Mostafa Bakhti</span>
                            <div class="status pending">en attente</div>
                            <span class="amount">150 MAD</span>
                        </div>

                        <div class="payment-item">
                            <span class="date">aujourd'hui</span>
                            <span class="name">Saad Talhi</span>
                            <div class="status pending">en attente</div>
                            <span class="amount">450 MAD</span>
                        </div>

                        <div class="payment-item">
                            <span class="date">aujourd'hui</span>
                            <span class="name">Mohammad Elcadi</span>
                            <div class="status received">reçues</div>
                            <span class="amount positive">500 MAD</span>
                        </div>
                    </div>
                </div>
                <div class="comment-1">
                    <p>“Avant, je gérais tout avec des
                        fichiers Excel et de l’aide comptable, mais c’était lent et compliqué. Maintenant, cette
                        plateforme me fait gagner un temps fou en automatisant mes rendez-vous et mes
                        paiements.”</p>
                    <div class="icon-autor">
                        <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/image@2x.png"
                            alt="" width="60px" height="60px">
                        <div class="hd-icons">
                            <h5>Mehdi Azroual</h5>
                            <p>Dentist Errachidia</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-3">
            <div class="mine3-container">
                <div class="payment-container">
                    <div class="payments-list">
                        <div class="payment-row">
                            <div class="user-info">
                                <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-10@2x.png"
                                    alt="Hichame" class="avatar">
                                <div class="user-details">
                                    <span class="name">Hichame</span>
                                    <span class="time">4:17PM</span>
                                </div>
                            </div>
                            <span class="amount positive">+200 MAD</span>
                        </div>

                        <div class="payment-row">
                            <div class="user-info">
                                <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-11@2x.png"
                                    alt="Ahlame" class="avatar">
                                <div class="user-details">
                                    <span class="name">Ahlame</span>
                                    <span class="time">4:17PM</span>
                                </div>
                            </div>
                            <span class="amount positive">+200 MAD</span>
                        </div>
                    </div>
                    <div class="section-header">
                        <div class="icon-title">
                            <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-40.svg"
                                alt="Sync Icon" class="sync-icon">
                            <h2>PAIEMENTS SYNCHRONISÉS SANS EFFORT</h2>
                        </div>
                        <p class="section-description">
                            Chaque paiement reçu est automatiquement identifié et associé à la bonne facture
                            plus besoin de vérifier manuellement.
                        </p>
                    </div>
                </div>
                <div class="invoice-container">
                    <div class="invoices-list">
                        <div class="invoice-item">
                            <div class="invoice-status">
                                <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-41.svg"
                                    alt="Check Icon" class="check-icon">
                                <span>Facture du Hicham</span>
                            </div>
                            <span class="status-badge sent">envoyé</span>
                        </div>

                        <div class="invoice-item">
                            <div class="invoice-status">
                                <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-41.svg"
                                    alt="Check Icon" class="check-icon">
                                <span>Facture du Adam</span>
                            </div>
                            <span class="status-badge sent">envoyé</span>
                        </div>

                        <div class="invoice-item">
                            <div class="invoice-status">
                                <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-41.svg"
                                    alt="Check Icon" class="check-icon">
                                <span>Facture du Ahlam</span>
                            </div>
                            <span class="status-badge sent">envoyé</span>
                        </div>
                    </div>
                    <div class="section-header">
                        <div class="icon-title">
                            <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-45.svg"
                                alt="Invoice Icon" class="invoice-icon">
                            <h2>FACTURATION AUTOMATISÉE, SANS OUBLIS</h2>
                        </div>
                        <p class="section-description">
                            Créez, envoyez et suivez toutes vos factures en un clic.
                            DentalCare s'occupe du reste, même les relances.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-4">
            <div class="header-sc4">
                <h1>Ne ratez plus aucun <span>rendez-vous</span></h1>
                <p>Des rappels automatiques par WhatsApp, SMS et Email sont envoyés à vos patients
                    avant chaque
                    rendez-vous — pour éviter les absences et garder le contrôle.</p>
            </div>
        </section>
        <section class="main-5">
            <div class="remainder-co">
                <div class="image-left">
                    <img src="./assets/rpl-msg.svg" alt="">
                </div>
                <div class="image-right">
                    <img src="./assets/rpl-email.svg" alt="">
                </div>
            </div>
            <div class="comment-1">
                <p>“Avant, je gérais tout avec des
                    fichiers Excel et de l’aide comptable, mais c’était lent et compliqué. Maintenant, cette
                    plateforme me fait gagner un temps fou en automatisant mes rendez-vous et mes
                    paiements.”</p>
                <div class="icon-autor">
                    <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/image@2x.png"
                        alt="" width="60px" height="60px">
                    <div class="hd-icons">
                        <h5>Mehdi Azroual</h5>
                        <p>Dentist Errachidia</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-6">
            <div class="head-m6">
                <h1>Pensé pour <span>les dentistes</span></h1>
                <p id="m6-subt">SmileDesk a été conçu pour les professionnels du dentaire, pas pour les comptables.
                    Plus de paperasse, plus de stress, plus d’outils compliqués.
                    Juste une plateforme simple, rapide et efficace qui facilite votre quotidien.</p>
                <div class="starts">
                    <img src="https://cdn.animaapp.com/projects/683cb56bcb1fa081be2f8b2c/releases/683cb689aa63b0519c365b09/img/component-1-15.svg"
                        alt="" width="30px" height="30px">
                    <p id="p1">4.8</p>
                    <p id="p2">from 500+ founders</p>
                </div>
            </div>
            <div class="tarifa" id="tarifa">
                <div class="head-tarifa">
                    <h1>Tarification</h1>
                    <p>Évolue selon vos besoins, pas selon vos dépenses.</p>
                </div>
                <div class="plans">
                    <?php foreach ($plans as $plan): ?>
                        <div class="plan-box" style="margin-bottom:2rem;display:inline-block;vertical-align:top;width:340px;max-width:100%;background:#fff;border-radius:16px;box-shadow:0 2px 8px #0001;padding:24px 20px 20px 20px;margin-right:24px;">
                            <div class="plan-header">
                                <h3><?= htmlspecialchars($plan['name']) ?></h3>
                                <p><?= htmlspecialchars($plan['description']) ?></p>
                                <div class="price">
                                    <h2><?= number_format($plan['price'], 2) ?> MAD</h2>
                                    <span>/ <?= $plan['duration_months'] > 1 ? $plan['duration_months'] . ' mois' : 'mois' ?></span>
                                </div>
                            </div>
                            <div class="features">
                                <?php $features = json_decode($plan['features'], true); if ($features): ?>
                                    <?php foreach ($features as $feature): ?>
                                        <div class="feature-item">
                                            <img src="./assets/check-blue.svg" alt="check">
                                            <span><?= htmlspecialchars($feature) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button class="plan-btn" onclick="window.location.href='./auth/register.php?plan_id=<?= $plan['id'] ?>'">
                                <?= $plan['id'] == 1 ? 'Essai gratuit 7 jours' : 'Essayer le ' . htmlspecialchars($plan['name']) ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="faqs">
            <h2>Questions fréquemment posées</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Est-ce que je dois installer un logiciel sur mon ordinateur</span>
                        <img src="./assets/down.svg" alt="toggle" class="toggle-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Non, SmileDesk est une application web - il suffit d'avoir un navigateur internet et une
                            connexion pour y accéder depuis n'importe quel appareil.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Est-ce que je peux tester SmileDesk gratuitement ?</span>
                        <img src="./assets/down.svg" alt="toggle" class="toggle-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Oui, vous pouvez essayer toutes les fonctionnalités pendant 7 jours sans engagement.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Puis-je envoyer des factures à mes patients ?</span>
                        <img src="./assets/down.svg" alt="toggle" class="toggle-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Oui, vous pouvez créer et envoyer des factures professionnelles en quelques clics.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Est-ce que les patients reçoivent des rappels avant leur rendez-vous ?</span>
                        <img src="./assets/down.svg" alt="toggle" class="toggle-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Oui, des rappels automatiques sont envoyés par SMS, WhatsApp et email.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Y a-t-il un tableau de bord pour suivre mon activité ?</span>
                        <img src="./assets/down.svg" alt="toggle" class="toggle-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Oui, vous avez accès à un tableau de bord complet avec des statistiques détaillées.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Mes données et celles de mes patients sont-elles sécurisées ?</span>
                        <img src="./assets/down.svg" alt="toggle" class="toggle-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Oui, toutes les données sont cryptées et stockées de manière sécurisée.</p>
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
                        <img src="./assets/logo/logo.png" alt="SmileDesk-Logo">
                    </a>
                </div>
                <div class="icons">
                    <ul id="sc-icons">
                        <li><a href="#"><img src="https://a-us.storyblok.com/f/1023015/16x16/4f2af5d116/linkedin.svg"
                                    alt="LinkedIn"></a></li>
                        <li><a href="#"><img src="https://www.svgrepo.com/show/452229/instagram-1.svg" alt="Instagram"
                                    width="16px" height="16px"></a></li>
                        <li><a href="#"><img src="https://a-us.storyblok.com/f/1023015/16x16/0001a8fb35/x.svg"
                                    alt="X.com"></a></li>
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
    <script src="./assets/js/main.js"></script>
</body>

</html>