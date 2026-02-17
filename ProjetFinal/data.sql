INSERT INTO regions (nom) VALUES
('Analamanga'),
('Atsinanana'),
('Vakinankaratra');

INSERT INTO villes (nom, id_region) VALUES
('Antananarivo', 1),
('Ambohidratrimo', 1),
('Toamasina', 2),
('Brickaville', 2),
('Antsirabe', 3);

INSERT INTO produits (nom, id_categorie, prix_unitaire) VALUES
('Riz', 1, 2500.00),
('Huile', 1, 6000.00),
('Tôle', 2, 35000.00),
('Clous (kg)', 2, 8000.00),
('Argent', 3, 1.00);

INSERT INTO besoins 
(description, id_produit, id_ville, id_region, quantite, quantite_restante, etat, date_besoin)
VALUES
('Riz pour familles sinistrées', 1, 3, 2, 500, 500, 'En attente', '2026-02-10'),
('Huile pour distribution alimentaire', 2, 3, 2, 200, 200, 'En attente', '2026-02-10'),
('Tôles pour reconstruction maisons', 3, 4, 2, 100, 100, 'En attente', '2026-02-11'),
('Clous pour réparation toitures', 4, 1, 1, 150, 150, 'En attente', '2026-02-12'),
('Riz pour centre d’accueil', 1, 5, 3, 300, 300, 'En attente', '2026-02-12');

INSERT INTO dons 
(description, id_produit, id_ville, id_region, quantite, date_don, donneur)
VALUES
('Don en argent ONG A', 5, 3, 2, 2000000, '2026-02-13', 'ONG Solidarité'),
('Don en argent Entreprise B', 5, 1, 1, 1500000, '2026-02-14', 'Telma Madagascar'),
('Don de riz Association locale', 1, 3, 2, 100, '2026-02-14', 'Association Toamasina'),
('Don en argent Diaspora', 5, 5, 3, 1000000, '2026-02-15', 'Diaspora France');

INSERT INTO achats (id_produit, id_ville, quantite, montant_total, date_achat)
VALUES
(1, 3, 200, 550000.00, '2026-02-15'),
(3, 4, 50, 1925000.00, '2026-02-16');

INSERT INTO conf_frais_achat (taux_pourcentage, date_config) VALUES
(10.00, '2026-02-10');
