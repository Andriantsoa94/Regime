# 👤 Controller Profile - Documentation Complète

## Aperçu

Le contrôleur **Profile** gère tous les aspects du profil utilisateur:
- Affichage du profil
- Édition des informations personnelles
- Gestion des données de santé (taille, poids, IMC)
- Changement de mot de passe
- API REST pour le profil

---

## 📋 Méthodes du Controller

### 1. `index()`
**Affiche le profil complet de l'utilisateur connecté**

```php
// Route: /profile ou /profile/index
public function index()
```

**Retour**: Vue `profile/profile` avec:
- `user` - Données utilisateur
- `sante` - Données de santé (taille, poids)
- `imc` - Indice de Masse Corporelle calculé
- `imc_category` - Catégorie IMC (insuffisant, normal, surpoids, obésité, etc.)

**Exemple d'utilisation en vue**:
```php
<?= $user['nom'] ?> <?= $user['prenom'] ?>
Email: <?= $user['email'] ?>
Taille: <?= $sante['taille'] ?? 'Non renseignée' ?> cm
Poids: <?= $sante['poids'] ?? 'Non renseigné' ?> kg
IMC: <?= $imc ?? 'Non calculable' ?>
```

---

### 2. `edit()`
**Affiche le formulaire d'édition du profil**

```php
// Route: /profile/edit
public function edit()
```

**Retour**: Vue `profile/edit` avec:
- `user` - Données actuelles de l'utilisateur
- `sante` - Données de santé actuelles

**Exemple de formulaire**:
```html
<form method="POST" action="/profile/update">
    <?= csrf_field() ?>
    <input type="text" name="nom" value="<?= $user['nom'] ?>" required>
    <input type="text" name="prenom" value="<?= $user['prenom'] ?? '' ?>">
    <input type="email" name="email" value="<?= $user['email'] ?>" required>
    <select name="genre">
        <option value="homme" <?= $user['genre'] === 'homme' ? 'selected' : '' ?>>Homme</option>
        <option value="femme" <?= $user['genre'] === 'femme' ? 'selected' : '' ?>>Femme</option>
        <option value="autre" <?= $user['genre'] === 'autre' ? 'selected' : '' ?>>Autre</option>
    </select>
    <input type="date" name="date_naissance" value="<?= $user['date_naissance'] ?? '' ?>">
    <textarea name="objectif"><?= $user['objectif'] ?? '' ?></textarea>
    
    <!-- Données de santé -->
    <input type="number" name="taille" step="0.1" value="<?= $sante['taille'] ?? '' ?>" placeholder="Taille en cm">
    <input type="number" name="poids" step="0.1" value="<?= $sante['poids'] ?? '' ?>" placeholder="Poids en kg">
    
    <button type="submit">Mettre à jour</button>
</form>
```

---

### 3. `update()`
**Met à jour le profil de l'utilisateur**

```php
// Route: POST /profile/update
public function update()
```

**Données POST acceptées**:
```
nom (required) - min: 2, max: 100
prenom (optional) - min: 2, max: 100
email (required) - valid email, unique
genre (required) - "homme" | "femme" | "autre"
date_naissance (optional) - format: YYYY-MM-DD
objectif (optional) - max: 500 caractères
taille (optional) - min: 50, max: 300 (en cm)
poids (optional) - min: 20, max: 300 (en kg)
```

**Validations appliquées**:
- ✅ Champs requis vérifiés
- ✅ Formats validés (email, date)
- ✅ Unicité de l'email vérifiée
- ✅ Plages de valeurs vérifiées (taille, poids)

**Retour**: Redirection vers `/profile` avec message de succès/erreur

**Exemple de traitement**:
```php
// Depuis une vue
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
```

---

### 4. `changePassword()`
**Change le mot de passe de l'utilisateur**

```php
// Route: POST /profile/change-password
public function changePassword()
```

**Données POST requises**:
```
current_password (required) - min: 6 caractères
new_password (required) - min: 8, différent du courant
confirm_password (required) - doit correspondre à new_password
```

**Validations**:
- ✅ Verifies le mot de passe actuel
- ✅ Vérifie la longueur du nouveau mot de passe
- ✅ Vérifie que les deux nouveaux mots de passe correspondent
- ✅ Mot de passe hashé avec BCRYPT avant stockage

