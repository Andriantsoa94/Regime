-- ============================================================
-- VUES SQL POUR FACILITER LES REQUETES - Application Regime
-- ============================================================

USE regime_tp;

-- ============================================================
-- VUE 1: Vue complète des régimes utilisateur avec détails
-- ============================================================
-- Récupère tous les régimes d'un utilisateur avec les informations du régime
CREATE OR REPLACE VIEW view_user_regimes_details AS
SELECT 
    ur.id AS regime_user_id,
    ur.user_id,
    u.nom AS user_nom,
    u.email,
    ur.regime_id,
    r.nom AS regime_nom,
    r.description,
    r.type_objectif,
    ur.duree_jours,
    ur.prix_paye,
    ur.date_debut,
    ur.date_fin,
    ur.statut,
    ur.created_at,
    DATEDIFF(ur.date_fin, CURDATE()) AS jours_restants,
    CASE 
        WHEN ur.statut = 'actif' AND CURDATE() > ur.date_fin THEN 'expire'
        ELSE ur.statut 
    END AS statut_reel
FROM user_regimes ur
JOIN users u ON u.id = ur.user_id
LEFT JOIN regimes r ON r.id = ur.regime_id
ORDER BY ur.date_debut DESC;

-- ============================================================
-- VUE 2: Régimes actifs des utilisateurs
-- ============================================================
-- Uniquement les régimes actuellement actifs et non expirés
CREATE OR REPLACE VIEW view_user_active_regimes AS
SELECT 
    ur.id AS regime_user_id,
    ur.user_id,
    u.nom AS user_nom,
    u.email,
    u.genre,
    ur.regime_id,
    r.nom AS regime_nom,
    r.description,
    ur.duree_jours,
    ur.prix_paye,
    ur.date_debut,
    ur.date_fin,
    DATEDIFF(ur.date_fin, CURDATE()) AS jours_restants,
    ur.created_at
FROM user_regimes ur
JOIN users u ON u.id = ur.user_id
LEFT JOIN regimes r ON r.id = ur.regime_id
WHERE ur.statut = 'actif' 
  AND CURDATE() <= ur.date_fin
ORDER BY ur.date_fin ASC;

-- ============================================================
-- VUE 3: Historique des régimes (terminés et annulés)
-- ============================================================
-- Suivi complet de tous les régimes complétés
CREATE OR REPLACE VIEW view_user_regime_history AS
SELECT 
    ur.id AS regime_user_id,
    ur.user_id,
    u.nom AS user_nom,
    u.email,
    ur.regime_id,
    r.nom AS regime_nom,
    ur.duree_jours,
    ur.prix_paye,
    ur.date_debut,
    ur.date_fin,
    ur.statut,
    ur.created_at,
    DATEDIFF(ur.date_fin, ur.date_debut) AS duree_reelle_jours
FROM user_regimes ur
JOIN users u ON u.id = ur.user_id
LEFT JOIN regimes r ON r.id = ur.regime_id
WHERE ur.statut IN ('termine', 'annule')
ORDER BY ur.date_fin DESC;

-- ============================================================
-- VUE 4: Statistiques par utilisateur
-- ============================================================
-- Résumé des régimes pour chaque utilisateur
CREATE OR REPLACE VIEW view_user_regime_statistics AS
SELECT 
    u.id,
    u.nom,
    u.email,
    u.genre,
    COUNT(ur.id) AS total_regimes,
    SUM(CASE WHEN ur.statut = 'actif' THEN 1 ELSE 0 END) AS regimes_actifs,
    SUM(CASE WHEN ur.statut = 'termine' THEN 1 ELSE 0 END) AS regimes_termines,
    SUM(CASE WHEN ur.statut = 'annule' THEN 1 ELSE 0 END) AS regimes_annules,
    SUM(ur.prix_paye) AS cout_total_regime,
    SUM(ur.duree_jours) AS total_jours_regime,
    MIN(ur.date_debut) AS premiere_date_regime,
    MAX(ur.date_fin) AS derniere_date_regime
FROM users u
LEFT JOIN user_regimes ur ON ur.user_id = u.id
GROUP BY u.id, u.nom, u.email, u.genre;

-- ============================================================
-- VUE 5: Régimes populaires (statistiques par régime)
-- ============================================================
-- Quels régimes sont les plus achetés
CREATE OR REPLACE VIEW view_regime_popularity AS
SELECT 
    r.id AS regime_id,
    r.nom,
    r.description,
    r.type_objectif,
    COUNT(ur.id) AS nombre_acheteurs,
    COUNT(CASE WHEN ur.statut = 'actif' THEN 1 END) AS acheteurs_actifs,
    COUNT(CASE WHEN ur.statut = 'termine' THEN 1 END) AS acheteurs_termines,
    AVG(ur.prix_paye) AS prix_moyen,
    SUM(ur.prix_paye) AS revenus_total,
    AVG(ur.duree_jours) AS duree_moyenne
