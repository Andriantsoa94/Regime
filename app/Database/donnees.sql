USE regime_tp;

-- =========================
-- INSERTION DES ROLES
-- =========================
INSERT INTO role (label) VALUES
('admin'),
('coach'),
('client');

-- =========================
-- INSERTION DES ALIMENTS
-- =========================
INSERT INTO aliment (libelle, valeur) VALUES
('Riz complet', 130),
('Poulet grille', 165),
('Salade verte', 25),
('Pomme', 52),
('Banane', 89),
('Oeuf', 155),
('Poisson', 140),
('Avoine', 389),
('Yaourt nature', 59),
('Patate douce', 86),
('Brocoli', 34),
('Avocat', 160),
('Tomate', 18),
('Carotte', 41),
('Pain complet', 247);

-- =========================
-- INSERTION DES SPORTS
-- =========================
INSERT INTO sport (libelle, valeur) VALUES
('Course a pied', 500),
('Natation', 450),
('Cyclisme', 400),
('Yoga', 180),
('Musculation', 350);

-- =========================
-- INSERTION DES MENUS
-- =========================
INSERT INTO menu (aliment_id, pourcentage) VALUES
(1, 40),
(2, 35),
(3, 25),
(4, 20),
(5, 30),
(6, 15),
(7, 45),
(8, 50),
(9, 20),
(10, 35),
(11, 25),
(12, 15),
(13, 10),
(14, 20),
(15, 40);

-- =========================
-- INSERTION DES TYPES
-- =========================
INSERT INTO type (menu_id, sport_id, prix) VALUES
(1, 1, 50000.00),
(2, 2, 65000.00),
(3, 3, 55000.00),
(4, 4, 40000.00),
(5, 5, 70000.00);

-- =========================
-- INSERTION DES REGIMES
-- =========================
INSERT INTO regime (duree, type_id) VALUES
(30, 1),
(45, 2),
(60, 3),
(15, 4),
(90, 5);

-- =========================
-- INSERTION DES USERS
-- =========================
INSERT INTO users (nom, email, genre, password, role_id, objectif) VALUES
('Lola Razaiarisoa', 'lola@gmail.com', 'femme', '1234', 1, 'Perte de poids'),
('Jean Rakoto', 'jean@gmail.com', 'homme', '1234', 2, 'Prise de masse'),
('Sarah Ravelona', 'sarah@gmail.com', 'femme', '1234', 3, 'Maintien de forme'),
('Lucas Randria', 'lucas@gmail.com', 'homme', '1234', 3, 'Amelioration cardio'),
('Emma Rasoa', 'emma@gmail.com', 'femme', '1234', 3, 'Regime equilibre');

-- =========================
-- INSERTION DES DONNEES SANTE
-- =========================
INSERT INTO sante (user_id, taille, poids) VALUES
(1, 165, 60),
(2, 180, 78),
(3, 170, 65),
(4, 175, 72),
(5, 160, 55);