**Exemple de formulaire**:
```html
<form method="POST" action="/profile/change-password">
    <?= csrf_field() ?>
    <input type="password" name="current_password" placeholder="Mot de passe actuel" required>
    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
    <button type="submit">Changer le mot de passe</button>
</form>
```

---

### 5. `apiGetProfile()`
**Retourne le profil en JSON (pour API REST)**

```php
// Route: GET /api/profile/get
public function apiGetProfile()
```

**Retour JSON**:
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "nom": "Dupont",
            "prenom": "Jean",
            "email": "jean@example.com",
            "genre": "homme",
            "objectif": "imc_ideal",
            "date_naissance": "1990-01-15",
            "solde": 5000
        },
        "sante": {
            "id": 1,
            "user_id": 1,
            "taille": 180,
            "poids": 75
        },
        "imc": 23.15
    }
}
```

---

## 🛠️ Méthodes Protégées (helper methods)

### `calculateIMC($sante)`
Calcule l'IMC: poids (kg) / (taille (m))²

```php
$imc = $this->calculateIMC($sante);
// Retourne: float|null
```

### `getIMCCategory($imc)`
Retourne la catégorie IMC

```php
$category = $this->getIMCCategory(23.5);
// Retourne: "normal"
```

**Catégories**:
- `insuffisant` - IMC < 18.5
- `normal` - 18.5 ≤ IMC < 25
- `surpoids` - 25 ≤ IMC < 30
- `obese_classe1` - 30 ≤ IMC < 35
- `obese_classe2` - 35 ≤ IMC < 40
- `obese_classe3` - IMC ≥ 40

---

## 📚 Dépendances

- **UserModel** - Gestion des utilisateurs
- **SanteModel** - Gestion des données de santé
- **CodeIgniter Auth** - Authentification `auth()`

---

## 🔐 Authentification & Sécurité

- ✅ Toutes les routes nécessitent l'authentification (`filter: auth`)
- ✅ Validation CSRF sur tous les formulaires (`csrf_field()`)
- ✅ Mots de passe hashés avec BCRYPT
- ✅ Emails vérifiés comme uniques
- ✅ Gestion d'erreurs robuste et logging

---

## 📍 Configuration des Routes

Ajouter à `app/Config/Routes.php`:

```php
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('', 'Profile::index');
    $routes->get('edit', 'Profile::edit');
    $routes->post('update', 'Profile::update');
    $routes->get('change-password', 'Profile::changePassword');
    $routes->post('change-password', 'Profile::changePassword');
});

$routes->group('api/profile', ['filter' => 'auth'], function($routes) {
    $routes->get('get', 'Profile::apiGetProfile');
});
```

---

## 📱 Exemples d'Utilisation

### Afficher le profil
```php
// Dans un contrôleur
return redirect()->to('/profile');
```

### Éditer le profil
```php
// Dans une vue
<a href="/profile/edit" class="btn btn-primary">Éditer mon profil</a>
```

### Changer le mot de passe
```php
// Dans une vue
<form method="POST" action="/profile/change-password">
    // ... formulaire ...
</form>
```

### Récupérer le profil en JSON
```javascript
// En JavaScript
fetch('/api/profile/get')
    .then(res => res.json())
    .then(data => {
        console.log('IMC:', data.data.imc);
        console.log('Poids:', data.data.sante.poids);
    });
```

---

## 🧪 Test du Profil

Accéder à:
```
http://localhost:8080/profile           → Voir le profil
http://localhost:8080/profile/edit      → Éditer le profil
http://localhost:8080/api/profile/get   → Récupérer en JSON
```

---

## ✨ Bonnes Pratiques

1. **Toujours valider les données POST** - Déjà fait dans `update()`
2. **Vérifier l'authentification** - Utiliser `if (!auth()->loggedIn())`
3. **Mettre à jour la session** - Fait automatiquement après chaque `update()`
4. **Logger les erreurs** - Utilisé via `log_message('error', ...)`
5. **Afficher des messages clairs** - Utiliser flash data
6. **Protéger les données sensibles** - Mots de passe hashés, CSRF protection

---

**Version**: 1.2  
**Date**: 2026-05-10  
**Framework**: CodeIgniter 4
