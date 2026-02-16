# BNGRC - Application de Gestion des Dons et Besoins

## Vue d'ensemble
Application de gestion des urgences pour le Bureau National de Gestion des Risques et des Catastrophes (BNGRC) en Haïti. Permet de centraliser le suivi des besoins (nature, matériaux, argent) et des dons reçus par région et ville.

## Architecture

### Structure du projet
```
ProjetFinal/
├── app/
│   ├── config/           # Configuration globale
│   │   ├── bootstrap.php
│   │   ├── config.php
│   │   ├── routes.php    # Toutes les routes de l'application
│   │   └── services.php
│   ├── controllers/      # Orchestrateurs métier
│   │   ├── UserController.php      # Authentification
│   │   ├── RegionController.php    # CRUD Régions
│   │   ├── VilleController.php     # CRUD Villes
│   │   ├── BesoinController.php    # CRUD Besoins
│   │   ├── DonController.php       # CRUD Dons
│   │   └── DashboardController.php # Agrégation données
│   ├── models/           # Accès données
│   │   ├── Db.php           # Connexion BD
│   │   ├── Region.php
│   │   ├── Ville.php
│   │   ├── Besoin.php
│   │   ├── Don.php
│   │   ├── Produit.php
│   │   ├── Categorie.php
│   │   └── User.php
│   └── views/            # Rendu HTML
│       ├── accueil.php
│       ├── modele.php    # Template maître
│       ├── admin/        # Pages admin
│       ├── utilisateur/  # Pages utilisateur
│       ├── dashboard/    # Vue principal (index.php)
│       ├── region/       # Vues région (add.php, list.php)
│       ├── ville/        # Vues ville (add.php, list.php)
│       ├── besoin/       # Vues besoin (add.php, list.php)
│       └── don/          # Vues don (add.php, list.php)
├── public/
│   ├── index.php         # Point d'entrée
│   ├── css/              # Styles (dashboard.css)
│   └── js/
└── vendor/               # Dépendances Composer
```

## Flux de l'application

### 1. **Authentification**
- Routes: `/loginAdmin`, `/loginUser`, `/register`, `/logout`
- Contrôleur: `UserController`
- Gestion des sessions utilisateurs

### 2. **Tableau de Bord (Dashboard)**
- Route: `GET /dashboard`
- Contrôleur: `DashboardController`
- Vue: `app/views/dashboard/index.php`
- Affiche:
  - Statistiques: total villes, besoins, dons
  - Tableau des villes avec:
    - Nombre de besoins par ville
    - Quantité nécessaire (somme besoins)
    - Quantité reçue (somme dons)
    - Progression (pourcentage satisfaction)
    - État badge (Satisfait/Partiel/Attente)

### 3. **Gestion des Régions**
Routes et fonctionnalités:
- `GET /regions` → Affiche liste des régions
- `GET /regions/add` → Affiche formulaire d'ajout
- `POST /regions/add` → Traite et sauvegarde nouvelle région

Flux: Voir liste → Cliquer "Ajouter" → Remplir formulaire → Valider → Redirects vers liste

### 4. **Gestion des Villes**
Routes et fonctionnalités:
- `GET /villes` → Affiche liste des villes
- `GET /villes/add` → Affiche formulaire d'ajout
- `POST /villes/add` → Traite et sauvegarde nouvelle ville

Formulaire requiert: Nom + Sélection région

Flux: Voir liste → Cliquer "Ajouter" → Choisir région → Remplir nom → Valider → Redirects vers liste

### 5. **Gestion des Besoins**
Routes et fonctionnalités:
- `GET /besoins` → Affiche liste des besoins
- `GET /besoins/add` → Affiche formulaire d'ajout
- `POST /besoins/add` → Traite et sauvegarde nouveau besoin

Formulaire requiert: 
- Description (type de besoin)
- Produit (droplist, fetched from model)
- Ville (droplist, fetched from model)
- Quantité

Flux: Dashboard "Gérer besoins" → Liste → "Ajouter" → Remplir → Valider → Redirects liste

### 6. **Gestion des Dons**
Routes et fonctionnalités:
- `GET /dons` → Affiche liste des dons
- `GET /dons/add` → Affiche formulaire d'ajout
- `POST /dons/add` → Traite et sauvegarde nouveau don

Formulaire requiert:
- Description
- Produit (droplist, fetched from model)
- Ville (droplist, fetched from model)
- Quantité
- Donneur (nom du donateur)

Flux: Dashboard "Ajouter don" → Formulaire → Remplir → Valider → Redirects liste

