<?php

use app\models\Region;

$regionModel = new Region();
$regions = $regionModel->getAllRegions();
?>

<section id="reservation">
    <div class="reservation">
        <div class="reservation-left">
            <h2>Ajouter une Région</h2>
            
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

            <form class="loginAdmin-form" action="<?php echo Flight::get('flight.base_url'); ?>/regions/add" method="POST">
                <div class="form-group">
                    <label for="nom">Nom de la région</label>
                    <input type="text" id="nom" name="nom" required placeholder="Ex: Atsinanana">
                </div>

                <button type="submit" class="submit-btn-loginAdmin">Ajouter</button>
                <a href="<?php echo Flight::get('flight.base_url'); ?>/regions" style="text-align: center; display: block; margin-top: 1rem; text-decoration: none; color: #666;">Retour</a>
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
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        font-family: inherit;
    }
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #e74c3c;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    }
</style>
