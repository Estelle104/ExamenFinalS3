<?php

use app\models\Ville;
use app\models\Produit;
use app\models\Region;

$villeModel = new Ville();
$produitModel = new Produit();
$regionModel = new Region();

$villes = $villeModel->getAllVilles();
$produits = $produitModel->getAllProduits();
$regions = $regionModel->getAllRegions();
?>

<section id="reservation">
    <div class="reservation">
        <div class="reservation-left">
            <h2>Ajouter un Besoin</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="margin-bottom: 20px;">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form class="loginAdmin-form" action="<?php echo Flight::get('flight.base_url'); ?>/besoins/add" method="POST">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" required placeholder="Ex: Besoin de riz">
                </div>

                <div class="form-group">
                    <label for="id_produit">Produit</label>
                    <select id="id_produit" name="id_produit" required>
                        <option value="">Sélectionner un produit</option>
                        <?php foreach ($produits as $produit): ?>
                            <option value="<?php echo $produit['id']; ?>">
                                <?php echo htmlspecialchars($produit['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_region">Région <span style="color: #999; font-size: 0.9rem;">(optionnel)</span></label>
                    <select id="id_region" name="id_region">
                        <option value="">Sélectionner une région</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?php echo $region['id']; ?>">
                                <?php echo htmlspecialchars($region['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #999;">Ou sélectionner une ville ci-dessous</small>
                </div>

                <div class="form-group">
                    <label for="id_ville">Ville <span style="color: #999; font-size: 0.9rem;">(optionnel)</span></label>
                    <select id="id_ville" name="id_ville">
                        <option value="">Sélectionner une ville</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?php echo $ville['id']; ?>">
                                <?php echo htmlspecialchars($ville['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #999;">Ou sélectionner une région ci-dessus</small>
                </div>

                <div class="form-group">
                    <label for="quantite">Quantité</label>
                    <input type="number" id="quantite" name="quantite" required placeholder="Ex: 500" min="1">
                </div>

                <button type="submit" class="submit-btn-loginAdmin">Ajouter</button>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/besoins" style="text-align: center; display: block; margin-top: 1rem; text-decoration: none; color: #666;">Retour</a>
            </form>
        </div>
        <div class="reservation-right">
            <section class="heroAdmin">
                <div class="diagonal-grid">
                    <div class="soft-block" style="width: 80px; bottom: -400px; left: -100px; animation-delay: 0s; animation-duration: 22s;"></div>
                    <div class="soft-block" style="width: 60px; bottom: -300px; left: 100px; animation-delay: 2s; animation-duration: 20s;"></div>
                    <div class="soft-block" style="width: 100px; bottom: -370px; left: 350px; animation-delay: 1s; animation-duration: 24s;"></div>
                    <div class="soft-block" style="width: 70px; bottom: -230px; left: 200px; animation-delay: 1.5s; animation-duration: 21s;"></div>
                    <div class="soft-block" style="width: 90px; bottom: -170px; left: 500px; animation-delay: 0.5s; animation-duration: 23s;"></div>
                </div>
                <div class="static-decoration">
                    <div class="static-block-outline" style="width: 85px; height: 85px; top: 20px; right: 30px;"></div>
                    <div class="static-block" style="width: 120px; height: 120px; top: 80px; right: 120px;"></div>
                    <div class="static-block-outline" style="width: 100px; height: 100px; top: 140px; right: 50px;"></div>
                    <div class="static-block" style="width: 95px; height: 95px; top: 200px; right: 150px;"></div>
                </div>
                <div class="bottom-right-decoration">
                    <div class="red-block" style="width: 65px; height: 65px; bottom: 20px; right: 40px;"></div>
                    <div class="red-block" style="width: 45px; height: 45px; bottom: 60px; right: 120px;"></div>
                    <div class="red-block" style="width: 85px; height: 85px; bottom: 120px; right: 60px;"></div>
                </div>
            </section>
        </div>
    </div>
</section>

<style>
    .alert {
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
    }
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #1e3a8a;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #fbbf24;
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.15);
    }
    .form-group input:hover,
    .form-group select:hover {
        border-color: #1e3a8a;
    }
    /* Feedback interactif */
    #achat-feedback {
        font-size: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        padding: 0.75rem 1rem;
        min-height: 30px;
        color: #1e3a8a;
        border-left: 4px solid #fbbf24;
    }
    
    /* Style supplémentaire pour cohérence */
    .form-group.required label::after {
        content: " *";
        color: #f59e0b;
        font-weight: bold;
    }
    
    .btn-submit {
        background: #1e3a8a;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        background: #2d4ec0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }
    
    .btn-submit:active {
        transform: translateY(0);
    }
    
    .hint-text {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.25rem;
        display: block;
    }
    
    .hint-text i {
        color: #f59e0b;
        margin-right: 0.25rem;
    }
</style>

