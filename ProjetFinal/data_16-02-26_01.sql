USE bngrc_final_s3;

-- Régions
INSERT INTO regions (nom) VALUES
('Atsinanana'),
('Analamanga');

-- Villes
INSERT INTO villes (nom, id_region) VALUES
('Toamasina', 1),
('Brickaville', 1),
('Antananarivo', 2),
('Ambohidratrimo', 2),
('Ankazobe', 2);

-- Catégories de produits
INSERT INTO categorie_produits (libelle, description) VALUES
('Naturel', 'Produits de consommation alimentaire'),
('Matériel', 'Matériaux pour réparation'),
('Financier', 'Aide en argent');

-- Produits
INSERT INTO produits (nom, id_categorie, prix_unitaire) VALUES
('Riz', 1, 2500.00),
('Huile', 1, 6000.00),
('Tôle', 2, 35000.00),
('Clous (kg)', 2, 8000.00),
('Argent', 3, 1.00);

-- Besoins
INSERT INTO besoins (description, id_produit, id_ville, quantite) VALUES
('Besoin de riz', 1, 1, 500),
('Manque d’huile', 2, 1, 200),
('Besoin de tôles', 3, 2, 100),
('Réparation infrastructure', 3, 3, 50),
('Besoin de clous pour reconstruction', 4, 4, 80),
('Aide financière pour familles vulnérables', 5, 5, 1000000);

-- Dons (avec id_ville ajouté)
INSERT INTO dons (description, id_produit, id_ville, quantite, date_don, donneur) VALUES
('Don de riz ONG locale', 1, 1, 300, '2026-02-15', 'ONG Fanantenana'),
('Don d’huile société privée', 2, 1, 150, '2026-02-15', 'STAR Madagascar'),
('Don de tôles diaspora', 3, 2, 70, '2026-02-16', 'Diaspora France'),
('Don financier entreprise', 5, 5, 500000, '2026-02-16', 'Telma Madagascar'),
('Don de clous association', 4, 4, 40, '2026-02-17', 'Association Tanora');
