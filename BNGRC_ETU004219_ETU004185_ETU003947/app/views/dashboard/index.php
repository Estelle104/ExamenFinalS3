<link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/public/css/dashboard.css">

<section id="dashboard">
    <div class="dashboard-container">
        <!-- Left Section: Stats & Table -->
        <div class="dashboard-left">
            <h2>Tableau de Bord BNGRC</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="margin-bottom: 20px; padding: 1rem; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

           
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo isset($totalVilles) ? $totalVilles : 0; ?></h3>
                    <p>Villes</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo isset($totalBesoins) ? $totalBesoins : 0; ?></h3>
                    <p>Besoins</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo isset($totalDons) ? $totalDons : 0; ?></h3>
                    <p>Dons</p>
                </div>
            </div>

             <div class="dashboard-actions" style="margin-bottom: 20px; margin-top: 50px;">
                <a href="<?php echo Flight::get('flight.base_url'); ?>/simulate" class="btn-dashboard" style="background: #f59e0b;">Simuler l'allocation</a>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/dashboard/details" class="btn-dashboard" style="background: #e0e7ff;"> Détails par produit</a>
                <!-- <a href="<?php echo Flight::get('flight.base_url'); ?>/villes" class="btn-dashboard">Gérer les villes</a>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/besoins" class="btn-dashboard">Gérer les besoins</a> -->
                <a href="<?php echo Flight::get('flight.base_url'); ?>/dons" class="btn-dashboard btn-primary">Ajouter un don</a>
            </div>

            <!-- Main Table -->
            <div class="table-wrapper">
                <?php if (!empty($dashboard)): ?>
                    <?php
                        $sumBesoins = 0;
                        $sumQuantite = 0;
                        $sumAllouee = 0;
                        $sumRestante = 0;
                        $rowIndex = 0;
                    ?>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Catégories</th>
                                <th>Besoins</th>
                                <th>Quantité nécessaire</th>
                                <th>Quantité allouée</th>
                                <th>Quantité restante</th>
                                <th>État</th>
                                <th>Progression</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dashboard as $item): ?>
                                <?php
                                    $sumBesoins += $item['totalBesoins'];
                                    $sumQuantite += $item['totalBesoinsQuantite'];
                                    $sumAllouee += $item['quantiteAllouee'];
                                    $sumRestante += $item['quantiteRestante'];
                                    $rowIndex++;
                                    $hasCategories = !empty($item['parCategorie']);
                                ?>
                                <tr class="ville-row <?php echo $hasCategories ? 'expandable' : ''; ?>" data-row="<?php echo $rowIndex; ?>">
                                    <td class="ville-name">
                                        <?php if ($hasCategories): ?>
                                            <span class="expand-icon">▶</span>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($item['ville']['nom']); ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['produits'])): ?>
                                            <div class="product-chips">
                                                <?php foreach ($item['produits'] as $categorie): ?>
                                                    <span class="chip chip-categorie"><?php echo htmlspecialchars($categorie); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="muted">Aucun</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $item['totalBesoins']; ?></td>
                                    <td><?php echo $item['totalBesoinsQuantite']; ?> unités</td>
                                    <td>
                                        <?php echo $item['quantiteAllouee']; ?> unités
                                        <small><?php echo $item['quantiteAllouee']; ?>/<?php echo $item['totalBesoinsQuantite']; ?></small>
                                    </td>
                                    <td><?php echo $item['quantiteRestante']; ?> unités</td>
                                    <td>
                                        <?php if ($item['etat'] === ' Satisfait'): ?>
                                            <span class="badge badge-satisfait">Satisfait</span>
                                        <?php elseif ($item['etat'] === ' Partiel'): ?>
                                            <span class="badge badge-partiel">Partiel</span>
                                        <?php elseif ($item['etat'] === ' En attente'): ?>
                                            <span class="badge badge-attente">En attente</span>
                                        <?php else: ?>
                                            <span class="badge badge-na">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?php echo min($item['pourcentage'], 100); ?>%"></div>
                                        </div>
                                        <small><?php echo $item['pourcentage']; ?>%</small>
                                    </td>
                                </tr>
                                <?php if ($hasCategories): ?>
                                    <?php foreach ($item['parCategorie'] as $cat): ?>
                                        <?php
                                            $catPourcentage = $cat['quantiteNecessaire'] > 0 
                                                ? round(($cat['quantiteAllouee'] / $cat['quantiteNecessaire']) * 100, 2) 
                                                : 0;
                                        ?>
                                        <tr class="sub-row sub-row-<?php echo $rowIndex; ?>" style="display: none;">
                                            <td class="sub-cell-indent">
                                                <span class="sub-indicator">└</span>
                                                <span class="sub-categorie-name"><?php echo htmlspecialchars($cat['nom']); ?></span>
                                            </td>
                                            <td>
                                                <div class="product-chips product-chips-small">
                                                    <?php foreach ($cat['produits'] as $produit): ?>
                                                        <span class="chip chip-small"><?php echo htmlspecialchars($produit); ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                            <td class="sub-cell"><?php echo $cat['nbBesoins']; ?></td>
                                            <td class="sub-cell"><?php echo $cat['quantiteNecessaire']; ?> unités</td>
                                            <td class="sub-cell"><?php echo $cat['quantiteAllouee']; ?> unités</td>
                                            <td class="sub-cell"><?php echo $cat['quantiteRestante']; ?> unités</td>
                                            <td class="sub-cell">
                                                <?php if ($cat['quantiteRestante'] <= 0): ?>
                                                    <span class="badge badge-satisfait badge-small">✓</span>
                                                <?php elseif ($cat['quantiteAllouee'] > 0): ?>
                                                    <span class="badge badge-partiel badge-small">~</span>
                                                <?php else: ?>
                                                    <span class="badge badge-attente badge-small">✗</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="sub-cell">
                                                <div class="progress-bar progress-bar-small">
                                                    <div class="progress-fill" style="width: <?php echo min($catPourcentage, 100); ?>%"></div>
                                                </div>
                                                <small><?php echo $catPourcentage; ?>%</small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="ville-name">Total</td>
                                <td></td>
                                <td><?php echo $sumBesoins; ?></td>
                                <td><?php echo $sumQuantite; ?> unités</td>
                                <td><?php echo $sumAllouee; ?> unités</td>
                                <td><?php echo $sumRestante; ?> unités</td>
                                <td>
                                    <?php if ($sumQuantite <= 0): ?>
                                        <span class="badge badge-na">N/A</span>
                                    <?php elseif ($sumRestante <= 0): ?>
                                        <span class="badge badge-satisfait">Satisfait</span>
                                    <?php elseif ($sumAllouee > 0): ?>
                                        <span class="badge badge-partiel">Partiel</span>
                                    <?php else: ?>
                                        <span class="badge badge-attente">En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $totalPourcentage = $sumQuantite > 0
                                            ? round(($sumAllouee / $sumQuantite) * 100, 2)
                                            : 0;
                                    ?>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo min($totalPourcentage, 100); ?>%"></div>
                                    </div>
                                    <small><?php echo $totalPourcentage; ?>%</small>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <p class="table-note">Cliquez sur une ville pour voir les détails par catégorie. État basé sur la quantité restante des besoins.</p>
                <?php else: ?>
                    <div class="no-data">
                        <p>Aucune donnée disponible pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            
        </div>

    </div>
