DROP DATABASE IF EXISTS bngrc_final_s3;

CREATE DATABASE bngrc_final_s3;

USE bngrc_final_s3;

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

INSERT INTO users (nom, login, mdp, id_type_user) VALUES
('Administrateur', 'admin', 'admin123', 1),
('Jean Rakoto', 'jean', 'jean123', 2),
('Marie Rasoa', 'marie', 'marie123', 2),
('Paul Andry', 'paul', 'paul123', 2);


CREATE  TABLE regions(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(40) NOT NULL
);

CREATE  TABLE villes(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(40) NOT NULL,
    id_region INT
);

CREATE  TABLE categorie_produits(
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(40) NOT NULL,
    description VARCHAR(255) NOT NULL
);

CREATE  TABLE produits(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(40) NOT NULL,
    id_categorie INT,
    prix_unitaire DECIMAL(10,2)
);

CREATE  TABLE besoins(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(255) NOT NULL,
    id_produit INT,
    id_ville INT,
    id_region INT,
    quantite INT NOT NULL,
    date_besoin DATE
);


CREATE  TABLE dons(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(255) NOT NULL,
    id_produit INT,
    id_ville INT,
    id_region INT,
    quantite INT NOT NULL,
    date_don DATE,
    donneur VARCHAR(40) NOT NULL
);

CREATE TABLE dispatch (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_don INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_attribuee INT NOT NULL
);
