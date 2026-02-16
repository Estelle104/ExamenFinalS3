-- ==========================================
-- BASE DE DONNÉES : TAKALO-TAKALO
-- ==========================================

DROP DATABASE IF EXISTS takalo;

CREATE DATABASE takalo
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE takalo;

-- ==========================================
-- 1. TYPES D’UTILISATEURS
-- ==========================================

CREATE TABLE type_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_user VARCHAR(50) NOT NULL
);

INSERT INTO type_user (type_user) VALUES
('admin'),
('user');

-- ==========================================
-- 2. UTILISATEURS
-- ==========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL,
    id_type_user INT NOT NULL,
    FOREIGN KEY (id_type_user) REFERENCES type_user(id)
);

-- Données de test pour les utilisateurs
-- INSERT INTO users (nom, login, mdp, id_type_user) VALUES
-- ('Administrateur', 'admin', 'admin', 1),
-- ('Utilisateur Test', 'user', 'user', 2);

-- ==========================================
-- 3. CATÉGORIES D’OBJETS
-- ==========================================

CREATE TABLE product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Données de test pour les catégories
-- INSERT INTO product_categories (name, description) VALUES
-- ('Électronique', 'Appareils électroniques et gadgets'),
-- ('Vêtements', 'Vêtements et accessoires'),
-- ('Livres', 'Livres et matériel de lecture');

-- ==========================================
-- 4. OBJETS (PRODUITS)
-- ==========================================

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price_estimated DECIMAL(10,2),
    id_category INT NOT NULL,
    id_owner INT NOT NULL,
    FOREIGN KEY (id_category) REFERENCES product_categories(id),
    FOREIGN KEY (id_owner) REFERENCES users(id)
);

-- ==========================================
-- 5. PHOTOS DES OBJETS
-- ==========================================

CREATE TABLE product_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_product INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_product) REFERENCES products(id)
);

-- ==========================================
-- 6. STATUTS
-- ==========================================

CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) NOT NULL
);

INSERT INTO status (status_name) VALUES
('en_attente'),
('accepte'),
('refuse');

-- ==========================================
-- 7. PROPOSITIONS D’ÉCHANGE
-- ==========================================

CREATE TABLE propositions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user_proposer INT NOT NULL,
    id_product_proposed INT NOT NULL,
    id_product_requested INT NOT NULL,
    date_proposition DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_status INT NOT NULL,
    FOREIGN KEY (id_user_proposer) REFERENCES users(id),
    FOREIGN KEY (id_product_proposed) REFERENCES products(id),
    FOREIGN KEY (id_product_requested) REFERENCES products(id),
    FOREIGN KEY (id_status) REFERENCES status(id)
);

-- ==========================================
-- 8. ÉCHANGES VALIDÉS
-- ==========================================

CREATE TABLE exchanges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user_1 INT NOT NULL,
    id_product_1 INT NOT NULL,
    id_user_2 INT NOT NULL,
    id_product_2 INT NOT NULL,
    date_exchange DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_status INT NOT NULL,
    FOREIGN KEY (id_user_1) REFERENCES users(id),
    FOREIGN KEY (id_product_1) REFERENCES products(id),
    FOREIGN KEY (id_user_2) REFERENCES users(id),
    FOREIGN KEY (id_product_2) REFERENCES products(id),
    FOREIGN KEY (id_status) REFERENCES status(id)
);

-- ==========================================
-- 9. HISTORIQUE DES PROPRIÉTAIRES
-- ==========================================

CREATE TABLE product_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_product INT NOT NULL,
    id_user INT NOT NULL,
    date_change DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_product) REFERENCES products(id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

-- ==========================================
-- FIN DU SCRIPT
-- ==========================================
