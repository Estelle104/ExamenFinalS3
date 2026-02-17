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

// Récupérer le don passé par le contrôleur
$don = Flight::get('don') ?? $don ?? [];
?>

<section id="reservation">
    <div class="reservation">
        <div class="reservation-left">
            <h2>Modifier le Don #<?php echo htmlspecialchars((string)($don['id'] ?? '')); ?></h2>
            
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

            <form class="loginAdmin-form" action="<?php echo Flight::get('flight.base_url'); ?>/dons/edit/<?php echo $don['id']; ?>" method="POST">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" required 
                           value="<?php echo htmlspecialchars($don['description'] ?? ''); ?>"
                           placeholder="Ex: Don de riz ONG locale">
                </div>

                <div class="form-group">
                    <label for="id_produit">Produit</label>
                    <select id="id_produit" name="id_produit" required>
                        <option value="">Sélectionner un produit</option>
                        <?php foreach ($produits as $produit): ?>
                            <option value="<?php echo $produit['id']; ?>" 
                                    <?php echo ($don['id_produit'] ?? '') == $produit['id'] ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $region['id']; ?>"
                                    <?php echo ($don['id_region'] ?? '') == $region['id'] ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $ville['id']; ?>"
                                    <?php echo ($don['id_ville'] ?? '') == $ville['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ville['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #999;">Ou sélectionner une région ci-dessus</small>
                </div>

                <div class="form-group">
                    <label for="quantite">Quantité</label>
                    <input type="number" id="quantite" name="quantite" required 
                           value="<?php echo htmlspecialchars((string)($don['quantite'] ?? '')); ?>"
                           placeholder="Ex: 300" min="1">
                </div>

                <div class="form-group">
                    <label for="date_don">Date du don</label>
                    <input type="date" id="date_don" name="date_don" 
                           value="<?php echo htmlspecialchars($don['date_don'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="donneur">Donneur</label>
                    <input type="text" id="donneur" name="donneur" required 
                           value="<?php echo htmlspecialchars($don['donneur'] ?? ''); ?>"
                           placeholder="Ex: ONG Fanantenana">
                </div>

                <button type="submit" class="submit-btn-loginAdmin">💾 Enregistrer les modifications</button>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/dons" style="text-align: center; display: block; margin-top: 1rem; text-decoration: none; color: #666;">← Retour à la liste</a>
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
</style>
