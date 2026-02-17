<?php
$detailsParProduit = $detailsParProduit ?? [];
$baseUrl = Flight::get('flight.base_url');
?>

<section class="list-section">
    <div class="list-container">
        <div class="list-header">
            <h2> Détails des Besoins et Dons par Produit</h2>
            <a href="<?php echo $baseUrl; ?>/dashboard" class="btn-back" style="background: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                ← Retour au Dashboard
            </a>
        </div>

        <!-- Résumé global -->
        <div style="background: #f0f9ff; border: 2px solid #0284c7; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="color: #0284c7; margin-top: 0;">Résumé Global</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <?php
                $totalBesoinsGlobal = array_sum(array_column($detailsParProduit, 'total_besoins'));
                $totalSatisfaitGlobal = array_sum(array_column($detailsParProduit, 'total_satisfait'));
                $totalDonsGlobal = array_sum(array_column($detailsParProduit, 'total_dons'));
                $pourcentageGlobal = $totalBesoinsGlobal > 0 ? round(($totalSatisfaitGlobal / $totalBesoinsGlobal) * 100, 2) : 0;
                ?>
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #ef4444;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Total Besoins</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.5rem; font-weight: bold; color: #ef4444;">
                        <?php echo number_format($totalBesoinsGlobal, 0, ',', ' '); ?>
                    </p>
                </div>
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #10b981;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Total Satisfait</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.5rem; font-weight: bold; color: #10b981;">
                        <?php echo number_format($totalSatisfaitGlobal, 0, ',', ' '); ?>
                    </p>
                </div>
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #3b82f6;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Total Dons disponibles</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.5rem; font-weight: bold; color: #3b82f6;">
                        <?php echo number_format($totalDonsGlobal, 0, ',', ' '); ?>
                    </p>
                </div>
                <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 4px solid #f59e0b;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Couverture globale</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.5rem; font-weight: bold; color: #f59e0b;">
                        <?php echo $pourcentageGlobal; ?>%
                    </p>
                </div>
            </div>
        </div>

        <!-- Détails par produit -->
        <?php foreach ($detailsParProduit as $detail): ?>
        <?php if ($detail['total_besoins'] > 0 || $detail['total_dons'] > 0): ?>
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 1.5rem; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <!-- En-tête du produit -->
            <div style="background: #1e3a8a; color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.2rem;">
                    <?php echo htmlspecialchars($detail['produit']['nom']); ?>
                </h3>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.9rem;">
                        <?php echo $detail['nb_besoins']; ?> besoin(s)
                    </span>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.9rem;">
                        <?php echo $detail['nb_dons']; ?> don(s)
                    </span>
                </div>
            </div>
            
            <!-- Statistiques du produit -->
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                    <div style="text-align: center; padding: 0.75rem; background: #fef2f2; border-radius: 6px;">
                        <p style="margin: 0; color: #991b1b; font-size: 0.85rem;">Besoins totaux</p>
                        <p style="margin: 0.25rem 0 0 0; font-size: 1.3rem; font-weight: bold; color: #dc2626;">
                            <?php echo number_format($detail['total_besoins'], 0, ',', ' '); ?>
                        </p>
                    </div>
                    <div style="text-align: center; padding: 0.75rem; background: #f0fdf4; border-radius: 6px;">
                        <p style="margin: 0; color: #166534; font-size: 0.85rem;">Satisfaits</p>
                        <p style="margin: 0.25rem 0 0 0; font-size: 1.3rem; font-weight: bold; color: #16a34a;">
                            <?php echo number_format($detail['total_satisfait'], 0, ',', ' '); ?>
                        </p>
                    </div>
                    <div style="text-align: center; padding: 0.75rem; background: #fef3c7; border-radius: 6px;">
                        <p style="margin: 0; color: #92400e; font-size: 0.85rem;">Restants</p>
                        <p style="margin: 0.25rem 0 0 0; font-size: 1.3rem; font-weight: bold; color: #d97706;">
                            <?php echo number_format($detail['total_restant'], 0, ',', ' '); ?>
                        </p>
                    </div>
                    <div style="text-align: center; padding: 0.75rem; background: #eff6ff; border-radius: 6px;">
                        <p style="margin: 0; color: #1e40af; font-size: 0.85rem;">Dons disponibles</p>
                        <p style="margin: 0.25rem 0 0 0; font-size: 1.3rem; font-weight: bold; color: #2563eb;">
                            <?php echo number_format($detail['total_dons'], 0, ',', ' '); ?>
                        </p>
                    </div>
                </div>
                
                <!-- Barre de progression -->
                <div style="margin-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                        <span style="font-size: 0.85rem; color: #666;">Progression</span>
                        <span style="font-size: 0.85rem; font-weight: 600; color: <?php echo $detail['pourcentage'] >= 100 ? '#16a34a' : ($detail['pourcentage'] > 0 ? '#d97706' : '#dc2626'); ?>">
                            <?php echo $detail['pourcentage']; ?>%
                        </span>
                    </div>
                    <div style="background: #e5e7eb; border-radius: 4px; height: 10px; overflow: hidden;">
                        <div style="background: <?php echo $detail['pourcentage'] >= 100 ? '#16a34a' : ($detail['pourcentage'] > 0 ? '#f59e0b' : '#ef4444'); ?>; height: 100%; width: <?php echo min($detail['pourcentage'], 100); ?>%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Détails par ville -->
            <?php if (!empty($detail['besoins_par_ville'])): ?>
            <div style="padding: 1rem 1.5rem;">
                <h4 style="margin: 0 0 0.75rem 0; color: #374151; font-size: 0.95rem;"> Répartition par ville</h4>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                        <thead>
                            <tr style="background: #f3f4f6;">
                                <th style="padding: 0.5rem; text-align: left; border-bottom: 2px solid #e5e7eb;">Ville</th>
                                <th style="padding: 0.5rem; text-align: right; border-bottom: 2px solid #e5e7eb;">Besoins</th>
                                <th style="padding: 0.5rem; text-align: right; border-bottom: 2px solid #e5e7eb;">Satisfait</th>
                                <th style="padding: 0.5rem; text-align: right; border-bottom: 2px solid #e5e7eb;">Restant</th>
                                <th style="padding: 0.5rem; text-align: center; border-bottom: 2px solid #e5e7eb;">État</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail['besoins_par_ville'] as $bv): ?>
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid #e5e7eb; font-weight: 500;">
                                    <?php echo htmlspecialchars($bv['ville']); ?>
                                </td>
                                <td style="padding: 0.5rem; text-align: right; border-bottom: 1px solid #e5e7eb;">
                                    <?php echo number_format($bv['quantite'], 0, ',', ' '); ?>
                                </td>
                                <td style="padding: 0.5rem; text-align: right; border-bottom: 1px solid #e5e7eb; color: #16a34a; font-weight: 600;">
                                    <?php echo number_format($bv['satisfait'], 0, ',', ' '); ?>
                                </td>
                                <td style="padding: 0.5rem; text-align: right; border-bottom: 1px solid #e5e7eb; color: #d97706;">
                                    <?php echo number_format($bv['restant'], 0, ',', ' '); ?>
                                </td>
                                <td style="padding: 0.5rem; text-align: center; border-bottom: 1px solid #e5e7eb;">
                                    <?php if ($bv['restant'] <= 0): ?>
                                        <span style="background: #d1fae5; color: #166534; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;"> Satisfait</span>
                                    <?php elseif ($bv['satisfait'] > 0): ?>
                                        <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;"> Partiel</span>
                                    <?php else: ?>
                                        <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;"> En attente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>

        <!-- Bouton retour -->
        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?php echo $baseUrl; ?>/dashboard" style="background: #1e3a8a; color: white; padding: 0.75rem 2rem; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block;">
                ← Retour au Dashboard
            </a>
            <a href="<?php echo $baseUrl; ?>/simulate" style="background: #10b981; color: white; padding: 0.75rem 2rem; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block; margin-left: 1rem;">
                Simuler l'allocation
            </a>
        </div>
    </div>
</section>

<style>
.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.list-header h2 {
    margin: 0;
    color: #1e3a8a;
}
</style>
