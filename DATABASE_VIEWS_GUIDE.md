# Documentation des Vues SQL - Régimes Utilisateur

## Overview
Les vues SQL simplifient les requêtes complexes en pré-définissant les jointures et agrégations. Le modèle `UserRegimeModel` propose des méthodes pour accéder à ces vues facilement.

---

## 📊 Vues Disponibles

### 1. **view_user_regimes_details**
**Utilité**: Détails complets des régimes avec jointures utilisateur et régime
**Utilisable via**: `getRegimesViaView($user_id)`

```php
$userRegimeModel = new UserRegimeModel();
$regimes = $userRegimeModel->getRegimesViaView(5);
```

**Colonnes retournées**:
- `regime_user_id`, `user_id`, `user_nom`, `email`
- `regime_id`, `regime_nom`, `description`, `type_objectif`
- `duree_jours`, `prix_paye`, `date_debut`, `date_fin`, `statut`
- `jours_restants`, `statut_reel`

---

### 2. **view_user_active_regimes**
**Utilité**: Uniquement les régimes actuellement actifs et non expirés
**Utilisable via**: `getActiveRegimesViaView($user_id)`

```php
$activeRegimes = $userRegimeModel->getActiveRegimesViaView(5);
// Retourne les régimes où statut='actif' ET date_fin >= aujourd'hui
```

---

### 3. **view_user_regime_history**
**Utilité**: Historique des régimes complétés ou annulés
**Utilisable via**: `getHistoriqueViaView($user_id)`

```php
$history = $userRegimeModel->getHistoriqueViaView(5);
// Retourne les régimes termines et annulés, triés par date décroissante
```

---

### 4. **view_user_regime_statistics**
**Utilité**: Résumé statistique pour un utilisateur
**Utilisable via**: `getUserStatistics($user_id)`

```php
$stats = $userRegimeModel->getUserStatistics(5);
```

**Données**:
- `total_regimes` - Total des régimes achetés
- `regimes_actifs` - Nombre actuellement actifs
- `regimes_termines` - Régimes complétés
- `regimes_annules` - Régimes annulés
- `cout_total_regime` - Montant total dépensé
- `total_jours_regime` - Jours cumulatifs
- `premiere_date_regime` - Premier achat
- `derniere_date_regime` - Dernier régime

---

### 5. **view_regime_popularity**
**Utilité**: Statistiques de popularité par régime (tous utilisateurs)
**Utilisable via**: `getPopularRegimes($limit)`

```php
$popular = $userRegimeModel->getPopularRegimes(10);
// Les 10 régimes les plus vendus
```

**Données**:
- `nombre_acheteurs` - Combien d'utilisateurs ont acheté
- `acheteurs_actifs` / `acheteurs_termines`
- `prix_moyen` - Prix moyen payé
- `revenus_total` - Revenus générés

---

### 6. **view_expiring_regimes**
**Utilité**: Régimes expirant dans 7 jours
**Utilisable via**: 
- `getExpiringRegimes()` - tous les utilisateurs
- `getUserExpiringRegimes($user_id)` - un utilisateur

```php
$expiring = $userRegimeModel->getExpiringRegimes();
// Utile pour envoyer des notifications
```

---

### 7. **view_user_monthly_spending**
**Utilité**: Suivi des dépenses mensuelles par utilisateur
**Utilisable via**: `getUserMonthlySpendings($user_id)`

```php
$spending = $userRegimeModel->getUserMonthlySpendings(5);
// Retourne les dépenses groupées par mois/année
```

---

### 8. **view_user_regimes_with_pricing**
**Utilité**: Détails enrichis avec prix catalogue et remises
**Utilisable via**: 
- `getRegimesWithPricing($user_id)` - tous les régimes
- `getRegimePricingDetails($regime_user_id)` - un régime

