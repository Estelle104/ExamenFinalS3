<?php
$preview = $preview ?? [];
$baseUrl = Flight::get('flight.base_url');
?>

<section class="list-section">
    <div class="list-container">
        <div class="list-header">
            <h2> Aperçu de la Simulation</h2>
        </div>

        <div style="background: #f0f9ff; border: 2px solid #0284c7; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="color: #0284c7; margin-top: 0;">Résumé de l'allocation des dons</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #10b981;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Allocations créées</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: bold; color: #10b981;">
                        <?php echo $preview['dispatch_crees'] ?? 0; ?>
                    </p>
                </div>
                
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #f59e0b;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Dons traités</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: bold; color: #f59e0b;">
                        <?php echo $preview['dons_traite'] ?? 0; ?>
                    </p>
                </div>
            </div>
        </div>

        <?php if (!empty($preview['details'])): ?>
            <div style="margin-bottom: 2rem;">
                <h3 style="color: #1e3a8a; margin-bottom: 1rem;">Détails des allocations</h3>
                
                <div style="overflow-x: auto;">
                    <table class="list-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID Don</th>
                                <th>ID Besoin</th>
                                <th>Quantité allouée</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preview['details'] as $index => $detail): ?>
                                <tr>
                                    <td><?php echo $detail['id_don']; ?></td>
                                    <td><?php echo $detail['id_besoin']; ?></td>
                                    <td>
                                        <span style="background: #dbeafe; color: #1e3a8a; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: 600;">
                                            <?php echo $detail['quantite']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div style="background: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; padding: 1.5rem; text-align: center;">
                <p style="color: #92400e; font-size: 1rem; margin: 0;">
                    ⚠️ Aucune allocation à effectuer selon la simulation.
                </p>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo $baseUrl; ?>/simulate-valider" 
               style="background: #10b981; color: white; padding: 0.75rem 2rem; border: none; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
               onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'"
               onmouseout="this.style.background='#10b981'; this.style.transform='translateY(0)'">
                 Valider réellement
            </a>
            
            <a href="<?php echo $baseUrl; ?>/simulate-annuler" 
               style="background: #ef4444; color: white; padding: 0.75rem 2rem; border: none; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
               onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-2px)'"
               onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'">
                 Annuler
            </a>
        </div>

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
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.list-table thead {
    background: #1e3a8a;
    color: white;
}

.list-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
}

.list-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.list-table tbody tr:hover {
    background: #f8fafc;
}

.list-table tbody tr:last-child td {
    border-bottom: none;
}
</style>
