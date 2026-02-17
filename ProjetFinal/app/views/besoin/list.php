<?php 
    use app\models\Besoin;
    use app\models\Ville;
    use app\models\Produit;

    $besoinModel = new Besoin();
    $villeModel = new Ville();
    $produitModel = new Produit();

?>
<section class="list-section">
    <div class="list-container">
        <div class="list-header">
            <h2>Liste des Besoins</h2>
            <a href="<?php echo Flight::get('flight.base_url'); ?>/besoins/add" class="btn-add">Ajouter un besoin</a>
        </div>

        <div class="list-content">
            <?php 
            $besoins = $besoinModel->getAllBesoins();
            
            if (!empty($besoins)): 
            ?>
                <table class="list-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Produit</th>
                            <th>Ville</th>
                            <th>Quantité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($besoins as $besoin): ?>
                            <?php
                                $produit = $produitModel->getProduitById($besoin['id_produit']);
                                $ville = $villeModel->getVilleById($besoin['id_ville']);
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$besoin['id']); ?></td>
                                <td><?php echo htmlspecialchars($besoin['description']); ?></td>
                                <td><?php echo htmlspecialchars($produit['nom'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($ville['nom'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars((string)$besoin['quantite']); ?></td>
                                <td>
                                    <a href="<?php echo Flight::get('flight.base_url'); ?>/dashboard" class="btn-view">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>Aucun besoin trouvé.</p>
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