INSERT INTO users (nom, login, mdp, id_type_user) VALUES
('Administrateur', 'admin', 'admin123', 1),
('Jean Rakoto', 'jean', 'jean123', 2),
('Marie Rasoa', 'marie', 'marie123', 2),
('Paul Andry', 'paul', 'paul123', 2);


INSERT INTO product_categories (name, description) VALUES
('Téléphonie', 'Téléphones et accessoires'),
('Informatique', 'Ordinateurs et équipements informatiques'),
('Maison', 'Objets pour la maison'),
('Vêtements', 'Habits et accessoires'),
('Loisirs', 'Objets de loisirs et divertissement');

INSERT INTO products (title, description, price_estimated, id_category, id_owner) VALUES
('Samsung Galaxy S10', 'Téléphone en bon état', 900000, 1, 2),
('iPhone 11', 'Très bon état, peu utilisé', 1200000, 1, 3),
('PC Portable Dell', 'Core i5, 8Go RAM', 1500000, 2, 4),
('Télévision LG 43"', 'Smart TV Full HD', 1300000, 3, 2),
('Veste en cuir', 'Veste noire taille M', 200000, 4, 3),
('PlayStation 4', 'Console avec manette', 1000000, 5, 4);


INSERT INTO product_photos (id_product, photo_path) VALUES
(1, 'uploads/products/galaxy_s10_1.jpg'),
(1, 'uploads/products/galaxy_s10_2.jpg'),
(2, 'uploads/products/iphone11.jpg'),
(3, 'uploads/products/dell_pc.jpg'),
(4, 'uploads/products/tv_lg.jpg'),
(5, 'uploads/products/veste_cuir.jpg'),
(6, 'uploads/products/ps4.jpg');

INSERT INTO propositions (
    id_user_proposer,
    id_product_proposed,
    id_product_requested,
    id_status
) VALUES
-- Jean propose son Samsung contre l’iPhone de Marie
(2, 1, 2, 1),

-- Marie propose sa veste contre la TV de Jean
(3, 5, 4, 2),

-- Paul propose sa PS4 contre le PC de Paul (exemple refusé)
(4, 6, 3, 3);

INSERT INTO exchanges (
    id_user_1,  
    id_product_1,
    id_user_2,
    id_product_2,
    id_status
) VALUES
-- Échange validé entre Jean et Marie
(2, 1, 3, 2, 2);


INSERT INTO exchanges (
    id_user_1,
    id_product_1,
    id_user_2,
    id_product_2,
    id_status
) VALUES
-- Échange validé entre Jean et Marie
(2, 1, 3, 2, 1);

INSERT INTO product_history (id_product, id_user) VALUES
(1, 2),
(1, 3),
(2, 3),
(2, 2),
(5, 3),
(5, 2);