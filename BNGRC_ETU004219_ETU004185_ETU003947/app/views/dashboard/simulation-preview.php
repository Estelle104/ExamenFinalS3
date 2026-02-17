<?php
$categories = $categories ?? [];
$strategiesConfirmees = $strategiesConfirmees ?? [];
$preview = $preview ?? ['details' => [], 'dispatch_crees' => 0];
$baseUrl = Flight::get('flight.base_url');
?>

<section class="list-section">
    <div class="list-container">
        <div class="list-header">
            <h2> Simulation de Distribution</h2>
        </div>

        <!-- Messages flash -->
        <?php if (isset($_SESSION['error'])): ?>
            <div style="background: #fef2f2; border: 2px solid #ef4444; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #dc2626;">
                ⚠️ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="background: #f0fdf4; border: 2px solid #10b981; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #166534;">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
            <!-- Boutons d'action -->
        <div style="display: flex; margin-bottom:20px; gap: 1rem; margin-top: 2rem; justify-content: center; flex-wrap: wrap;">
            <?php if (!empty($strategiesConfirmees)): ?>
            <a href="<?php echo $baseUrl; ?>/simulate-valider" 
               id="btn-valider"
               style="background: #10b981; color: white; padding: 0.75rem 2rem; border: none; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                 Valider réellement (<?php echo count($strategiesConfirmees); ?> catégorie(s))
            </a>
            <?php else: ?>
            <span style="background: #9ca3af; color: white; padding: 0.75rem 2rem; border-radius: 6px; font-weight: 600; cursor: not-allowed;">
                 Valider (sélectionnez des stratégies)
            </span>
            <?php endif; ?>
            
            <a href="<?php echo $baseUrl; ?>/simulate-annuler" 
               style="background: #ef4444; color: white; padding: 0.75rem 2rem; border: none; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                Annuler
            </a>
        </div>
        <!-- Section: Dons par catégorie -->
        <?php if (!empty($categories)): ?>
        <div style="background: #fff7ed; border: 2px solid #f97316; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="color: #c2410c; margin-top: 0;">
                 Dons non distribués (par catégorie)
            </h3>
            
            <?php 
            $nbConfirmees = count($strategiesConfirmees);
            if ($nbConfirmees > 0): 
            ?>
            <div style="background: #d1fae5; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; color: #166534;">
                 <strong><?php echo $nbConfirmees; ?> catégorie(s)</strong> avec stratégie confirmée, prête(s) à être validée(s)
            </div>
            <?php endif; ?>

            <div style="overflow-x: auto;">
                <table class="list-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="background: #1e3a8a; color: white;">Catégorie (Produit)</th>
                            <th style="background: #1e3a8a; color: white;">Quantité restante</th>
                            <th style="background: #1e3a8a; color: white;">Stratégie</th>
                            <th style="background: #1e3a8a; color: white;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr id="row-<?php echo $cat['id_produit']; ?>" 
                            style="<?php echo isset($strategiesConfirmees[$cat['id_produit']]) ? 'background: #f0fdf4;' : ''; ?>">
                            <td>
                                <?php if (isset($strategiesConfirmees[$cat['id_produit']])): ?>
                                    <span style="color: #10b981;">✓</span>
                                <?php endif; ?>
                                <strong><?php echo htmlspecialchars($cat['produit_nom']); ?></strong>
                            </td>
                            <td>
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: 600;">
                                    <?php echo number_format($cat['quantite_restante'], 0, ',', ' '); ?>
                                </span>
                                <span style="font-size: 0.85rem; color: #666;">
                                    (Total: <?php echo number_format($cat['quantite_totale'], 0, ',', ' '); ?> | 
                                    Attribué: <?php echo number_format($cat['quantite_attribuee'], 0, ',', ' '); ?>)
                                </span>
                            </td>
                            <td>
                                <select id="strategie-<?php echo $cat['id_produit']; ?>" 
                                        class="strategie-select"
                                        data-produit-id="<?php echo $cat['id_produit']; ?>"
                                        style="padding: 0.5rem; border-radius: 4px; border: 1px solid #d1d5db; min-width: 180px;">
                                    <option value="">-- Choisir stratégie --</option>
                                    <option value="date" <?php echo ($strategiesConfirmees[$cat['id_produit']] ?? '') === 'date' ? 'selected' : ''; ?>>
                                         Par date plus anciens
                                    </option>
                                    <option value="moins_besoins" <?php echo ($strategiesConfirmees[$cat['id_produit']] ?? '') === 'moins_besoins' ? 'selected' : ''; ?>>
                                         Moins de besoins d'abord
                                    </option>
                                    <option value="proportionnel" <?php echo ($strategiesConfirmees[$cat['id_produit']] ?? '') === 'proportionnel' ? 'selected' : ''; ?>>
                                         Proportionnel
                                    </option>
                                </select>
                            </td>
                            <td>
                                <button id="btn-confirm-<?php echo $cat['id_produit']; ?>" 
                                        class="btn-confirmer"
                                        data-produit-id="<?php echo $cat['id_produit']; ?>"
                                        style="background: #10b981; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; display: none;">
                                     Confirmer
                                </button>
                                <span id="status-<?php echo $cat['id_produit']; ?>" 
                                      style="color: #10b981; font-weight: 600; <?php echo isset($strategiesConfirmees[$cat['id_produit']]) ? '' : 'display: none;'; ?>">
                                    ✓ Confirmé
                                </span>
                            </td>
                        </tr>
                        <!-- Ligne d'aperçu -->
                        <tr id="preview-row-<?php echo $cat['id_produit']; ?>" style="display: none;">
                            <td colspan="4" style="padding: 0; background: #f9fafb;">
                                <div id="preview-content-<?php echo $cat['id_produit']; ?>" 
                                     style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 1rem; margin: 0.5rem;">
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div style="background: #f0fdf4; border: 2px solid #10b981; border-radius: 8px; padding: 2rem; text-align: center; margin-bottom: 2rem;">
            <p style="color: #166534; font-size: 1.2rem; margin: 0;">
                ✓ Tous les dons ont été distribués !
            </p>
        </div>
        <?php endif; ?>

        <!-- Résumé des allocations confirmées -->
        <?php if (!empty($preview['details'])): ?>
        <div style="background: #f0f9ff; border: 2px solid #0284c7; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="color: #0284c7; margin-top: 0;"> Résumé des allocations prêtes</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #10b981;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Allocations à créer</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: bold; color: #10b981;">
                        <?php echo count($preview['details']); ?>
                    </p>
                </div>
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #f59e0b;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Catégories confirmées</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: bold; color: #f59e0b;">
                        <?php echo count($strategiesConfirmees); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        

        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?php echo $baseUrl; ?>/dashboard" style="color: #1e3a8a; text-decoration: none; font-weight: 600;">
                ← Retour au Dashboard
            </a>
        </div>
    </div>
