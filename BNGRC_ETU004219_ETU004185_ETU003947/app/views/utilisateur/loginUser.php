<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Connexion</title>
    <link rel="stylesheet" href="/tooplate-bistro-elegance.css">
    <link rel="stylesheet" href="/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section id="reservation">
        <div class="reservation">
            <div class="reservation-left">
                <h2>Login as User</h2>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <form class="loginAdmin-form" action="<?php echo Flight::get('flight.base_url'); ?>/loginUser" method="POST">
                    <div class="form-group">
                        <label for="login">User Name</label>
                        <input type="text" id="login" name="login" required>
                    </div>
                    <div class="form-group">
                        <label for="mdp">Password</label>
                        <input type="password" id="mdp" name="mdp" required>
                    </div>
                    <button type="submit" class="submit-btn-loginAdmin">Login</button>
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
                        <div class="soft-block" style="width: 50px; bottom: -270px; left: 400px; animation-delay: 3s; animation-duration: 25s;"></div>
                    </div>
                    <div class="static-decoration">
                        <div class="static-block-outline" style="width: 85px; height: 85px; top: 20px; right: 30px;"></div>
                        <div class="static-block" style="width: 120px; height: 120px; top: 80px; right: 120px;"></div>
                        <div class="static-block-outline" style="width: 100px; height: 100px; top: 140px; right: 50px;"></div>
                        <div class="static-block-outline" style="width: 40px; height: 40px; top: 50px; right: 180px;"></div>
                        <div class="static-block" style="width: 95px; height: 95px; top: 200px; right: 150px;"></div>
                        <div class="static-block-outline" style="width: 60px; height: 60px; top: 100px; right: 280px;"></div>
                        <div class="static-block-outline" style="width: 75px; height: 75px; top: 180px; right: 220px;"></div>
                        <div class="static-block-outline" style="width: 50px; height: 50px; top: 300px; right: 180px;"></div>
                        <div class="static-block" style="width: 90px; height: 90px; top: 60px; right: 320px;"></div>
                    </div>
                    <div class="bottom-right-decoration">
                        <div class="red-block" style="width: 65px; height: 65px; bottom: 20px; right: 40px;"></div>
                        <div class="red-block" style="width: 45px; height: 45px; bottom: 60px; right: 120px;"></div>
                        <div class="red-block" style="width: 85px; height: 85px; bottom: 120px; right: 60px;"></div>
                        <div class="red-block" style="width: 35px; height: 35px; bottom: 100px; right: 150px;"></div>
                        <div class="red-block-outline" style="width: 55px; height: 55px; bottom: 40px; right: 200px;"></div>
                        <div class="red-block-outline" style="width: 70px; height: 70px; bottom: 160px; right: 140px;"></div>
                    </div>
                    <div class="hero-content">
                        <h1>Don’t have an account? Sign up now! ⭐</h1>
                        <p>
                            <span class="text-option">Experience culinary excellence in an atmosphere of refined sophistication</span>
                            <span class="text-option">Discover exquisite flavors crafted with passion and precision</span>
                            <span class="text-option">Where fine dining meets unforgettable moments</span>
                        </p>
                        <a href="<?php echo Flight::get('flight.base_url'); ?>/registerForm" class="cta-btn-loginAdmin">Create User Account</a>
                    </div>
                </section>
            </div>
        </div>
    </section>
<script src="/tooplate-bistro-scripts.js"></script>
</body>
</html> 