FROM regimes r
LEFT JOIN user_regimes ur ON ur.regime_id = r.id
GROUP BY r.id, r.nom, r.description, r.type_objectif
ORDER BY nombre_acheteurs DESC;

-- ============================================================
-- VUE 6: Régimes en cours d'expiration (dans 7 jours)
-- ============================================================
-- Régimes qui expireront bientôt
CREATE OR REPLACE VIEW view_expiring_regimes AS
SELECT 
    ur.id AS regime_user_id,
    ur.user_id,
    u.nom AS user_nom,
    u.email,
    r.nom AS regime_nom,
    ur.date_fin,
    DATEDIFF(ur.date_fin, CURDATE()) AS jours_avant_expiration
FROM user_regimes ur
JOIN users u ON u.id = ur.user_id
LEFT JOIN regimes r ON r.id = ur.regime_id
WHERE ur.statut = 'actif'
  AND DATEDIFF(ur.date_fin, CURDATE()) BETWEEN 0 AND 7
ORDER BY ur.date_fin ASC;

-- ============================================================
-- VUE 7: Dépenses mensuelles par utilisateur
-- ============================================================
-- Tracker de dépenses par mois
CREATE OR REPLACE VIEW view_user_monthly_spending AS
SELECT 
    u.id,
    u.nom,
    u.email,
    DATE_TRUNC(ur.created_at, MONTH) AS mois,
    YEAR(ur.created_at) AS anne,
    MONTH(ur.created_at) AS mois_num,
    COUNT(ur.id) AS nombre_regimes_achetes,
    SUM(ur.prix_paye) AS depense_mensuelle
FROM users u
LEFT JOIN user_regimes ur ON ur.user_id = u.id
GROUP BY u.id, u.nom, u.email, anne, mois_num, mois
ORDER BY anne DESC, mois_num DESC;

-- ============================================================
-- VUE 8: Détails complets avec prix du régime
-- ============================================================
-- Vue enrichie avec les informations de prix disponibles
CREATE OR REPLACE VIEW view_user_regimes_with_pricing AS
SELECT 
    ur.id AS regime_user_id,
    ur.user_id,
    u.nom AS user_nom,
    u.email,
    ur.regime_id,
    r.nom AS regime_nom,
    r.description,
    ur.duree_jours,
    rp.prix AS prix_catalogue,
    ur.prix_paye AS prix_reel,
    (ur.prix_paye - rp.prix) AS remise_appliquee,
    ur.date_debut,
    ur.date_fin,
    ur.statut,
    DATEDIFF(ur.date_fin, CURDATE()) AS jours_restants
FROM user_regimes ur
JOIN users u ON u.id = ur.user_id
LEFT JOIN regimes r ON r.id = ur.regime_id
LEFT JOIN regime_prix rp ON rp.regime_id = ur.regime_id AND rp.duree_jours = ur.duree_jours
ORDER BY ur.date_debut DESC;

-- ============================================================
-- VUE 9: Utilisateurs avec régimes expirés
-- ============================================================
-- Détection des régimes qui ont expiré
CREATE OR REPLACE VIEW view_expired_regimes AS
SELECT 
    ur.id AS regime_user_id,
    ur.user_id,
    u.nom AS user_nom,
    u.email,
    r.nom AS regime_nom,
    ur.date_fin,
    DATEDIFF(CURDATE(), ur.date_fin) AS jours_depuis_expiration
FROM user_regimes ur
JOIN users u ON u.id = ur.user_id
LEFT JOIN regimes r ON r.id = ur.regime_id
WHERE ur.statut = 'actif'
  AND CURDATE() > ur.date_fin
ORDER BY ur.date_fin DESC;

-- ============================================================
-- VUE 10: Dashboard client - résumé personnel
-- ============================================================
-- Vue pour le tableau de bord personnel d'un utilisateur
CREATE OR REPLACE VIEW view_user_dashboard AS
SELECT 
    u.id,
    u.nom,
    u.email,
    u.genre,
    u.solde,
    u.is_gold,
    COUNT(DISTINCT CASE WHEN ur.statut = 'actif' THEN ur.id END) AS regimes_actifs,
    MAX(CASE WHEN ur.statut = 'actif' THEN ur.date_fin END) AS prochaine_expiration,
    COUNT(DISTINCT CASE WHEN ur.statut = 'termine' THEN ur.id END) AS regimes_completed,
    SUM(CASE WHEN ur.statut = 'termine' THEN ur.duree_jours ELSE 0 END) AS total_jours_complets,
    SUM(ur.prix_paye) AS total_depense
FROM users u
LEFT JOIN user_regimes ur ON ur.user_id = u.id
GROUP BY u.id, u.nom, u.email, u.genre, u.solde, u.is_gold;
