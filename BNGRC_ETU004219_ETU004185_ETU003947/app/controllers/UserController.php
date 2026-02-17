<?php
namespace app\controllers;

use app\models\User;
use Flight;

class UserController {
    public function index() {
        // Redirection vers la liste des utilisateurs
        header('Location: ' . BASE_URL . '/users');
        exit();
    }

    public function list() {
        $userModel = new User();
        $users = $userModel->getAll();

        // Affichage de la liste des utilisateurs
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    // ========== MÉTHODES DE LOGIN ADMIN ==========
    
    public function loginAdminForm() {
        Flight::render('modele.php', [
            'contentPage' => '/admin/loginAdmin',
            'currentPage' => '/admin/loginAdmin',
            'pageTitle' => 'Login Admin - BNGRC'
        ]);
    }

    public function loginAdmin() {
        // Récupérer les données du formulaire
        $login = isset($_POST['login']) ? trim($_POST['login']) : '';
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';

        // Validation
        if (empty($login) || empty($mdp)) {
            $_SESSION['error'] = 'Le login et le mot de passe sont obligatoires';
            Flight::render('modele.php', [
                'contentPage' => 'admin/loginAdmin',
                'currentPage' => 'admin/loginAdmin',
                'pageTitle' => 'Login Admin - BNGRC'
            ]);
            return;
        }

        // Vérification des identifiants
        $userModel = new User();
        $user = $userModel->chequeLoginAdmin($login, $mdp);

        if ($user) {
            // Authentification réussie
            $_SESSION['user'] = $user;
            $_SESSION['user_type'] = 'admin';
            header('Location: ' . Flight::get('flight.base_url') . '/accueil');
            exit();
        } else {
            // Authentification échouée
            $_SESSION['error'] = 'Identifiants invalides';
            Flight::render('modele.php', [
                'contentPage' => 'admin/loginAdmin',
                'currentPage' => 'admin/loginAdmin',
                'pageTitle' => 'Login Admin - Takalo-Takalo'
            ]);
        }
    }

    // ========== MÉTHODES DE LOGIN UTILISATEUR ==========
    
    public function loginUserForm() {
        Flight::render('modele.php', [
            'contentPage' => 'utilisateur/loginUser',
            'currentPage' => 'utilisateur/loginUser',
            'pageTitle' => 'Login User - BNGRC'
        ]);
    }

    public function loginUser() {
        // Récupérer les données du formulaire
        $login = isset($_POST['login']) ? trim($_POST['login']) : '';
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';

        // Validation
        if (empty($login) || empty($mdp)) {
            $_SESSION['error'] = 'Le login et le mot de passe sont obligatoires';
            Flight::render('modele.php', [
                'contentPage' => 'utilisateur/loginUser',
                'currentPage' => 'utilisateur/loginUser',
                'pageTitle' => 'Login User - Takalo-Takalo'
            ]);
            return;
        }

        // Vérification des identifiants
        $userModel = new User();
        $user = $userModel->chequeLoginClient($login, $mdp);

        if ($user) {
            // Authentification réussie
            $_SESSION['user'] = $user;
            $_SESSION['user_type'] = 'user';
            header('Location: ' . Flight::get('flight.base_url') . '/accueil');
            exit();
        } else {
            // Authentification échouée
            $_SESSION['error'] = 'Identifiants invalides';
            Flight::render('modele.php', [
                'contentPage' => 'utilisateur/loginUser',
                'currentPage' => 'utilisateur/loginUser',
                'pageTitle' => 'Login User - Takalo-Takalo'
            ]);
        }
    }

    // ========== MÉTHODES D'INSCRIPTION ==========
    
    public function registerForm() {
        Flight::render('modele.php', [
            'contentPage' => 'utilisateur/registerForm',
            'currentPage' => 'utilisateur/registerForm',
            'pageTitle' => 'Register - BNGRC'
        ]);
    }

    public function register() {
        // Récupérer les données du formulaire
        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
        $login = isset($_POST['login']) ? trim($_POST['login']) : '';
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';
        $mdp_confirm = isset($_POST['mdp_confirm']) ? $_POST['mdp_confirm'] : '';

        // Validation
        $errors = [];

        if (empty($nom)) {
            $errors[] = 'Le nom est obligatoire';
        }
        if (empty($login)) {
            $errors[] = 'Le login est obligatoire';
        }
        if (empty($mdp)) {
            $errors[] = 'Le mot de passe est obligatoire';
        }
        if (empty($mdp_confirm)) {
            $errors[] = 'Vous devez confirmer votre mot de passe';
        }
        if ($mdp !== $mdp_confirm) {
            $errors[] = 'Les mots de passe ne correspondent pas';
        }
        if (strlen($login) < 3) {
            $errors[] = 'Le login doit contenir au moins 3 caractères';
        }
        if (strlen($mdp) < 3) {
            $errors[] = 'Le mot de passe doit contenir au moins 3 caractères';
        }

        // Vérifier que le login n'existe pas déjà
        $userModel = new User();
        $existingUser = $userModel->getUserByLogin($login);
        if ($existingUser) {
            $errors[] = 'Ce login est déjà utilisé';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            Flight::render('modele.php', [
                'contentPage' => 'utilisateur/registerForm',
                'currentPage' => 'utilisateur/registerForm',
                'pageTitle' => 'Register - Takalo-Takalo'
            ]);
            return;
        }

        // Inscription réussie
        $userId = $userModel->inscriptionClient($nom, $login, $mdp);

        if ($userId) {
            $_SESSION['success'] = 'Inscription réussie ! Vous pouvez maintenant vous connecter';
            header('Location: ' . Flight::get('flight.base_url') . '/loginUser');
            exit();
        } else {
            $_SESSION['error'] = 'Une erreur est survenue lors de l\'inscription';
            Flight::render('modele.php', [
                'contentPage' => 'utilisateur/registerForm',
                'currentPage' => 'utilisateur/registerForm',
                'pageTitle' => 'Register - Takalo-Takalo'
            ]);
        }
    }

    // ========== DÉCONNEXION ==========
    
    public function logout() {
        // Détruire la session
        $_SESSION = [];
        session_destroy();
        
        // Rediriger vers la page d'accueil
        header('Location: ' . Flight::get('flight.base_url') . '/loginAdmin');
        exit();
    }
}