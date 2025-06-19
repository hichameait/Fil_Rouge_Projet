<?php
require_once './dashboard/config/database.php';


if (isset($_GET['id'])) {
    $dentist_id = $_GET['id'];
    // var_dump($dentist_id);
    // die();
} elseif (isset($_GET['username'])) {
    $username = $_GET['username'];
}

$stmt = $pdo->prepare("SELECT u.*, s.* FROM users u LEFT JOIN settings s ON u.id = s.user_id WHERE u.id = ? AND u.role = 'dentist'");
$stmt->execute([$dentist_id]);
$dentist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dentist) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <title>Dentiste introuvable | SmileDesk</title>
        <link rel="stylesheet" href="./assets/css/profile.css">
    </head>

    <body>
        <main class="container" style="margin-top: 5rem;">
            <section class="card animate-fade-in" style="text-align:center; padding:3rem;">
                <h1 style="font-size:3rem; color:#0284c7;">404</h1>
                <p style="font-size:1.5rem; color:#6b7280;">Dentiste ou cabinet non trouvé.</p>
                <a href="/" class="cta-button" style="margin-top:2rem;">Retour à l'accueil</a>
            </section>
        </main>
    </body>

    </html>
    <?php
    exit;
}

$services = [];
$prices = [];
$service_stmt = $pdo->prepare("
    SELECT bs.name, dsp.price
    FROM dentist_service_prices dsp
    JOIN base_services bs ON dsp.base_service_id = bs.id
    WHERE dsp.user_id = ?
    ORDER BY bs.name
");
$service_stmt->execute([$dentist_id]);
while ($row = $service_stmt->fetch(PDO::FETCH_ASSOC)) {
    $services[] = $row['name'];
    $prices[] = [
        'name' => $row['name'],
        'price' => $row['price']
    ];
}

$certifications = [];
if (!empty($dentist['certifications'])) {
    $certifications = json_decode($dentist['certifications'], true);
    if (!is_array($certifications))
        $certifications = [];
}
$experience = [];
if (!empty($dentist['experience'])) {
    $experience = json_decode($dentist['experience'], true);
    if (!is_array($experience))
        $experience = [];
}
$langs = [];
if (!empty($dentist['languages_spoken'])) {
    $langs = json_decode($dentist['languages_spoken'], true);
    if (!is_array($langs))
        $langs = [];
}

$profile_img = !empty($dentist['clinic_logo_url']) ? $dentist['clinic_logo_url'] : 'https://png.pngtree.com/png-vector/20240611/ourmid/pngtree-user-profile-icon-image-vector-png-image_12640450.png';

// Fetch dentist's services for the booking modal
$services_options = [];
$service_stmt = $pdo->prepare("
    SELECT bs.id, bs.name
    FROM dentist_service_prices dsp
    JOIN base_services bs ON dsp.base_service_id = bs.id
    WHERE dsp.user_id = ?
    ORDER BY bs.name
");
$service_stmt->execute([$dentist_id]);
while ($row = $service_stmt->fetch(PDO::FETCH_ASSOC)) {
    $services_options[] = $row;
}

// Handle appointment booking
$appointment_success = false;
$appointment_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $patient_name = trim($_POST['patient_name'] ?? '');
    $patient_phone = trim($_POST['patient_phone'] ?? '');
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $service_id = $_POST['service_id'] ?? '';
    $reason = trim($_POST['reason'] ?? '');

    if ($patient_name && $patient_phone && $appointment_date && $appointment_time && $service_id) {
        $patient_id = null;
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE first_name = ? AND phone = ? AND user_id = ?");
        $name_parts = explode(' ', $patient_name, 2);
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
        $stmt->execute([$first_name, $patient_phone, $dentist_id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient) {
            $patient_id = $patient['id'];
        } else {
            try {
                $insert_patient = $pdo->prepare("INSERT INTO patients (user_id, first_name, last_name, phone, created_at) VALUES (?, ?, ?, ?, NOW())");
                if ($insert_patient->execute([$dentist_id, $first_name, $last_name, $patient_phone])) {
                    $patient_id = $pdo->lastInsertId();
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $appointment_error = "Une erreur technique est survenue. Veuillez réessayer plus tard.";
            }
        }
        if ($patient_id) {
            $insert_appointment = $pdo->prepare("INSERT INTO appointments (user_id, dentist_id, patient_id, appointment_date, appointment_time, base_service_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'scheduled', NOW())");
            if ($insert_appointment->execute([$dentist_id, $dentist_id, $patient_id, $appointment_date, $appointment_time, $service_id])) {
                $appointment_success = true;
            } else {
                $appointment_error = "Erreur lors de la prise de rendez-vous. Veuillez réessayer.";
            }
        } else {
            $appointment_error = "Erreur lors de l'enregistrement du patient.";
        }

    } else {
        $appointment_error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($dentist['first_name'] . ' ' . $dentist['last_name']) ?> | SmileDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/profile.css">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="left-items">
            <div class="logo">
                <a href="../index.php">
                    <img src="./assets/logo/logo.png" alt="SmileDesk-Logo">
                </a>
            </div>
        </div>
        <div class="right-items">
            <ul id="menu-list">
                <li><a href="../index.php">Accueil</a></li>
                <li>
                    <a href="#" id="openBookModal"><button class="button-form" type="button">Prendre
                            Rendez-vous</button></a>
                </li>
            </ul>
        </div>
    </header>

    <!-- Modale Prendre Rendez-vous -->
    <div id="bookModal" class="modal"
        style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div
            style="background:#fff; border-radius:8px; max-width:400px; width:100%; margin:auto; padding:2rem; position:relative;">
            <button id="closeBookModal"
                style="position:absolute; top:1rem; right:1rem; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <h2 style="font-size:1.5rem; margin-bottom:1rem;">Prendre Rendez-vous</h2>
            <?php if ($appointment_success): ?>
                <div style="color:green; margin-bottom:1rem;">Votre rendez-vous a été enregistré avec succès !</div>
            <?php elseif ($appointment_error): ?>
                <div style="color:red; margin-bottom:1rem;"><?= htmlspecialchars($appointment_error) ?></div>
            <?php endif; ?>
            <form method="post" id="bookAppointmentForm" autocomplete="off">
                <input type="hidden" name="book_appointment" value="1">
                <div style="margin-bottom:1rem;">
                    <label for="patient_name" style="display:block; margin-bottom:0.5rem;">Nom complet *</label>
                    <input type="text" name="patient_name" id="patient_name" required class="input"
                        style="width:100%; padding:0.5rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="patient_phone" style="display:block; margin-bottom:0.5rem;">Téléphone *</label>
                    <input type="tel" name="patient_phone" id="patient_phone" required class="input"
                        style="width:100%; padding:0.5rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="appointment_date" style="display:block; margin-bottom:0.5rem;">Date *</label>
                    <input type="date" name="appointment_date" id="appointment_date" required class="input"
                        style="width:100%; padding:0.5rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="appointment_time" style="display:block; margin-bottom:0.5rem;">Heure *</label>
                    <input type="time" name="appointment_time" id="appointment_time" required class="input"
                        style="width:100%; padding:0.5rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="service_id" style="display:block; margin-bottom:0.5rem;">Service *</label>
                    <select name="service_id" id="service_id" required class="input"
                        style="width:100%; padding:0.5rem;">
                        <option value="">Sélectionner un service</option>
                        <?php foreach ($services_options as $service): ?>
                            <option value="<?= htmlspecialchars($service['id']) ?>">
                                <?= htmlspecialchars($service['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="reason" style="display:block; margin-bottom:0.5rem;">Motif du rendez-vous</label>
                    <textarea name="reason" id="reason" rows="2" class="input" style="width:100%; padding:0.5rem;"
                        placeholder="(Optionnel)"></textarea>
                </div>
                <button type="submit" class="cta-button" style="width:100%;">Envoyer la demande</button>
            </form>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero" style="margin-top: 6rem;">
        <div class="container">
            <div class="hero-content">
                <div class="hero-info animate-fade-in">
                    <h1><?= htmlspecialchars($dentist['first_name'] . ' ' . $dentist['last_name']) ?></h1>
                    <div class="rating">
                        <span class="stars">★★★★★</span>
                        <span>(4.9 - 120 avis)</span>
                    </div>
                    <div class="contact-info">
                        <div>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($dentist['clinic_address'] ?: 'Adresse non renseignée') ?></span>
                        </div>
                        <div>
                            <i class="fas fa-phone"></i>
                            <span><?= htmlspecialchars($dentist['clinic_phone'] ?: $dentist['phone']) ?></span>
                        </div>
                        <div>
                            <i class="fas fa-envelope"></i>
                            <span><?= htmlspecialchars($dentist['clinic_email'] ?: $dentist['email']) ?></span>
                        </div>
                    </div>
                    <a href="#" class="cta-button" id="openBookModal2">
                        <i class="far fa-calendar-alt"></i>
                        Prendre Rendez-vous
                    </a>
                </div>
                <div class="profile-image animate-fade-in delay-200">
                    <img src="<?= htmlspecialchars($profile_img) ?>"
                        alt="<?= htmlspecialchars($dentist['first_name'] . ' ' . $dentist['last_name']) ?>">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Informations Générales -->
        <section id="general" class="animate-fade-in delay-300">
            <h2 class="section-header">Informations Générales</h2>
            <div class="card">
                <div class="card-content">
                    <h3>Présentation</h3>
                    <p><?= nl2br(htmlspecialchars($dentist['presentation'] ?: 'Présentation non renseignée.')) ?></p>
                </div>
            </div>
        </section>

        <!-- Soins et actes -->
        <?php if (!empty($services)): ?>
            <section id="services" class="animate-fade-in delay-400">
                <h2 class="section-header">Services</h2>
                <div class="card">
                    <div class="card-content">
                        <div class="services-grid">
                            <?php
                            foreach ($services as $service) {
                                echo '<span class="service-badge">' . htmlspecialchars($service) . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Tarifs -->
        <?php if (!empty($prices)): ?>
            <section id="prices">
                <h2 class="section-header">Tarifs</h2>
                <div class="card">
                    <div class="card-content">
                        <div class="price-list">
                            <?php
                            foreach ($prices as $item) {
                                echo '<div class="price-item"><span class="price-name">' . htmlspecialchars($item['name']) . '</span><span class="price-value">' . htmlspecialchars($item['price']) . ' MAD</span></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Diplômes -->
        <?php if (!empty($certifications)): ?>
            <section id="diplomas">
                <h2 class="section-header">Diplômes & Certifications</h2>
                <div class="card">
                    <div class="card-content">
                        <div class="diploma-section">
                            <h3>Formation</h3>
                            <ul class="diploma-list">
                                <?php
                                foreach ($certifications as $cert) {
                                    echo '<li class="diploma-item"><i class="fas fa-chevron-right"></i> <span>' . htmlspecialchars($cert) . '</span></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Langues Parlées -->
        <?php if (!empty($langs)): ?>
            <section id="languages">
                <h2 class="section-header">Langues Parlées</h2>
                <div class="card">
                    <div class="card-content">
                        <div class="languages-container">
                            <?php foreach ($langs as $lang): ?>
                                <?php if (is_array($lang) && isset($lang['language'])): ?>
                                    <div class="language-item">
                                        <div class="language-badge">
                                            <?= htmlspecialchars($lang['language']) ?>
                                            <?php if (!empty($lang['level'])): ?>
                                                <span style="font-size: 0.9em;">(<?= htmlspecialchars($lang['level']) ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Carte -->
        <section id="map" style="display:none;">
            <h2 class="section-header">Carte</h2>
            <div class="card">
                <div class="card-content">
                    <div class="map-container" id="map-container">
                        <div>
                            <h3>Carte interactive</h3>
                            <p style="color: var(--text-gray);">
                                <?php
                                $address = $dentist['clinic_address'] ?? 'Adresse non renseignée';
                                if (is_array($address)) {
                                    $address = implode(', ', $address);
                                }
                                echo htmlspecialchars($address);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <!-- <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>SmileDesk</h3>
                    <p>Plateforme de réservation de rendez-vous dentaires en ligne. Trouvez le dentiste idéal près de
                        chez vous et prenez rendez-vous en quelques clics.</p>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <div class="footer-contact">
                        <div>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>10 Rue de la Paix, 75002 Paris</span>
                        </div>
                        <div>
                            <i class="fas fa-phone"></i>
                            <span>+33 1 23 45 67 89</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope"></i>
                            <span>contact@smiledesk.com</span>
                        </div>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Liens Utiles</h3>
                    <ul class="footer-links">
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Plan du site</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Téléchargez notre app</h3>
                    <div class="app-buttons">
                        <a href="#" class="app-button">App Store</a>
                        <a href="#" class="app-button">Google Play</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 SmileDesk. Tous droits réservés.</p>
                <div class="footer-legal">
                    <a href="#">Politique de Confidentialité</a>
                    <a href="#">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer> -->

    <!-- Scroll to Top Button -->
    <div class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script>
        // Gallery Functionality
        document.addEventListener('DOMContentLoaded', function () {
            // Gallery
            const slides = document.querySelectorAll('.gallery-slide');
            const dots = document.querySelectorAll('.gallery-dot');
            const prevBtn = document.querySelector('.gallery-prev');
            const nextBtn = document.querySelector('.gallery-next');
            let currentIndex = 0;

            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));

                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentIndex = index;
            }

            prevBtn.addEventListener('click', () => {
                let newIndex = currentIndex - 1;
                if (newIndex < 0) newIndex = slides.length - 1;
                showSlide(newIndex);
            });

            nextBtn.addEventListener('click', () => {
                let newIndex = currentIndex + 1;
                if (newIndex >= slides.length) newIndex = 0;
                showSlide(newIndex);
            });

            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const index = parseInt(dot.getAttribute('data-index'));
                    showSlide(index);
                });
            });

            // Auto-rotate gallery
            setInterval(() => {
                let newIndex = currentIndex + 1;
                if (newIndex >= slides.length) newIndex = 0;
                showSlide(newIndex);
            }, 5000);

            // Scroll to Top
            const scrollToTopBtn = document.getElementById('scrollToTop');

            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.classList.add('visible');
                } else {
                    scrollToTopBtn.classList.remove('visible');
                }
            });

            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Mobile Menu
            const menuButton = document.querySelector('.menu-button');
            const navLinks = document.querySelector('.nav-links');

            menuButton.addEventListener('click', () => {
                if (navLinks.style.display === 'flex') {
                    navLinks.style.display = 'none';
                } else {
                    navLinks.style.display = 'flex';
                    navLinks.style.position = 'absolute';
                    navLinks.style.top = '64px';
                    navLinks.style.left = '0';
                    navLinks.style.right = '0';
                    navLinks.style.flexDirection = 'column';
                    navLinks.style.backgroundColor = 'white';
                    navLinks.style.padding = '1rem';
                    navLinks.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
                    navLinks.style.zIndex = '100';
                }
            });

            // Service badges hover effect
            const serviceBadges = document.querySelectorAll('.service-badge');
            serviceBadges.forEach(badge => {
                badge.addEventListener('click', () => {
                    badge.classList.toggle('active');
                    if (badge.classList.contains('active')) {
                        badge.style.backgroundColor = 'var(--primary-light)';
                        badge.style.color = 'var(--primary-dark)';
                    } else {
                        badge.style.backgroundColor = 'var(--bg-light)';
                        badge.style.color = 'var(--text-dark)';
                    }
                });
            });

            // Map placeholder (in a real implementation, you would initialize a map here)
            const mapContainer = document.getElementById('map-container');
            // This is where you would initialize a Google Map or other map service
        });

        // Modal logic
        function openBookModal() {
            document.getElementById('bookModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeBookModal() {
            document.getElementById('bookModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.getElementById('openBookModal').addEventListener('click', function (e) {
            e.preventDefault();
            openBookModal();
        });
        var openBookModal2 = document.getElementById('openBookModal2');
        if (openBookModal2) {
            openBookModal2.addEventListener('click', function (e) {
                e.preventDefault();
                openBookModal();
            });
        }
        document.getElementById('closeBookModal').addEventListener('click', function () {
            closeBookModal();
        });
        window.addEventListener('click', function (e) {
            if (e.target === document.getElementById('bookModal')) {
                closeBookModal();
            }
        });
        // Optionally, close modal on ESC
        window.addEventListener('keydown', function (e) {
            if (e.key === "Escape") closeBookModal();
        });
    </script>
</body>

</html>