```php
$pricing = $userRegimeModel->getRegimesWithPricing(5);
// Pour chaque régime: prix_catalogue, prix_reel, remise_appliquee
```

---

### 9. **view_expired_regimes**
**Utilité**: Régimes dont la date de fin a dépassé
**Utilisable via**: `getExpiredRegimes()`

```php
$expired = $userRegimeModel->getExpiredRegimes();
// Pour identifier les régimes qui aurait dû être terminés
```

---

### 10. **view_user_dashboard**
**Utilité**: Tableau de bord personnel complet
**Utilisable via**: `getUserDashboard($user_id)`

```php
$dashboard = $userRegimeModel->getUserDashboard(5);
```

**Contient**:
- Infos utilisateur (`id`, `nom`, `email`, `genre`, `solde`, `is_gold`)
- Nombre de régimes actifs
- Prochaine date d'expiration
- Régimes complétés
- Total de jours suivis
- Total dépensé

---

## 🛠️ Installation des Vues

1. **Exécuter le fichier SQL** :
```bash
mysql -u user -p regime_tp < app/Database/views.sql
```

2. **Ou via CodeIgniter migration** (optionnel):
```php
// app/Database/Migrations/????_CreateViews.php
public function up()
{
    $this->db->query(file_get_contents(APPPATH.'Database/views.sql'));
}
```

---

## 📍 Cas d'Usage Courants

### Afficher le tableau de bord d'un utilisateur
```php
$userRegime = new UserRegimeModel();
$dashboard = $userRegime->getUserDashboard(auth()->user()->id);

echo "Régimes actifs: " . $dashboard['regimes_actifs'];
echo "Total dépensé: " . $dashboard['total_depense'] . " Ar";
echo "Prochaine expiration: " . $dashboard['prochaine_expiration'];
```

### Vérifier les régimes expirant bientôt
```php
$expiringUser = $userRegime->getUserExpiringRegimes(auth()->user()->id);
if (!empty($expiringUser)) {
    // Afficher notification: "Votre régime expire dans X jours"
    echo "Jours avant expiration: " . $expiringUser[0]['jours_avant_expiration'];
}
```

### Afficher l'historique avec détails de prix
```php
$regimesHistory = $userRegime->getRegimesWithPricing(auth()->user()->id);
foreach ($regimesHistory as $regime) {
    echo $regime['regime_nom'];
    echo "Prix payé: " . $regime['prix_reel'];
    if ($regime['remise_appliquee'] > 0) {
        echo "Remise: " . $regime['remise_appliquee'];
    }
}
```

### Liste des régimes populaires (admin)
```php
$popular = $userRegime->getPopularRegimes(5);
foreach ($popular as $regime) {
    echo $regime['nom'] . " - Acheté par " . $regime['nombre_acheteurs'] . " clients";
    echo "Revenus: " . $regime['revenus_total'] . " Ar";
}
```

---

## ⚡ Avantages des Vues SQL

1. **Performance** - Les jointures sont pré-optimisées
2. **Réutilisabilité** - Même vue utilisée par plusieurs méthodes
3. **Maintenance** - Changer la logique une fois = appliquée partout
4. **Sécurité** - Requêtes paramétrées vs requêtes dynamiques
5. **Clarté** - Code métier plus lisible dans les contrôleurs

---

## 🔄 Mise à Jour des Vues

Si vous modifiez la structure des tables, vous devrez recréer les vues :

```sql
DROP VIEW IF EXISTS view_user_regimes_details;
-- Puis réexécuter views.sql
```

Ou utiliser `CREATE OR REPLACE VIEW` (déjà utilisé dans views.sql)

---

## 📝 Notes

- Les vues utilisent `LEFT JOIN` pour éviter de perdre de données
- Les dates sont comparées avec `CURDATE()` (date du serveur)
- Les vues sont **en lecture seule** (requêtes SELECT uniquement)
- Pour les modifications, utiliser directement le modèle `UserRegimeModel`
