USE bngrc_final_s3;

-- ============================================================
-- ÉTAPE 1: Ajouter les régions
-- ============================================================
INSERT INTO regions (nom) VALUES
('Atsinanana'),
('Analamanga');

-- ============================================================
-- ÉTAPE 2: Ajouter les villes (id_region: 1=Atsinanana, 2=Analamanga)
-- ============================================================
INSERT INTO villes (nom, id_region) VALUES
('Toamasina', 1),
('Mananjary', 1),
('Farafangana', 1),
('Nosy Be', 1),
('Morondava', 2);

-- ============================================================
-- ÉTAPE 3: Catégories de produits
-- ============================================================
INSERT INTO categorie_produits (libelle, description) VALUES
('Naturel', 'Produits de consommation alimentaire'),
('Matériel', 'Matériaux pour réparation'),
('Financier', 'Aide en argent');

-- ============================================================
-- ÉTAPE 4: Produits avec prix unitaire (id_categorie: 1=Naturel, 2=Matériel, 3=Financier)
-- ============================================================
INSERT INTO produits (nom, id_categorie, prix_unitaire) VALUES
('Riz (kg)', 1, 3000.00),
('Eau (L)', 1, 1000.00),
('Huile (L)', 1, 6000.00),
('Haricots', 1, 4000.00),
('Tôle', 2, 25000.00),
('Bâche', 2, 15000.00),
('Clous (kg)', 2, 8000.00),
('Bois', 2, 10000.00),
('Groupe', 2, 6750000.00),
('Argent', 3, 1.00);

-- ============================================================
-- ÉTAPE 5: Besoins triés par Ordre
-- Villes: 1=Toamasina, 2=Mananjary, 3=Farafangana, 4=Nosy Be, 5=Morondava
-- Produits: 1=Riz, 2=Eau, 3=Huile, 4=Haricots, 5=Tôle, 6=Bâche, 7=Clous, 8=Bois, 9=Groupe, 10=Argent
-- ============================================================
INSERT INTO besoins (description, id_produit, id_ville, quantite, quantite_restante, etat, date_besoin) VALUES
-- Ordre 1: Toamasina - Bâche - 200
('Besoin Bâche Toamasina', 6, 1, 200, 200, 'En attente', '2026-02-15'),
-- Ordre 2: Nosy Be - Tôle - 40
('Besoin Tôle Nosy Be', 5, 4, 40, 40, 'En attente', '2026-02-15'),
-- Ordre 3: Mananjary - Argent - 6000000
('Besoin Argent Mananjary', 10, 2, 6000000, 6000000, 'En attente', '2026-02-15'),
-- Ordre 4: Toamasina - Eau (L) - 1500
('Besoin Eau Toamasina', 2, 1, 1500, 1500, 'En attente', '2026-02-15'),
-- Ordre 5: Nosy Be - Riz (kg) - 300
('Besoin Riz Nosy Be', 1, 4, 300, 300, 'En attente', '2026-02-15'),
-- Ordre 6: Mananjary - Tôle - 80
('Besoin Tôle Mananjary', 5, 2, 80, 80, 'En attente', '2026-02-15'),
-- Ordre 7: Nosy Be - Argent - 4000000
('Besoin Argent Nosy Be', 10, 4, 4000000, 4000000, 'En attente', '2026-02-15'),
-- Ordre 8: Farafangana - Bâche - 150
('Besoin Bâche Farafangana', 6, 3, 150, 150, 'En attente', '2026-02-16'),
-- Ordre 9: Mananjary - Riz (kg) - 500
('Besoin Riz Mananjary', 1, 2, 500, 500, 'En attente', '2026-02-15'),
-- Ordre 10: Farafangana - Argent - 8000000
('Besoin Argent Farafangana', 10, 3, 8000000, 8000000, 'En attente', '2026-02-16'),
-- Ordre 11: Morondava - Riz (kg) - 700
('Besoin Riz Morondava', 1, 5, 700, 700, 'En attente', '2026-02-16'),
-- Ordre 12: Toamasina - Argent - 12000000
('Besoin Argent Toamasina', 10, 1, 12000000, 12000000, 'En attente', '2026-02-16'),
-- Ordre 13: Morondava - Argent - 10000000
('Besoin Argent Morondava', 10, 5, 10000000, 10000000, 'En attente', '2026-02-16'),
-- Ordre 14: Farafangana - Eau (L) - 1000
('Besoin Eau Farafangana', 2, 3, 1000, 1000, 'En attente', '2026-02-15'),
-- Ordre 15: Morondava - Bâche - 180
('Besoin Bâche Morondava', 6, 5, 180, 180, 'En attente', '2026-02-16'),
-- Ordre 16: Toamasina - Groupe - 3
('Besoin Groupe Toamasina', 9, 1, 3, 3, 'En attente', '2026-02-15'),
-- Ordre 17: Toamasina - Riz (kg) - 800
('Besoin Riz Toamasina', 1, 1, 800, 800, 'En attente', '2026-02-16'),
-- Ordre 18: Nosy Be - Haricots - 200
('Besoin Haricots Nosy Be', 4, 4, 200, 200, 'En attente', '2026-02-16'),
-- Ordre 19: Mananjary - Clous (kg) - 60
('Besoin Clous Mananjary', 7, 2, 60, 60, 'En attente', '2026-02-16'),
-- Ordre 20: Morondava - Eau (L) - 1200
('Besoin Eau Morondava', 2, 5, 1200, 1200, 'En attente', '2026-02-15'),
-- Ordre 21: Farafangana - Riz (kg) - 600
('Besoin Riz Farafangana', 1, 3, 600, 600, 'En attente', '2026-02-16'),
-- Ordre 22: Morondava - Bois - 150
('Besoin Bois Morondava', 8, 5, 150, 150, 'En attente', '2026-02-15'),
-- Ordre 23: Toamasina - Tôle - 120
('Besoin Tôle Toamasina', 5, 1, 120, 120, 'En attente', '2026-02-16'),
-- Ordre 24: Nosy Be - Clous (kg) - 30
('Besoin Clous Nosy Be', 7, 4, 30, 30, 'En attente', '2026-02-16'),
-- Ordre 25: Mananjary - Huile (L) - 120
('Besoin Huile Mananjary', 3, 2, 120, 120, 'En attente', '2026-02-16'),
-- Ordre 26: Farafangana - Bois - 100
('Besoin Bois Farafangana', 8, 3, 100, 100, 'En attente', '2026-02-15');