</section>
<style>
/* ===== VARIABLES & BASE ===== */
:root {
    /* Couleurs principales BNGRC */
    --primary: #1e3a8a;
    --primary-dark: #1e2e6a;
    --primary-light: #3b5bbf;
    --primary-soft: #e0e7ff;
    
    /* Accents */
    --accent-yellow: #fbbf24;
    --accent-orange: #f59e0b;
    
    /* États */
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --info: #3b82f6;
    
    /* Niveaux de gris */
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    
    /* Ombres */
    --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    
    /* Bordures */
    --radius-xs: 0.25rem;
    --radius-sm: 0.375rem;
    --radius: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --radius-xl: 1.5rem;
    --radius-full: 9999px;
    
    /* Transitions */
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Typographie */
    --font-sans: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --font-mono: ui-monospace, monospace;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-sans);
    background: var(--gray-50);
    color: var(--gray-800);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ===== LAYOUT PRINCIPAL ===== */
.dashboard-container {
    max-width: 1440px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.dashboard-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    transition: var(--transition-slow);
    overflow: hidden;
    border: 1px solid var(--gray-200);
}

.dashboard-card:hover {
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-light);
}

.dashboard-card__header {
    padding: 1.5rem 2rem;
    border-bottom: 2px solid var(--accent-yellow);
    background: linear-gradient(to right, white, var(--gray-50));
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-card__title {
    color: var(--primary);
    font-size: 1.75rem;
    font-weight: 700;
    letter-spacing: -0.025em;
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.dashboard-card__title::before {
    content: '';
    width: 4px;
    height: 1.5em;
    background: var(--accent-yellow);
    border-radius: var(--radius-full);
}

.dashboard-card__content {
    padding: 2rem;
}

/* ===== CARTES STATISTIQUES ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: 1px solid var(--gray-200);
    position: relative;
    overflow: hidden;
}

.stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--accent-yellow));
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-light);
}

.stat-card__value {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary);
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.stat-card__label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-card__trend {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
}

.stat-card__trend--up {
    background: var(--success-light);
    color: var(--success);
}

.stat-card__trend--down {
    background: var(--danger-light);
    color: var(--danger);
}

/* ===== TABLEAU ===== */
.table-container {
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    overflow: hidden;
    margin: 1.5rem 0;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.table thead {
    background: var(--gray-50);
    border-bottom: 2px solid var(--primary);
}

.table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.table tbody tr {
    border-bottom: 1px solid var(--gray-200);
    transition: var(--transition);
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table tbody tr:hover {
    background: var(--gray-50);
}

.table td {
    padding: 1rem 1.5rem;
    color: var(--gray-600);
}

.table__cell--highlight {
    color: var(--primary);
    font-weight: 600;
}

.table__cell--numeric {
    font-family: var(--font-mono);
    font-weight: 500;
    text-align: right;
}

.table tfoot td {
    background: var(--gray-50);
    padding: 1rem 1.5rem;
    font-weight: 600;
    border-top: 2px solid var(--gray-200);
}

/* ===== BADGES ===== */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1.5;
    white-space: nowrap;
}

.badge--satisfait {
    background: var(--success-light);
    color: var(--success);
    border: 1px solid color-mix(in srgb, var(--success) 20%, transparent);
}

.badge--partiel {
    background: var(--warning-light);
    color: #92400e;
    border: 1px solid color-mix(in srgb, var(--warning) 20%, transparent);
}

.badge--attente {
    background: var(--danger-light);
    color: var(--danger);
    border: 1px solid color-mix(in srgb, var(--danger) 20%, transparent);
}

.badge--info {
    background: var(--primary-soft);
    color: var(--primary);
    border: 1px solid color-mix(in srgb, var(--primary) 20%, transparent);
}

/* ===== BARRE DE PROGRÈS ===== */
.progress {
    width: 100%;
    height: 0.5rem;
    background: var(--gray-200);
    border-radius: var(--radius-full);
    overflow: hidden;
}

.progress__fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: var(--radius-full);
}

.progress__label {
    display: block;
    text-align: center;
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
}

/* ===== BOUTONS ===== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.5;
    border-radius: var(--radius);
    transition: var(--transition);
    cursor: pointer;
    border: none;
    text-decoration: none;
    white-space: nowrap;
}

.btn--primary {
    background: var(--primary);
    color: white;
    border: 1px solid var(--primary);
}

.btn--primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px color-mix(in srgb, var(--primary) 30%, transparent);
}

.btn--secondary {
    background: white;
    color: var(--primary);
    border: 1px solid var(--primary);
}

.btn--secondary:hover {
    background: var(--primary-soft);
    transform: translateY(-2px);
}

.btn--accent {
    background: var(--accent-yellow);
    color: var(--primary);
    border: 1px solid var(--accent-yellow);
}

.btn--accent:hover {
    background: var(--accent-orange);
    border-color: var(--accent-orange);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px color-mix(in srgb, var(--accent-orange) 30%, transparent);
}

.btn--outline {
    background: transparent;
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
}

.btn--outline:hover {
    background: var(--gray-50);
    border-color: var(--primary);
    color: var(--primary);
}

.btn--sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.btn--lg {
    padding: 0.875rem 1.75rem;
    font-size: 1rem;
}

/* ===== ACTIONS RAPIDES ===== */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

/* ===== ÉTAT DE CHARGEMENT ===== */
.skeleton {
    background: linear-gradient(
        90deg,
        var(--gray-200) 25%,
        var(--gray-100) 50%,
        var(--gray-200) 75%
    );
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: var(--radius);
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ===== MESSAGE AUCUNE DONNÉE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray-400);
    background: var(--gray-50);
    border-radius: var(--radius);
    border: 2px dashed var(--gray-300);
}

.empty-state__icon {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 1rem;
}

.empty-state__title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 0.5rem;
}

