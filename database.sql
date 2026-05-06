-- Création de la base de données pour l'application Regime Tp
-- Base de données MySQL

CREATE DATABASE IF NOT EXISTS regime_tp;
USE regime_tp;

-- Table role
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50) NOT NULL
);

-- Table aliment
CREATE TABLE aliment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL,
    valeur FLOAT NOT NULL -- valeur nutritionnelle, par exemple en calories
);

-- Table sport
CREATE TABLE sport (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL,
    valeur FLOAT NOT NULL -- valeur énergétique, par exemple en calories brûlées
);

-- Table menu
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aliment_id INT NOT NULL,
    pourcentage FLOAT NOT NULL, -- pourcentage dans le menu
    FOREIGN KEY (aliment_id) REFERENCES aliment(id)
);

-- Table type
CREATE TABLE type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    sport_id INT NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (menu_id) REFERENCES menu(id),
    FOREIGN KEY (sport_id) REFERENCES sport(id)
);

-- Table regime
CREATE TABLE regime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    duree INT NOT NULL, -- durée en jours
    type_id INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES type(id)
);

-- Table user (sans sante_id pour éviter référence circulaire)
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    genre ENUM('homme', 'femme', 'autre') NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    objectif TEXT,
    FOREIGN KEY (role_id) REFERENCES role(id)
);

-- Table sante
CREATE TABLE sante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    taille FLOAT NOT NULL, -- en cm
    poids FLOAT NOT NULL, -- en kg
    FOREIGN KEY (user_id) REFERENCES user(id)
);

-- Ajouter la clé étrangère pour sante_id dans user
ALTER TABLE user ADD COLUMN sante_id INT;
ALTER TABLE user ADD FOREIGN KEY (sante_id) REFERENCES sante(id);