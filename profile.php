<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr. Houda Bel Houssine | SmileDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0284c7;
            --primary-dark: #0369a1;
            --primary-light: #7dd3fc;
            --secondary-color: #06b6d4;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --border-color: #e5e7eb;
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--bg-light);
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header Styles */
        header {
            background-color: var(--bg-white);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .nav-links {
            display: none;
        }

        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary-color);
            background-color: rgba(2, 132, 199, 0.05);
        }

        .menu-button {
            background-color: transparent;
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,100 1000,0 1000,100"/></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .hero-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .hero-info h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .specialty {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            transition: var(--transition);
        }

        .badge:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .stars {
            color: #fbbf24;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .contact-info div {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-info i {
            font-size: 0.875rem;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: white;
            color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        .profile-image {
            display: flex;
            justify-content: center;
        }

        .profile-image img {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            border: 6px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .profile-image img:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Main Content */
        main {
            padding: 3rem 0;
        }

        section {
            margin-bottom: 3rem;
        }

        section h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            position: relative;
        }

        section h2::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .card {
            background-color: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-content {
            padding: 1.5rem;
        }

        .card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .card p {
            color: var(--text-gray);
            margin-bottom: 1rem;
        }

        /* Gallery */
        .gallery {
            position: relative;
            height: 300px;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .gallery-container {
            height: 100%;
            width: 100%;
            position: relative;
        }

        .gallery-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .gallery-slide.active {
            opacity: 1;
        }

        .gallery-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-nav {
            position: absolute;
            bottom: 1rem;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            z-index: 10;
        }

        .gallery-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: var(--transition);
        }

        .gallery-dot.active {
            background-color: white;
        }

        .gallery-prev, .gallery-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.8);
            color: var(--text-dark);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: var(--transition);
            border: none;
        }

        .gallery-prev {
            left: 1rem;
        }

        .gallery-next {
            right: 1rem;
        }

        .gallery-prev:hover, .gallery-next:hover {
            background-color: white;
        }

        /* Services */
        .services-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .service-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: var(--bg-light);
            color: var(--text-dark);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: var(--transition);
        }

        .service-badge:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border-color: var(--primary-light);
        }

        /* Prices */
        .price-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .price-item:last-child {
            border-bottom: none;
        }

        .price-name {
            font-weight: 500;
        }

        .price-value {
            font-weight: 700;
        }

        /* Diplomas */
        .diploma-section {
            margin-bottom: 1.5rem;
        }

        .diploma-section h3 {
            color: var(--primary-color);
            font-size: 1.125rem;
            margin-bottom: 0.75rem;
        }

        .diploma-list {
            list-style: none;
        }

        .diploma-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .diploma-item i {
            position: absolute;
            left: 0;
            top: 0.25rem;
            color: var(--primary-color);
        }

        /* Languages */
        .languages-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .language-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .language-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .language-level {
            font-size: 0.875rem;
            color: var(--text-gray);
        }

        /* Map */
        .map-container {
            height: 400px;
            background-color: #f1f5f9;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Footer */
        footer {
            background-color: #1f2937;
            color: white;
            padding: 3rem 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-section h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-section p {
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-contact div {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #9ca3af;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
        }

        .app-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .app-button {
            display: inline-block;
            padding: 0.75rem 1rem;
            border: 1px solid white;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            text-align: center;
            transition: var(--transition);
        }

        .app-button:hover {
            background-color: white;
            color: #1f2937;
        }

        .footer-bottom {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #374151;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }

        .footer-bottom p {
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .footer-legal {
            display: flex;
            gap: 1rem;
        }

        .footer-legal a {
            color: #9ca3af;
            font-size: 0.875rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-legal a:hover {
            color: white;
        }

        /* Responsive Styles */
        @media (min-width: 768px) {
            .container {
                padding: 0 1.5rem;
            }

            .nav-links {
                display: flex;
                gap: 1.5rem;
            }

            .menu-button {
                display: none;
            }

            .hero {
                padding: 5rem 0;
            }

            .hero-content {
                grid-template-columns: 1fr 1fr;
                align-items: center;
            }

            .profile-image {
                justify-content: flex-end;
            }

            .profile-image img {
                width: 300px;
                height: 300px;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-bottom {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        @media (min-width: 1024px) {
            .gallery {
                height: 400px;
            }

            .footer-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Enhanced Professional Touches */
        .section-header {
            position: relative;
            padding-left: 1rem;
            margin-left: -1rem;
        }

        .section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.25rem;
            bottom: 0.25rem;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .highlight {
            color: var(--primary-color);
            font-weight: 600;
        }

        .card {
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: var(--transition);
        }

        .card:hover::before {
            opacity: 1;
        }

        .scroll-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: var(--transition);
            z-index: 99;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .scroll-to-top.visible {
            opacity: 1;
        }

        .scroll-to-top:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <a href="/" class="logo">SmileDesk</a>
                <div class="nav-links">
                    <a href="#">Nos Dentistes</a>
                    <a href="#">Je suis patient</a>
                    <a href="#">Je suis dentiste</a>
                    <a href="#">FR</a>
                </div>
                <button class="menu-button">Menu</button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-info animate-fade-in">
                    <h1>Dr. Houda Bel Houssine</h1>
                    <div>
                        <span class="badge">Chirurgien-dentiste</span>
                        <span class="badge">Orthodontiste</span>
                    </div>
                    <div class="rating">
                        <span class="stars">★★★★★</span>
                        <span>(4.9 - 120 avis)</span>
                    </div>
                    <div class="contact-info">
                        <div>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Avenue des Champs-Élysées, 75008 Paris</span>
                        </div>
                        <div>
                            <i class="fas fa-phone"></i>
                            <span>+33 1 23 45 67 89</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope"></i>
                            <span>dr.houda@smiledesk.com</span>
                        </div>
                        <div>
                            <i class="fas fa-globe"></i>
                            <span>Français, Anglais, Arabe</span>
                        </div>
                    </div>
                    <a href="#" class="cta-button">
                        <i class="far fa-calendar-alt"></i>
                        Prendre Rendez-vous
                    </a>
                </div>
                <div class="profile-image animate-fade-in delay-200">
                    <img src="https://via.placeholder.com/300" alt="Dr. Houda Bel Houssine">
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
                    <p>Dr. Houda Bel Houssine est une <span class="highlight">chirurgienne-dentiste expérimentée</span> avec plus de 15 ans de pratique en dentisterie générale et esthétique. Elle a obtenu son diplôme à l'Université de Médecine et de Pharmacie avec mention et a suivi une formation avancée en dentisterie esthétique.</p>
                    <p>Spécialisée en orthodontie et en implantologie, Dr. Houda offre une gamme complète de soins dentaires pour toute la famille, des examens de routine aux traitements complexes, toujours avec une approche douce et attentionnée.</p>
                </div>
            </div>

            <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Galerie</h3>
            <div class="gallery card">
                <div class="gallery-container">
                    <div class="gallery-slide active">
                        <img src="https://via.placeholder.com/800x400?text=Cabinet+Dentaire" alt="Cabinet dentaire">
                    </div>
                    <div class="gallery-slide">
                        <img src="https://via.placeholder.com/800x400?text=Salle+de+Traitement" alt="Salle de traitement">
                    </div>
                    <div class="gallery-slide">
                        <img src="https://via.placeholder.com/800x400?text=Équipement+Moderne" alt="Équipement moderne">
                    </div>
                    <div class="gallery-slide">
                        <img src="https://via.placeholder.com/800x400?text=Salle+d'Attente" alt="Salle d'attente">
                    </div>
                </div>
                <button class="gallery-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="gallery-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="gallery-nav">
                    <div class="gallery-dot active" data-index="0"></div>
                    <div class="gallery-dot" data-index="1"></div>
                    <div class="gallery-dot" data-index="2"></div>
                    <div class="gallery-dot" data-index="3"></div>
                </div>
            </div>
        </section>

        <!-- Soins et actes -->
        <section id="services" class="animate-fade-in delay-400">
            <h2 class="section-header">Soins et actes</h2>
            <div class="card">
                <div class="card-content">
                    <div class="services-grid">
                        <span class="service-badge">Détartrage</span>
                        <span class="service-badge">Extraction</span>
                        <span class="service-badge">Orthodontie</span>
                        <span class="service-badge">Implantologie</span>
                        <span class="service-badge">Blanchiment</span>
                        <span class="service-badge">Prothèses dentaires</span>
                        <span class="service-badge">Parodontologie</span>
                        <span class="service-badge">Endodontie</span>
                        <span class="service-badge">Pédodontie</span>
                        <span class="service-badge">Facettes</span>
                        <span class="service-badge">Couronnes</span>
                        <span class="service-badge">Bridges</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tarifs -->
        <section id="prices">
            <h2 class="section-header">Tarifs</h2>
            <div class="card">
                <div class="card-content">
                    <div class="price-list">
                        <div class="price-item">
                            <span class="price-name">Consultation initiale</span>
                            <span class="price-value">50€</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name">Détartrage</span>
                            <span class="price-value">70€</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name">Radiographie panoramique</span>
                            <span class="price-value">60€</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name">Consultation orthodontie</span>
                            <span class="price-value">80€</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name">Blanchiment dentaire</span>
                            <span class="price-value">350€</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Diplômes -->
        <section id="diplomas">
            <h2 class="section-header">Diplômes & Certifications</h2>
            <div class="card">
                <div class="card-content">
                    <div class="diploma-section">
                        <h3>Formation</h3>
                        <ul class="diploma-list">
                            <li class="diploma-item">
                                <i class="fas fa-chevron-right"></i>
                                <span>Docteur en Chirurgie Dentaire - Université de Médecine et Pharmacie</span>
                            </li>
                            <li class="diploma-item">
                                <i class="fas fa-chevron-right"></i>
                                <span>Spécialisation en Orthodontie - Académie Européenne d'Orthodontie</span>
                            </li>
                        </ul>
                    </div>

                    <div class="diploma-section">
                        <h3>Certifications</h3>
                        <ul class="diploma-list">
                            <li class="diploma-item">
                                <i class="fas fa-chevron-right"></i>
                                <span>Certification Invisalign (Depuis 2015)</span>
                            </li>
                            <li class="diploma-item">
                                <i class="fas fa-chevron-right"></i>
                                <span>Certification en Dentisterie Laser (Depuis 2018)</span>
                            </li>
                        </ul>
                    </div>

                    <div class="diploma-section">
                        <h3>Expérience</h3>
                        <ul class="diploma-list">
                            <li class="diploma-item">
                                <i class="fas fa-chevron-right"></i>
                                <span>Chef de Service - Clinique Dentaire Saint-Louis (2018-2022)</span>
                            </li>
                            <li class="diploma-item">
                                <i class="fas fa-chevron-right"></i>
                                <span>Chirurgien-Dentiste - Centre Médical International (2010-2018)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Langues Parlées -->
        <section id="languages">
            <h2 class="section-header">Langues Parlées</h2>
            <div class="card">
                <div class="card-content">
                    <div class="languages-container">
                        <div class="language-item">
                            <div class="language-badge">Français</div>
                            <span class="language-level">Natif</span>
                        </div>
                        <div class="language-item">
                            <div class="language-badge">Anglais</div>
                            <span class="language-level">Courant</span>
                        </div>
                        <div class="language-item">
                            <div class="language-badge">Arabe</div>
                            <span class="language-level">Natif</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Carte -->
        <section id="map">
            <h2 class="section-header">Carte</h2>
            <div class="card">
                <div class="card-content">
                    <div class="map-container" id="map-container">
                        <div>
                            <h3>Carte interactive</h3>
                            <p style="color: var(--text-gray);">123 Avenue des Champs-Élysées, 75008 Paris</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>SmileDesk</h3>
                    <p>Plateforme de réservation de rendez-vous dentaires en ligne. Trouvez le dentiste idéal près de chez vous et prenez rendez-vous en quelques clics.</p>
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
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script>
        // Gallery Functionality
        document.addEventListener('DOMContentLoaded', function() {
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
    </script>
</body>
</html>