</section>

<style>
.list-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.list-table th, .list-table td {
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}
.list-table tbody tr:hover {
    background: #f9fafb;
}
.btn-confirmer:hover {
    background: #059669 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '<?php echo $baseUrl; ?>';
    
    // Gérer les changements de stratégie
    document.querySelectorAll('.strategie-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const idProduit = this.dataset.produitId;
            const strategie = this.value;
            const btnConfirm = document.getElementById('btn-confirm-' + idProduit);
            const previewRow = document.getElementById('preview-row-' + idProduit);
            const previewContent = document.getElementById('preview-content-' + idProduit);
            
            if (!strategie) {
                btnConfirm.style.display = 'none';
                previewRow.style.display = 'none';
                return;
            }
            
            // Afficher l'aperçu
            previewContent.innerHTML = '<p style="color: #666;">... Chargement de l\'aperçu...</p>';
            previewRow.style.display = '';
            
            fetch(baseUrl + '/api/preview-strategie', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_produit: parseInt(idProduit), strategie: strategie })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    let html = '<h4 style="margin: 0 0 0.75rem 0; color: #166534;"> Aperçu de la distribution</h4>';
                    
                    if (data.preview.length === 0) {
                        html += '<p style="color: #dc2626;"> Aucune allocation possible avec cette stratégie</p>';
                        btnConfirm.style.display = 'none';
                    } else {
                        html += '<div style="display: grid; gap: 0.5rem;">';
                        data.preview.forEach(function(item) {
                            html += '<div style="display: flex; justify-content: space-between; padding: 0.5rem; background: white; border-radius: 4px; border: 1px solid #d1fae5;">';
                            html += '<span style="color: #166534;"> ' + item.ville + ' (' + item.allocations + ' allocation(s))</span>';
                            html += '<span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: 700;">' + item.quantite.toLocaleString() + '</span>';
                            html += '</div>';
                        });
                        html += '</div>';
                        html += '<p style="margin: 0.75rem 0 0 0; color: #0284c7; font-weight: 600;">Total: ' + data.total_quantite.toLocaleString() + ' unités en ' + data.total_allocations + ' allocation(s)</p>';
                        btnConfirm.style.display = '';
                    }
                    
                    previewContent.innerHTML = html;
                } else {
                    previewContent.innerHTML = '<p style="color: #dc2626;">! Erreur: ' + data.message + '</p>';
                    btnConfirm.style.display = 'none';
                }
            })
            .catch(function(error) {
                previewContent.innerHTML = '<p style="color: #dc2626;">! Erreur de communication</p>';
                btnConfirm.style.display = 'none';
            });
        });
    });
    
    // Gérer les confirmations
    document.querySelectorAll('.btn-confirmer').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const idProduit = this.dataset.produitId;
            const select = document.getElementById('strategie-' + idProduit);
            const strategie = select.value;
            
            if (!strategie) {
                alert('Veuillez choisir une stratégie');
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '... Confirmation...';
            
            fetch(baseUrl + '/api/confirmer-strategie', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_produit: parseInt(idProduit), strategie: strategie })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    // Recharger la page pour voir les changements
                    location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                    btn.disabled = false;
                    btn.innerHTML = ' Confirmer';
                }
            })
            .catch(function(error) {
                alert('Erreur de communication');
                btn.disabled = false;
                btn.innerHTML = ' Confirmer';
            });
        });
    });
});
</script>
