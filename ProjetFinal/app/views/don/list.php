<section class="list-section">
    <div class="list-container">
        <div class="list-header">
            <h2>Liste des Dons</h2>
            <a href="<?php echo Flight::get('flight.base_url'); ?>/dons/add" class="btn-add">Ajouter un don</a>
        </div>

        <div class="list-content">
            <?php 
            use app\models\Don;
            $donModel = new Don();
            $dons = $donModel->getAllDons();
            
            if (!empty($dons)): 
            ?>
                <table class="list-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Produit</th>
                            <th>Ville</th>
                            <th>Quantité</th>
                            <th>Donneur</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dons as $don): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$don['id']); ?></td>
                                <td><?php echo htmlspecialchars($don['description']); ?></td>
                                <td><?php echo htmlspecialchars($don['produit_nom'] ?? $don['id_produit'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($don['ville_nom'] ?? $don['id_ville'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars((string)$don['quantite']); ?></td>
                                <td><?php echo htmlspecialchars($don['donneur']); ?></td>
                                <td><?php echo htmlspecialchars($don['date_don'] ?? 'N/A'); ?></td>
                                <td>
                                    <a href="<?php echo Flight::get('flight.base_url'); ?>/dashboard" class="btn-view">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>Aucun don trouvé.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .list-section {
        padding: 20px;
        background: #f8f9fa;
        min-height: 80vh;
    }

    .list-container {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem;
        border-bottom: 2px solid #e74c3c;
    }

    .list-header h2 {
        color: #e74c3c;
        margin: 0;
    }

    .btn-add {
        background: #e74c3c;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-add:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .list-content {
        padding: 2rem;
        overflow-x: auto;
    }

    .list-table {
        width: 100%;
        border-collapse: collapse;
    }

    .list-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .list-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        font-size: 0.95rem;
    }

    .list-table tbody tr {
        border-bottom: 1px solid #e9ecef;
    }

    .list-table tbody tr:hover {
        background: #f8f9fa;
    }

    .list-table td {
        padding: 1rem;
        color: #555;
        font-size: 0.95rem;
    }

    .btn-view {
        background: #e74c3c;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s;
        display: inline-block;
    }

    .btn-view:hover {
        background: #c0392b;
    }

    .no-data {
        text-align: center;
        padding: 3rem;
        color: #999;
    }
</style>
