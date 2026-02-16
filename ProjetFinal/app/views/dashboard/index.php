<link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/css/dashboard.css">

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
                    <h3><?php echo $totalVilles; ?></h3>
                    <p>Villes</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $totalBesoins; ?></h3>
                    <p>Besoins</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $totalDons; ?></h3>
                    <p>Dons</p>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-wrapper">
                <?php if (!empty($dashboard)): ?>
                    <?php
                        $sumBesoins = 0;
                        $sumQuantite = 0;
                        $sumAllouee = 0;
                        $sumRestante = 0;
                    ?>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Produits demandés</th>
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
                                ?>
                                <tr>
                                    <td class="ville-name"><?php echo htmlspecialchars($item['ville']['nom']); ?></td>
                                    <td>
                                        <?php if (!empty($item['produits'])): ?>
                                            <div class="product-chips">
                                                <?php foreach ($item['produits'] as $produitNom): ?>
                                                    <span class="chip"><?php echo htmlspecialchars($produitNom); ?></span>
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
                                        <?php if ($item['etat'] === '✅ Satisfait'): ?>
                                            <span class="badge badge-satisfait">Satisfait</span>
                                        <?php elseif ($item['etat'] === '⏳ Partiel'): ?>
                                            <span class="badge badge-partiel">Partiel</span>
                                        <?php elseif ($item['etat'] === '❌ En attente'): ?>
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
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="ville-name">Total</td>
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
                    <p class="table-note">État basé sur la quantité restante des besoins. La progression correspond au taux de couverture allouée.</p>
                <?php else: ?>
                    <div class="no-data">
                        <p>Aucune donnée disponible pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="dashboard-actions">
                <a href="<?php echo Flight::get('flight.base_url'); ?>/simulate" class="btn-dashboard" style="background: #27ae60;">Simuler l'allocation</a>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/villes" class="btn-dashboard">Gérer les villes</a>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/besoins" class="btn-dashboard">Gérer les besoins</a>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/dons" class="btn-dashboard btn-primary">Ajouter un don</a>
            </div>
        </div>

    </div>
</section>

<style>
   /* ===== VARIABLES & BASE ===== */
:root {
    --primary: #e53e3e;
    --primary-dark: #c53030;
    --primary-light: #feb2b2;
    --secondary: #4a5568;
    --success: #38a169;
    --warning: #d69e2e;
    --danger: #e53e3e;
    --gray-50: #f7fafc;
    --gray-100: #edf2f7;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e0;
    --gray-400: #a0aec0;
    --gray-500: #718096;
    --gray-600: #4a5568;
    --gray-700: #2d3748;
    --gray-800: #1a202c;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --radius-sm: 0.375rem;
    --radius: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --transition: all 0.2s ease;
}

* {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background: var(--gray-50);
    color: var(--gray-800);
    line-height: 1.5;
}

/* ===== LAYOUT PRINCIPAL ===== */
.dashboard-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    max-width: 1440px;
    margin: 2rem auto;
    padding: 0 2rem;
    align-items: start;
}

.dashboard-left {
    background: white;
    padding: 2rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
}

.dashboard-left:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.dashboard-left h2 {
    color: var(--primary);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
    letter-spacing: -0.025em;
    position: relative;
    display: inline-block;
    left: 50%;
    transform: translateX(-50%);
}

.dashboard-left h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--primary), transparent);
    border-radius: 2px;
}

/* ===== CARTES STATISTIQUES ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.stat-card {
    background: white;
    color: var(--gray-800);
    padding: 1.5rem 1rem;
    border-radius: var(--radius);
    text-align: center;
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: 1px solid var(--gray-200);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-light);
}

.stat-card h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.stat-card p {
    font-size: 0.95rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* ===== TABLEAU ===== */
.table-wrapper {
    overflow-x: auto;
    margin: 2rem 0;
    border-radius: var(--radius);
    border: 1px solid var(--gray-200);
    background: white;
}

.dashboard-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.dashboard-table thead {
    background: var(--gray-50);
    border-bottom: 2px solid var(--primary);
}

.dashboard-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
    color: var(--gray-700);
    white-space: nowrap;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dashboard-table tbody tr {
    border-bottom: 1px solid var(--gray-200);
    transition: var(--transition);
}

.dashboard-table tbody tr:last-child {
    border-bottom: none;
}

.dashboard-table tbody tr:hover {
    background: var(--gray-50);
}