-- ===============================
-- DONS ARGENT
-- ===============================

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Argent', id, NULL, NULL, 5000000, '2026-02-16', 'Don anonyme'
FROM produits WHERE nom = 'Argent';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Argent', id, NULL, NULL, 3000000, '2026-02-16', 'Don anonyme'
FROM produits WHERE nom = 'Argent';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Argent', id, NULL, NULL, 4000000, '2026-02-17', 'Don anonyme'
FROM produits WHERE nom = 'Argent';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Argent', id, NULL, NULL, 1500000, '2026-02-17', 'Don anonyme'
FROM produits WHERE nom = 'Argent';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Argent', id, NULL, NULL, 6000000, '2026-02-17', 'Don anonyme'
FROM produits WHERE nom = 'Argent';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Argent', id, NULL, NULL, 2000000, '2026-02-19', 'Don anonyme'
FROM produits WHERE nom = 'Argent';


-- ===============================
-- DONS NATURE
-- ===============================

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Riz', id, NULL, NULL, 400, '2026-02-16', 'Don anonyme'
FROM produits WHERE nom = 'Riz (kg)';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Eau', id, NULL, NULL, 600, '2026-02-16', 'Don anonyme'
FROM produits WHERE nom = 'Eau (L)';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Haricots', id, NULL, NULL, 100, '2026-02-17', 'Don anonyme'
FROM produits WHERE nom = 'Haricots';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Riz', id, NULL, NULL, 2000, '2026-02-18', 'Don anonyme'
FROM produits WHERE nom = 'Riz (kg)';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Eau', id, NULL, NULL, 5000, '2026-02-18', 'Don anonyme'
FROM produits WHERE nom = 'Eau (L)';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Haricots', id, NULL, NULL, 88, '2026-02-17', 'Don anonyme'
FROM produits WHERE nom = 'Haricots';


-- ===============================
-- DONS MATERIEL
-- ===============================

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Tôle', id, NULL, NULL, 50, '2026-02-17', 'Don anonyme'
FROM produits WHERE nom = 'Tôle';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Bâche', id, NULL, NULL, 70, '2026-02-17', 'Don anonyme'
FROM produits WHERE nom = 'Bâche';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Tôle', id, NULL, NULL, 300, '2026-02-18', 'Don anonyme'
FROM produits WHERE nom = 'Tôle';

INSERT INTO dons (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
SELECT 'Don Bâche', id, NULL, NULL, 500, '2026-02-19', 'Don anonyme'
FROM produits WHERE nom = 'Bâche';