<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'DentalCare'; ?></title>
    <meta name="description" content="Professional dental services for the whole family. We offer general, cosmetic, and emergency dental care.">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico">
</head>
<body class="font-sans antialiased">
    <header class="sticky top-0 z-50 w-full border-b bg-white bg-opacity-95 backdrop-blur">
        <div class="container mx-auto flex h-16 items-center justify-between">
            <div class="flex items-center gap-2">
                <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="index.php" class="flex items-center space-x-2">
                    <span class="font-bold text-xl text-blue-600">DentalCare</span>
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-6">
                <a href="index.php" class="text-sm font-medium transition-colors hover:text-blue-600">Home</a>
                <a href="index.php#services" class="text-sm font-medium transition-colors hover:text-blue-600">Services</a>
                <a href="index.php#about" class="text-sm font-medium transition-colors hover:text-blue-600">About</a>
                <a href="index.php#contact" class="text-sm font-medium transition-colors hover:text-blue-600">Contact</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="reservation.php" class="hidden md:block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                    Book Appointment
                </a>
                <a href="auth/login.php" class="hidden md:block text-gray-700 hover:text-blue-600 font-medium py-2 px-4 border border-gray-300 rounded-md hover:border-blue-600 transition-colors">
                    Staff Login
                </a>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex flex-col gap-3">
                    <a href="index.php" class="text-gray-700 hover:text-blue-600 py-2">Home</a>
                    <a href="index.php#services" class="text-gray-700 hover:text-blue-600 py-2">Services</a>
                    <a href="index.php#about" class="text-gray-700 hover:text-blue-600 py-2">About</a>
                    <a href="index.php#contact" class="text-gray-700 hover:text-blue-600 py-2">Contact</a>
                    <a href="reservation.php" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
                        Book Appointment
                    </a>
                    <a href="auth/login.php" class="text-gray-700 hover:text-blue-600 font-medium py-2 px-4 border border-gray-300 rounded-md text-center hover:border-blue-600 transition-colors">
                        Staff Login
                    </a>
                </nav>
            </div>
        </div>
    </header>