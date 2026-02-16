<section class="list-section">
    <div class="list-container">
        <div class="list-header">
            <h2>Liste des Villes</h2>
            <a href="<?php echo Flight::get('flight.base_url'); ?>/villes/add" class="btn-add">Ajouter une ville</a>
        </div>

        <div class="list-content">
            <?php 
            use app\models\Ville;
            $villeModel = new Ville();
            $villes = $villeModel->getAllVilles();
            
            if (!empty($villes)): 
            ?>
                <table class="list-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Région</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($villes as $ville): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ville['id']); ?></td>
                                <td><?php echo htmlspecialchars($ville['nom']); ?></td>
                                <td><?php echo htmlspecialchars($ville['region_nom'] ?? 'N/A'); ?></td>
                                <td>
                                    <a href="<?php echo Flight::get('flight.base_url'); ?>/dashboard" class="btn-view">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>Aucune ville trouvée.</p>
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
        max-width: 1000px;
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
