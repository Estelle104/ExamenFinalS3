DROP DATABASE IF EXISTS bngrc_final_s3;

CREATE DATABASE bngrc_final_s3;

USE bngrc_final_s3;

CREATE OR REPLACE TABLE regions(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(40) NOT NULL
);

CREATE OR REPLACE TABLE villes(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(40) NOT NULL,
    id_region INT
);

CREATE OR REPLACE TABLE categorie_produits(
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(40) NOT NULL,
    description VARCHAR(255) NOT NULL
);

CREATE OR REPLACE TABLE produits(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(40) NOT NULL,
    id_categorie INT,
    prix_unitaire DECIMAL(10,2)
);

CREATE OR REPLACE TABLE besoins(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(255) NOT NULL,
    id_produit INT,
    id_ville INT,
    quantite INT NOT NULL,
    date_besoin DATE
);


CREATE OR REPLACE TABLE dons(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(255) NOT NULL,
    id_produit INT,
    id_ville INT,
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