.empty-state__text {
    color: var(--gray-500);
}

/* ===== CHIPS ET TAGS ===== */
.chips-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.chip {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    background: var(--gray-100);
    color: var(--gray-700);
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid var(--gray-200);
    transition: var(--transition);
}

.chip:hover {
    background: var(--primary-soft);
    border-color: var(--primary-light);
    color: var(--primary);
}

.chip__remove {
    margin-left: 0.25rem;
    cursor: pointer;
    opacity: 0.6;
    transition: var(--transition);
}

.chip__remove:hover {
    opacity: 1;
}

/* ===== ANIMATIONS DÉCORATIVES ===== */
.dashboard-decor {
    height: 100%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-soft) 0%, white 100%);
    border-radius: var(--radius);
    overflow: hidden;
}

.decor-element {
    position: absolute;
    border-radius: 40% 60% 60% 40% / 40% 40% 60% 60%;
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    opacity: 0.1;
    filter: blur(40px);
    animation: float 20s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

.decor-element--2 {
    background: linear-gradient(135deg, var(--accent-yellow), var(--accent-orange));
    opacity: 0.1;
    animation: pulse 8s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.1); opacity: 0.15; }
}

/* ===== UTILITAIRES ===== */
.text-primary { color: var(--primary); }
.text-accent { color: var(--accent-yellow); }
.text-success { color: var(--success); }
.text-warning { color: var(--warning); }
.text-danger { color: var(--danger); }

