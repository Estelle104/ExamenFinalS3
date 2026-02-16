<?php
// Variables dynamiques pour connecter le modèle aux autres pages
$pageTitle = isset($pageTitle) ? $pageTitle : 'Takalo-Takalo - Fine Dining Experience';
$currentPage = isset($currentPage) ? $currentPage : 'home';
$baseUrl = isset($baseUrl) ? $baseUrl : Flight::get('flight.base_url');
$navItems = isset($navItems) ? $navItems : [
    ['label' => 'Home', 'url' => '#home', 'id' => 'home'],
    ['label' => 'Menu', 'url' => '#menu', 'id' => 'menu'],
    ['label' => 'Reservations', 'url' => '#reservation', 'id' => 'reservation'],
    ['label' => 'Contact', 'url' => '#contact', 'id' => 'contact']
];

$nonce = Flight::app()->get('csp_nonce');

// Données du restaurant
$restaurantInfo = isset($restaurantInfo) ? $restaurantInfo : [
    'name' => 'Takalo-Takalo',
    'address' => '123 Culinary Street',
    'district' => 'Gourmet District, GD 12345',
    'phone' => '(555) 123-4567',
    'email' => 'info@bistroelegance.com',
    'copyright' => '2026'
];

// Horaires
$hours = isset($hours) ? $hours : [
    'monday_thursday' => '5:00 PM - 10:00 PM',
    'friday_saturday' => '5:00 PM - 11:00 PM',
    'sunday' => '5:00 PM - 9:00 PM'
];

// Réseaux sociaux
$socialLinks = isset($socialLinks) ? $socialLinks : [
    'facebook' => '#',
    'instagram' => '#',
    'twitter' => '#',
    'linkedin' => '#'
];

// Contenu dynamique - page à inclure
$contentPage = isset($contentPage) ? $contentPage : 'accueil';
$contentPath = __DIR__ . '/' . $contentPage . '.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/public/css/tooplate-bistro-elegance.css">
    <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/public/css/style.css">
    <!-- <link rel="stylesheet" href="/css/tooplate-bistro-elegance.css">
    <link rel="stylesheet" href="/css/style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /* Modal overlay */
        #editModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
        }
        #editModal .modal-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 300px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        #editModal .modal-close {
            float: right;
            font-size: 1.5rem;
            background: transparent;
            border: none;
            cursor: pointer;
        }
    </style>
<!--

Tooplate 2148 Takalo-Takalo

https://www.tooplate.com/view/2148-bistro-elegance

Free HTML CSS Template

-->
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="<?php echo htmlspecialchars($baseUrl); ?>" class="logo">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Plate/Circle -->
                    <circle cx="20" cy="20" r="18" stroke="#e74c3c" stroke-width="2" fill="none"/>
                    <circle cx="20" cy="20" r="14" stroke="#e74c3c" stroke-width="1" fill="none" opacity="0.5"/>
                    
                    <!-- Fork -->
                    <path d="M10 12 L10 20 M8 12 L8 15 M12 12 L12 15 M8 12 L12 12" stroke="#333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    
                    <!-- Knife -->
                    <path d="M30 12 L30 20 M28 12 Q28 14 30 14" stroke="#333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    
                    <!-- Chef's hat accent -->
                    <ellipse cx="20" cy="26" rx="6" ry="3" fill="#e74c3c" opacity="0.2"/>
                    <path d="M14 26 C14 24 16 22 20 22 C24 22 26 24 26 26" stroke="#e74c3c" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    <circle cx="17" cy="24" r="1" fill="#e74c3c" opacity="0.6"/>
                    <circle cx="20" cy="23" r="1" fill="#e74c3c" opacity="0.6"/>
                    <circle cx="23" cy="24" r="1" fill="#e74c3c" opacity="0.6"/>
                </svg>
                <span><?php echo htmlspecialchars($restaurantInfo['name']); ?></span>
            </a>
            <ul class="nav-links">
                <?php foreach($navItems as $item): ?>
                <li>
                    <a href="<?php echo htmlspecialchars($item['url']); ?>" 
                       class="<?php echo $currentPage === $item['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($item['label']); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Contenu principal dynamique -->
    <main id="content">
        <?php
        // Inclure le fichier de contenu dynamiquement
        if (file_exists($contentPath)) {
            include $contentPath;
        } else {
            echo '<section style="padding: 2rem; text-align: center;">';
            echo '<h2>Page not found</h2>';
            echo '<p>Le fichier de contenu demandé n\'existe pas.</p>';
            echo '</section>';
        }
        ?>
    </main>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p><?php echo htmlspecialchars($restaurantInfo['address']); ?><br>
                <?php echo htmlspecialchars($restaurantInfo['district']); ?><br>
                Phone: <?php echo htmlspecialchars($restaurantInfo['phone']); ?><br>
                Email: <?php echo htmlspecialchars($restaurantInfo['email']); ?></p>
            </div>
            <div class="footer-section">
                <h3>Opening Hours</h3>
                <p>Monday - Thursday: <?php echo htmlspecialchars($hours['monday_thursday']); ?><br>
                Friday - Saturday: <?php echo htmlspecialchars($hours['friday_saturday']); ?><br>
                Sunday: <?php echo htmlspecialchars($hours['sunday']); ?><br>
                Closed on major holidays</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <p>
                    <a href="<?php echo htmlspecialchars($socialLinks['facebook']); ?>">Facebook</a><br>
                    <a href="<?php echo htmlspecialchars($socialLinks['instagram']); ?>">Instagram</a><br>
                    <a href="<?php echo htmlspecialchars($socialLinks['twitter']); ?>">Twitter</a><br>
                    <a href="<?php echo htmlspecialchars($socialLinks['linkedin']); ?>">LinkedIn</a>
                </p>
            </div>
        </div>
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #555; text-align: center;">
            <p>&copy; <?php echo htmlspecialchars($restaurantInfo['copyright']); ?> <?php echo htmlspecialchars($restaurantInfo['name']); ?>. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <span>|</span>
                <a href="#">Terms & Conditions</a>
                <span>|</span>
                <a href="#">Sitemap</a>
                <span>|</span>
                <a rel="nofollow noopener" href="https://www.tooplate.com" target="_blank">Designed by Tooplate</a>
            </div>
        </div>
    </footer>

    <!-- Ingredients Modal -->
    <div id="ingredientsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Dish Ingredients</h3>
            <ul id="ingredientsList" class="ingredient-list"></ul>
        </div>
    </div>

<script src="<?php echo Flight::get('flight.base_url'); ?>/public/js/tooplate-bistro-scripts.js"></script>
<!-- <script  script src="/js/tooplate-bistro-scripts.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>