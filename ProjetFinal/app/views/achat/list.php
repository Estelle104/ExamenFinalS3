<section class="list-section">
    <div class="list-container">
        <div class="list-header">
            <h2>Liste des Achats Effectué</h2>
            <a href="<?php echo Flight::get('flight.base_url'); ?>/achat/add" class="btn-add">Ajouter un achat</a>
        </div>

        <!-- Filtres -->
        <div class="filter-section">
            <div class="filter-header">
                <h3>Filtrer les achats</h3>
                <button type="button" id="toggle-filters" class="toggle-filters">Afficher/Masquer filtres</button>
            </div>
            
            <div class="filter-content" id="filter-content">
                <div class="filter-group">
                    <label for="filter-ville">Ville:</label>
                    <select id="filter-ville" class="filter-select">
                        <option value="">-- Tous --</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?php echo htmlspecialchars($ville['nom']); ?>">
                                <?php echo htmlspecialchars($ville['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-produit">Produit:</label>
                    <select id="filter-produit" class="filter-select">
                        <option value="">-- Tous --</option>
                        <?php foreach ($produits as $produit): ?>
                            <option value="<?php echo htmlspecialchars($produit['nom']); ?>">
                                <?php echo htmlspecialchars($produit['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-date">Date d'achat:</label>
                    <input type="date" id="filter-date" class="filter-input">
                </div>

                <div class="filter-group">
                    <label for="filter-montant-min">Montant minimum:</label>
                    <input type="number" id="filter-montant-min" class="filter-input" placeholder="0" min="0">
                </div>

                <div class="filter-group">
                    <label for="filter-montant-max">Montant maximum:</label>
                    <input type="number" id="filter-montant-max" class="filter-input" placeholder="0" min="0">
                </div>

                <div class="filter-group">
                    <label for="filter-quantite-min">Quantité minimum:</label>
                    <input type="number" id="filter-quantite-min" class="filter-input" placeholder="0" min="0">
                </div>

                <div class="filter-group">
                    <label for="filter-quantite-max">Quantité maximum:</label>
                    <input type="number" id="filter-quantite-max" class="filter-input" placeholder="0" min="0">
                </div>

                <div class="filter-actions">
                    <button type="button" id="apply-filters" class="btn-filter">Appliquer filtres</button>
                    <button type="button" id="reset-filters" class="btn-reset">Réinitialiser</button>
                </div>
            </div>
        </div>

        <div id="filter-result" class="filter-result"></div>

        <div class="list-content">
            <?php if (!empty($achats)): ?>
                <table class="list-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produit</th>
                            <th>Ville</th>
                            <th>Quantité</th>
                            <th>Montant Total</th>
                            <th>Date d'achat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($achats as $achat): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$achat['id']); ?></td>
                                <td><?php echo htmlspecialchars($achat['produit_nom'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($achat['ville_nom'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars((string)$achat['quantite']); ?></td>
                                <td><?php echo htmlspecialchars($achat['montant_total'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($achat['date_achat'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>Aucun achat trouvé.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<style>
    .list-section {
        padding: 20px;
        background: #f8fafc;
        min-height: 80vh;
    }

    .list-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(30, 58, 138, 0.1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem;
        border-bottom: 3px solid #fbbf24;
        background: linear-gradient(to right, #ffffff, #f8fafc);
    }

    .list-header h2 {
        color: #1e3a8a;
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        position: relative;
        padding-left: 1rem;
    }

    .list-header h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background: #fbbf24;
        border-radius: 2px;
    }

    .btn-add {
        background: #1e3a8a;
        color: white;
        padding: 0.8rem 1.8rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 2px solid transparent;
    }

    .btn-add:hover {
        background: #2d4ec0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .btn-add:active {
        transform: translateY(0);
    }

    .btn-add i {
        font-size: 1.1rem;
    }

    .list-content {
        padding: 2rem;
        overflow-x: auto;
    }

    .list-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
    }

    .list-table thead {
        background: #1e3a8a;
        border-bottom: none;
    }

    .list-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: white;
        white-space: nowrap;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .list-table th:first-child {
        padding-left: 1.5rem;
    }

    .list-table th:last-child {
        padding-right: 1.5rem;
    }

    .list-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .list-table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(30, 58, 138, 0.1);
    }

    .list-table td {
        padding: 1rem;
        color: #475569;
    }

    .list-table td:first-child {
        padding-left: 1.5rem;
        font-weight: 500;
        color: #1e3a8a;
    }

    .list-table td:last-child {
        padding-right: 1.5rem;
    }

    .btn-view {
        background: #fbbf24;
        color: #1e3a8a;
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        border: 2px solid transparent;
    }

    .btn-view:hover {
        background: #f59e0b;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
    }

    .btn-view:active {
        transform: translateY(0);
    }

    /* Variante de bouton pour actions secondaires */
    .btn-edit {
        background: transparent;
        color: #1e3a8a;
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        border: 2px solid #1e3a8a;
        margin-right: 0.5rem;
    }

    .btn-edit:hover {
        background: #1e3a8a;
        color: white;
        transform: translateY(-2px);
    }

    .no-data {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
        font-size: 1.1rem;
        background: #f8fafc;
        border-radius: 8px;
        margin: 1rem;
    }

    .no-data i {
        font-size: 3rem;
        color: #fbbf24;
        margin-bottom: 1rem;
        display: block;
    }

    /* Badges de statut */
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-badge.pending {
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde047;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
        padding: 1rem;
    }

    .pagination-item {
        padding: 0.5rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        color: #1e3a8a;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .pagination-item:hover,
    .pagination-item.active {
        background: #1e3a8a;
        color: white;
        border-color: #1e3a8a;
    }

    /* Filtres */
    .filters-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .filter-input {
        flex: 1;
        padding: 0.6rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-input:focus {
        outline: none;
        border-color: #fbbf24;
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
    }

    .filter-btn {
        background: #1e3a8a;
        color: white;
        padding: 0.6rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        background: #2d4ec0;
    }

</style>

<link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/public/css/filters.css">

<script nonce="<?php echo Flight::get('csp_nonce'); ?>">
document.addEventListener('DOMContentLoaded', function() {
    const filterContent = document.getElementById('filter-content');
    const toggleFiltersBtn = document.getElementById('toggle-filters');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const resetFiltersBtn = document.getElementById('reset-filters');
    const filterVilleSelect = document.getElementById('filter-ville');
    const filterProduitSelect = document.getElementById('filter-produit');
    const filterDateInput = document.getElementById('filter-date');
    const filterMontantMinInput = document.getElementById('filter-montant-min');
    const filterMontantMaxInput = document.getElementById('filter-montant-max');
    const filterQuantiteMinInput = document.getElementById('filter-quantite-min');
    const filterQuantiteMaxInput = document.getElementById('filter-quantite-max');

    // Toggle filters visibility
    if (toggleFiltersBtn) {
        toggleFiltersBtn.addEventListener('click', function() {
            filterContent.classList.toggle('hidden');
        });
    }

    // Apply filters
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', filterTable);
    }

    // Reset filters
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            filterVilleSelect.value = '';
            filterProduitSelect.value = '';
            filterDateInput.value = '';
            filterMontantMinInput.value = '';
            filterMontantMaxInput.value = '';
            filterQuantiteMinInput.value = '';
            filterQuantiteMaxInput.value = '';
            filterTable();
        });
    }

    function filterTable() {
        const table = document.querySelector('.list-table');
        if (!table) return;

        const rows = table.querySelectorAll('tbody tr');
        const villeFilter = filterVilleSelect.value.toLowerCase();
        const produitFilter = filterProduitSelect.value.toLowerCase();
        const dateFilter = filterDateInput.value;
        const montantMin = parseFloat(filterMontantMinInput.value) || 0;
        const montantMax = parseFloat(filterMontantMaxInput.value) || Infinity;
        const quantiteMin = parseFloat(filterQuantiteMinInput.value) || 0;
        const quantiteMax = parseFloat(filterQuantiteMaxInput.value) || Infinity;

        let visibleCount = 0;
        let totalRows = 0;

        rows.forEach(row => {
            totalRows++;
            const cells = row.querySelectorAll('td');
            
            const produit = cells[1]?.textContent.toLowerCase() || '';
            const ville = cells[2]?.textContent.toLowerCase() || '';
            const quantite = parseFloat(cells[3]?.textContent) || 0;
            const montant = parseFloat(cells[4]?.textContent) || 0;
            const date = cells[5]?.textContent || '';

            let showRow = true;

            // Appliquer les filtres
            if (villeFilter && !ville.includes(villeFilter)) {
                showRow = false;
            }
            if (produitFilter && !produit.includes(produitFilter)) {
                showRow = false;
            }
            if (dateFilter && !date.includes(dateFilter)) {
                showRow = false;
            }
            if (montantMin > 0 && montant < montantMin) {
                showRow = false;
            }
            if (montantMax < Infinity && montant > montantMax) {
                showRow = false;
            }
            if (quantiteMin > 0 && quantite < quantiteMin) {
                showRow = false;
            }
            if (quantiteMax < Infinity && quantite > quantiteMax) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        });

        // Afficher le nombre de résultats
        const resultDiv = document.getElementById('filter-result');
        if (resultDiv) {
            if (visibleCount === 0) {
                resultDiv.innerHTML = '<p style="color: #ef4444; font-weight: bold;">Aucun résultat ne correspond aux filtres.</p>';
            } else if (visibleCount < totalRows) {
                resultDiv.innerHTML = `<p>Affichage de <strong>${visibleCount}</strong> achat(s) sur <strong>${totalRows}</strong></p>`;
            } else {
                resultDiv.innerHTML = '';
            }
        }
    }

    // Appliquer les filtres au changement de valeur (optionnel pour l'UX)
    [filterVilleSelect, filterProduitSelect, filterDateInput, filterMontantMinInput, filterMontantMaxInput, filterQuantiteMinInput, filterQuantiteMaxInput].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                // Optionnel: décommenter pour filtrer automatiquement
                // filterTable();
            });
        }
    });
});
</script>