<?php
// Variables dynamiques pour connecter le modèle aux autres pages
$pageTitle = isset($pageTitle) ? $pageTitle : 'BNGRC - Bureau de Gestion des Ressources Communes';
$currentPage = isset($currentPage) ? $currentPage : 'home';
$baseUrl = isset($baseUrl) ? $baseUrl : Flight::get('flight.base_url');
$navItems = isset($navItems) ? $navItems : [
    ['label' => 'Accueil', 'url' => $baseUrl . '/accueil', 'id' => 'accueil'],
    ['label' => 'Dashboard', 'url' => $baseUrl . '/dashboard', 'id' => 'dashboard'],
    ['label' => 'Besoins', 'url' => $baseUrl . '/besoins', 'id' => 'besoins'],
    ['label' => 'Dons', 'url' => $baseUrl . '/dons', 'id' => 'dons'],
    ['label' => 'Achats', 'url' => $baseUrl . '/achat/list', 'id' => 'achat'],
    ['label' => 'Récapitulatif', 'url' => $baseUrl . '/recapitulatif', 'id' => 'recapitulatif'],
    ['label' => 'Frais', 'url' => $baseUrl . '/achat/frais', 'id' => 'frais'],
    ['label' => 'Villes', 'url' => $baseUrl . '/villes', 'id' => 'ville'],
    ['label' => 'Régions', 'url' => $baseUrl . '/regions', 'id' => 'region']
];


$nonce = Flight::app()->get('csp_nonce');

// Données du restaurant
$restaurantInfo = isset($restaurantInfo) ? $restaurantInfo : [
    'name' => 'BNGRC',
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
    <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/public/css/filters.css">
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

BNGRC - Bureau de Gestion des Ressources Communes

https://www.tooplate.com/view/2148-bistro-elegance

Free HTML CSS Template

-->
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="<?php echo htmlspecialchars($baseUrl); ?>" class="logo">
            <svg width="45" height="45" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Cercle de fond bleu (couleur principale BNGRC) -->
    <circle cx="50" cy="50" r="48" fill="#1e3a8a" stroke="none"/>
    
    <!-- Anneau extérieur blanc pour contraste -->
    <circle cx="50" cy="50" r="45" fill="none" stroke="#ffffff" stroke-width="2" opacity="0.8"/>
    
    <!-- Éléments de protection/sécurité (bouclier stylisé) -->
    <path d="M50 15 L75 30 L75 55 Q50 80 25 55 L25 30 L50 15" fill="#fbbf24" opacity="0.95" stroke="#ffffff" stroke-width="1.5"/>
    
    <!-- Silhouette de Madagascar (blanche) -->
    <path d="M50 35 L58 40 L60 48 L55 55 L60 63 L55 70 L50 73 L45 70 L40 63 L45 55 L40 48 L42 40 L50 35" fill="#ffffff" opacity="0.9"/>
    
    <!-- Éléments de gestion/ressources (engrenages stylisés) -->
    <g fill="#f59e0b" opacity="0.9">
        <!-- Petit engrenage droite -->
        <circle cx="70" cy="40" r="8" fill="#f59e0b"/>
        <circle cx="70" cy="40" r="5" fill="#1e3a8a"/>
        <!-- Petit engrenage gauche -->
        <circle cx="30" cy="60" r="8" fill="#f59e0b"/>
        <circle cx="30" cy="60" r="5" fill="#1e3a8a"/>
    </g>
    
    <!-- Lignes de connexion (réseau/coordination) -->
    <path d="M40 45 L50 38 L60 45 M35 55 L45 62 L55 55" stroke="#ffffff" stroke-width="2" opacity="0.7" stroke-dasharray="3 2"/>
    
    <!-- Initiales BNGRC minimales -->
    <text x="50" y="88" text-anchor="middle" fill="#ffffff" font-size="9" font-weight="bold" font-family="Arial, sans-serif">BNGRC</text>
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
        <h1>ETU004219 - ETU004185 - ETU003947</h1>
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