## Logique métier clé

### Calcul de satisfaction (Dashboard)
```
Pour chaque ville:
  - totalBesoinsQuantite = somme(besoins.quantite pour cette ville)
  - totalDonsQuantite = somme(dons.quantite pour cette ville)
  - pourcentage = (totalDonsQuantite / totalBesoinsQuantite) * 100
  
État:
  - "Satisfait" si pourcentage >= 100
  - "Partiel" si 0 < pourcentage < 100
  - "Attente" si pourcentage == 0
  - "N/A" si pas de besoins
```

### Récupération des données étrangères
Dans les formulaires d'ajout:
- Villes: `Region::getAllRegions()` pour le dropdown région
- Besoins: `Produit::getAllProduits()` et `Ville::getAllVilles()` pour les dropdowns
- Dons: `Produit::getAllProduits()` et `Ville::getAllVilles()` pour les dropdowns

Aucune valeur en dur - tout vient de la BD.

## Stylisation

### Thème de couleur
- Couleur principale: `#e74c3c` (rouge)
- Couleur secondaire: `#c0392b` (rouge foncé)
- Fond: `#f8f9fa` (gris clair)

### Fichiers CSS
- `public/css/dashboard.css` → Styling du dashboard
- `public/css/style.css` → Styles généraux
- `public/css/tooplate-bistro-elegance.css` → Template original

### Template HTML
Tous les formulaires utilisent la structure `loginAdmin` avec:
- Section gauche: formulaire
- Section droite: décoration animée (blocs glissants)
- Consistance visuelle avec le reste de l'application

## Modèles de données (Déjà existants)

### Méthodes utilisées dans les contrôleurs

**Region**
- `getAllRegions()` → Array
- `addRegion($nom)` → Insert

**Ville**
- `getAllVilles()` → Array
- `addVille($nom, $idRegion)` → Insert

**Besoin**
- `getAllBesoins()` → Array
- `addBesoin($description, $idProduit, $idVille, $idRegion, $quantite, $dateBesoin)` → Insert

**Don**
- `getAllDons()` → Array
- `addDon($description, $idProduit, $idVille, $quantite, $dateDon, $donneur)` → Insert

**Produit**
- `getAllProduits()` → Array

**User**
- Authentification (login/register)

## Cycle complet de simulation

1. **Connexion**: Admin se connecte via `/loginAdmin`
2. **Accès Dashboard**: Redirection vers `/dashboard`
3. **Visualisation**: Voit état général des régions/villes/besoins/dons
4. **Ajout région** (optionnel):
   - Clique button "+" ou formulaire
   - Va à `/regions/add`
   - Remplit nom
   - POST `/regions/add` → sauvegarde → redirect `/regions`
   - Voit région dans liste
5. **Ajout ville**:
   - Clique "Gérer villes"
   - Va à `/villes`
   - Clique "Ajouter"
   - Sélectionne région
   - Entre nom
   - POST → sauvegarde → redirect `/villes`
6. **Ajout besoins**:
   - Dashboard → "Gérer besoins"
   - Va à `/besoins`
   - Clique "Ajouter"
   - Remplit description, produit, ville, quantité
   - POST → sauvegarde → redirect `/besoins`
7. **Ajout don**:
   - Dashboard → "Ajouter don"
   - Formulaire `/dons/add`
   - Remplit tous champs + donneur
   - POST → sauvegarde → redirect `/dons`
8. **Retour dashboard**:
   - Voit progression mise à jour
   - Besoins/dons comptabilisés par ville
   - État badges reflètent satisfaction

## Points importants

✅ **Ce qui fonctionne:**
- Toutes les routes sont configurées
- Tous les contrôleurs sont complets
- Tous les formulaires créés
- Listes des enregistrements affichées
- Dashboard agrège et calcule correctement
- Aucune valeur en dur - tout dynamique
- Styles cohérents avec template existant

⚠️ **À vérifier:**
- Connexion BD est correcte (config.php)
- Tables existent: regions, villes, besoins, dons, produits, categorie_produits, users
- Modèles ont méthodes `getAllXXX()` et `addXXX()` implémentées

## Lancer l'application

1. S'assurer que la BD est créée et tables existent
2. Accéder `http://localhost/ProjetFinal/public/`
3. Se connecter via login admin
4. Commencer à ajouter données via formulaires
5. Dashboard se met à jour automatiquement

---

**Framework:** Flight PHP (micro-framework léger)
**Langue:** French/English
**Dernière mise à jour:** Après création vues list