.dashboard-table td {
    padding: 1rem 1.5rem;
    color: var(--gray-600);
}

.ville-name {
    font-weight: 600;
    color: var(--primary);
}

.dashboard-table tfoot td {
    background: var(--gray-50);
    border-top: 2px solid var(--gray-200);
    font-weight: 600;
}

.table-note {
    margin: 0.75rem 0 0;
    font-size: 0.85rem;
    color: var(--gray-500);
}

.badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
}

.badge-satisfait {
    background: #d4edda;
    color: #155724;
}

.badge-partiel {
    background: #fff3cd;
    color: #856404;
}

.badge-attente {
    background: #f8d7da;
    color: #721c24;
}

.badge-na {
    background: #e2e8f0;
    color: #4a5568;
}

.product-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
}

.chip {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
    background: var(--gray-100);
    color: var(--gray-700);
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid var(--gray-200);
}

.muted {
    color: var(--gray-400);
    font-size: 0.8rem;
}

/* ===== BARRE DE PROGRÈS ===== */
.progress-bar {
    width: 100%;
    height: 8px;
    background: var(--gray-200);
    border-radius: 4px;
    overflow: hidden;
    margin: 0.5rem 0;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--primary-dark));
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 4px;
}

td small {
    display: block;
    text-align: center;
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
}

/* ===== BOUTONS D'ACTION ===== */
.dashboard-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 240px));
    gap: 1rem;
    margin-top: 2rem;
    justify-content: start;
}

.btn-dashboard {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    font-weight: 600;
    border-radius: var(--radius);
    text-align: center;
    transition: var(--transition);
    background: white;
    color: var(--primary);
    border: 2px solid var(--primary);
    cursor: pointer;
    font-size: 0.95rem;
    box-shadow: var(--shadow-sm);
}

.btn-dashboard:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-dashboard.btn-primary {
    background: var(--primary);
    color: white;
    border: 2px solid var(--primary);
}

.btn-dashboard.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    box-shadow: 0 10px 15px -3px rgba(229, 62, 62, 0.4);
}

/* ===== SECTION DÉCORATIVE DROITE ===== */
.dashboard-right {
    background: white;
    padding: 2rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    height: 600px;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}

.dashboard-right:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.heroDashboard {
    height: 100%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: linear-gradient(135deg, #fff5f5 0%, white 100%);
    border-radius: var(--radius);
}

/* Éléments décoratifs animés */
.diagonal-grid {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.soft-block {
    position: absolute;
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    opacity: 0.1;
    border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
    filter: blur(40px);
    animation: float 20s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

.static-decoration {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.static-block-outline {
    position: absolute;
    border: 3px solid var(--primary);
    opacity: 0.1;
    border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
}

.static-block {
    position: absolute;
    background: var(--primary);
    opacity: 0.05;
    border-radius: 40% 60% 60% 40% / 40% 40% 60% 60%;
}

.bottom-right-decoration {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.red-block {
    position: absolute;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    opacity: 0.1;
    border-radius: 50% 50% 50% 50% / 60% 40% 60% 40%;
    filter: blur(20px);
    animation: pulse 8s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.1); opacity: 0.15; }
}

/* ===== MESSAGE AUCUNE DONNÉE ===== */
.no-data {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray-400);
    font-size: 1.2rem;
    background: var(--gray-50);
    border-radius: var(--radius);
    border: 2px dashed var(--gray-300);
}

/* ===== BADGES (non utilisés mais prêts) ===== */
.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-satisfait {
    background: #c6f6d5;
    color: #22543d;
}

.badge-partiel {
    background: #feebc8;
    color: #744210;
}

.badge-attente {
    background: #fed7d7;
    color: #822727;
}

.badge-na {
    background: var(--gray-200);
    color: var(--gray-600);
}

/* ===== RESPONSIVITÉ ===== */
@media (max-width: 1200px) {
    .dashboard-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 1rem;
    }

    .dashboard-right {
        display: none;
    }

    .dashboard-left {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 0.5rem;
    }

    .dashboard-left {
        padding: 1.5rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .dashboard-actions {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .dashboard-left h2 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .dashboard-table th,
    .dashboard-table td {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
    }

    .btn-dashboard {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .dashboard-left {
        padding: 1rem;
    }

    .stat-card h3 {
        font-size: 2rem;
    }

    .dashboard-table th,
    .dashboard-table td {
        padding: 0.5rem;
    }
}
</style>