.bg-primary-light { background: var(--primary-soft); }
.bg-success-light { background: var(--success-light); }
.bg-warning-light { background: var(--warning-light); }
.bg-danger-light { background: var(--danger-light); }

.mb-1 { margin-bottom: 0.5rem; }
.mb-2 { margin-bottom: 1rem; }
.mb-3 { margin-bottom: 1.5rem; }
.mb-4 { margin-bottom: 2rem; }

.mt-1 { margin-top: 0.5rem; }
.mt-2 { margin-top: 1rem; }
.mt-3 { margin-top: 1.5rem; }
.mt-4 { margin-top: 2rem; }

/* ===== RESPONSIVITÉ ===== */
@media (max-width: 1024px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-card__header {
        padding: 1.25rem;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .dashboard-card__content {
        padding: 1.25rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .table-container {
        margin: 1rem -1.25rem;
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .dashboard-card__title {
        font-size: 1.5rem;
    }
    
    .stat-card__value {
        font-size: 2rem;
    }
    
    .table th,
    .table td {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
}

/* ===== STYLES POUR SOUS-LIGNES PAR CATÉGORIE ===== */
.ville-row.expandable {
    cursor: pointer;
}

.ville-row.expandable:hover {
    background: #f0f9ff !important;
}

.expand-icon {
    display: inline-block;
    margin-right: 0.5rem;
    font-size: 0.7rem;
    color: #64748b;
    transition: transform 0.2s ease;
}

.ville-row.expanded .expand-icon {
    transform: rotate(90deg);
}

.sub-row {
    background: #f8fafc;
    border-left: 3px solid #e0e7ff;
}

.sub-row:hover {
    background: #f1f5f9 !important;
}

.sub-cell-indent {
    padding-left: 2rem !important;
    color: #64748b;
}

.sub-indicator {
    color: #cbd5e1;
    margin-right: 0.5rem;
    font-family: monospace;
}

.sub-categorie-name {
    font-weight: 600;
    color: #475569;
    font-size: 0.9rem;
}

.sub-cell {
    font-size: 0.875rem;
    color: #64748b;
}

.chip-categorie {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    font-weight: 600;
}

.chip-small {
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
    background: #e2e8f0;
    color: #475569;
}

.product-chips-small {
    gap: 0.3rem;
}

.badge-small {
    padding: 0.15rem 0.4rem;
    font-size: 0.75rem;
    min-width: auto;
}

.progress-bar-small {
    height: 6px;
}

/* Animation d'apparition des sous-lignes */
.sub-row.showing {
    animation: slideDown 0.2s ease-out forwards;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== MODE SOMBRE (optionnel) ===== */
@media (prefers-color-scheme: dark) {
    :root {
        --gray-50: #1a1e24;
        --gray-100: #2d3138;
        --gray-200: #3a3f48;
        --gray-300: #4b5563;
        --gray-400: #6b7280;
        --gray-500: #9ca3af;
        --gray-600: #d1d5db;
        --gray-700: #e5e7eb;
        --gray-800: #f3f4f6;
    }
    
    body {
        background: #111827;
        color: var(--gray-600);
    }
    
    .dashboard-card {
        background: var(--gray-100);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'expansion des lignes de ville
    const villeRows = document.querySelectorAll('.ville-row.expandable');
    
    villeRows.forEach(function(row) {
        row.addEventListener('click', function() {
            const rowId = this.getAttribute('data-row');
            const subRows = document.querySelectorAll('.sub-row-' + rowId);
            const isExpanded = this.classList.contains('expanded');
            
            if (isExpanded) {
                // Fermer
                this.classList.remove('expanded');
                subRows.forEach(function(subRow) {
                    subRow.style.display = 'none';
                    subRow.classList.remove('showing');
                });
            } else {
                // Ouvrir
                this.classList.add('expanded');
                subRows.forEach(function(subRow, index) {
                    subRow.style.display = '';
                    subRow.classList.add('showing');
                    // Décalage pour animation en cascade
                    subRow.style.animationDelay = (index * 0.05) + 's';
                });
            }
        });
    });
});
</script>