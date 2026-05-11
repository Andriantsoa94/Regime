
USE regime_tp;
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS prenom VARCHAR(100) NOT NULL DEFAULT '' AFTER nom,
    ADD COLUMN IF NOT EXISTS date_naissance DATE NULL AFTER genre,
    ADD COLUMN IF NOT EXISTS is_gold TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS gold_paid_at DATETIME NULL,
    ADD COLUMN IF NOT EXISTS solde DECIMAL(10,2) DEFAULT 0.00,
    ADD COLUMN IF NOT EXISTS created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


CREATE TABLE IF NOT EXISTS regimes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(150) NOT NULL,
    description     TEXT,
    pct_viande      DECIMAL(5,2) DEFAULT 0,
    pct_poisson     DECIMAL(5,2) DEFAULT 0,
    pct_volaille    DECIMAL(5,2) DEFAULT 0,
    pct_legumes     DECIMAL(5,2) DEFAULT 0,
    variation_min   DECIMAL(5,2) DEFAULT 0,
    variation_max   DECIMAL(5,2) DEFAULT 0,
    type_objectif   VARCHAR(100) NOT NULL DEFAULT 'imc_ideal',
    actif           TINYINT(1) DEFAULT 1,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS regime_prix (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    regime_id   INT NOT NULL,
    duree_jours INT NOT NULL,
    prix        DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS activites (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nom                 VARCHAR(150) NOT NULL,
    description         TEXT,
    calories_par_heure  DECIMAL(8,2) NOT NULL DEFAULT 300,
    intensite           ENUM('faible','modere','intense') NOT NULL DEFAULT 'modere',
    duree_recommandee   INT NOT NULL DEFAULT 30,
    seances_par_semaine INT NOT NULL DEFAULT 3,
    type_objectif       VARCHAR(100) DEFAULT 'reduire,augmenter,imc_ideal',
    actif               TINYINT(1) DEFAULT 1,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS codes_wallet (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    code        VARCHAR(50) NOT NULL UNIQUE,
    montant     DECIMAL(10,2) NOT NULL,
    is_used     TINYINT(1) DEFAULT 0,
    used_by     INT NULL,
    used_at     DATETIME NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (used_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS user_regimes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    regime_id   INT NOT NULL,
    duree_jours INT NOT NULL,
    prix_paye   DECIMAL(10,2) NOT NULL,
    date_debut  DATE NOT NULL,
    date_fin    DATE NOT NULL,
    statut      ENUM('actif','termine','annule') DEFAULT 'actif',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB;
UPDATE users SET prenom = 'Admin', is_gold = 0, solde = 0 WHERE nom = 'Lola Razaiarisoa' AND prenom = '';
UPDATE users SET prenom = SUBSTRING_INDEX(nom, ' ', 1) WHERE prenom = '' OR prenom IS NULL;


INSERT IGNORE INTO users (nom, prenom, email, genre, password, role_id, objectif, solde)
VALUES ('Admin', 'Super', 'admin@regime.mg', 'homme',
        '$2y$10$Tv5twgnhT4eqdKwqtVCGne0llmxHTt5sf2aRMFiSX9cSTEpylUM4.',
        1, 'imc_ideal', 0);

UPDATE users SET password = '$2y$10$JZc1EROjYsnEA.QeNh6u7OFO6gu3gTIh.ACOHxF6b41hNZTIahQua',
                solde = 5000
WHERE email IN ('lola@gmail.com','jean@gmail.com','sarah@gmail.com','lucas@gmail.com','emma@gmail.com');

UPDATE users SET solde = 15000 WHERE email = 'jean@gmail.com';
UPDATE users SET solde = 8000  WHERE email = 'lola@gmail.com';


INSERT IGNORE INTO regimes (id, nom, description, pct_viande, pct_poisson, pct_volaille, pct_legumes, variation_min, variation_max, type_objectif) VALUES
(1, 'Regime Proteines',    'Regime riche en proteines pour la prise de masse musculaire.',   40, 20, 30, 10,  2,  8,  'augmenter'),
(2, 'Regime Equilibre',    'Alimentation equilibree pour atteindre un IMC ideal.',            20, 25, 25, 30,  0,  3,  'imc_ideal'),
(3, 'Regime Minceur',      'Regime faible en graisses pour perdre du poids progressivement.', 10, 30, 20, 40, -8, -2, 'reduire'),
(4, 'Regime Prise de Masse','Regime hypercalorique pour augmenter significativement le poids.',45, 10, 35, 10,  5, 12, 'augmenter'),
(5, 'Regime Mediterraneen','Regime inspire du bassin mediterraneen, sain et equilibre.',      15, 35, 20, 30, -5,  1, 'reduire,imc_ideal');

INSERT IGNORE INTO regime_prix (regime_id, duree_jours, prix) VALUES
(1,7,15000),(1,14,25000),(1,30,45000),
(2,7,12000),(2,14,22000),(2,30,40000),
(3,7,13000),(3,14,23000),(3,30,42000),
(4,7,18000),(4,14,30000),(4,30,55000),
(5,7,14000),(5,14,24000),(5,30,43000);

INSERT IGNORE INTO activites (id, nom, description, calories_par_heure, intensite, duree_recommandee, seances_par_semaine) VALUES
(1,'Marche rapide', 'Marche a cadence elevee, ideal pour debutants.',             300,'faible', 45,5),
(2,'Course a pied', 'Jogging excellent pour bruler les graisses.',                 600,'modere', 30,4),
(3,'Natation',      'Exercice complet sollicitant tous les muscles du corps.',     500,'modere', 45,3),
(4,'Musculation',   'Entrainement pour developper la masse musculaire.',           400,'intense',60,4),
(5,'Cyclisme',      'Velo en exterieur ou en salle, faible impact articulaire.',   450,'modere', 60,3);

INSERT IGNORE INTO codes_wallet (code, montant) VALUES
('CODE-A1B2-GOLD1',10000),('CODE-C3D4-SILVER',5000),('CODE-E5F6-BRONZE',2000),
('CODE-G7H8-PROMO',15000),('CODE-I9J0-SPEC',8000),('CODE-K1L2-GIFT',3000),
('CODE-M3N4-VIP',20000),('CODE-O5P6-NEW',1000),('CODE-Q7R8-LOYAL',12000),
('CODE-S9T0-XMAS',5000),('CODE-U1V2-SUMMER',7000),('CODE-W3X4-BACK',4000),
('CODE-Y5Z6-PROMO',6000),('CODE-A7B8-SPRING',9000),('CODE-C9D0-FLASH',11000);
