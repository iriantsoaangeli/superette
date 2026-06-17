# TODO - Caisse supermarche

## Fichiers crees

- `app/Controllers/CaisseController.php`
- `app/Views/login.php`
- `app/Views/accueil.php`
- `app/Views/achat.php`
- `app/Models/CaisseModel.php`
- `app/Models/MouvementCaisseModel.php`
- `app/Database/Migrations/2026-06-17-070128_CreateProduit.php`
- `app/Database/Migrations/2026-06-17-070202_CreateAchat.php`
- `app/Database/Migrations/2026-06-17-070214_CreateCaisse.php`
- `app/Database/Migrations/2026-06-17-071250_CreateUser.php`
- `app/Database/Migrations/CreateTable.php`
- `app/Database/Seeds/SupermarketSeeder.php`
- `writable/db/superette.db`

## Step by step - Base SQLite

1. Configurer SQLite dans `app/Config/Database.php`.
2. Creer la base dans `writable/db/superette.db`.
3. Creer la migration `CreateProduit`.
4. Creer les tables `produit` et `mouvement_stock`.
5. Creer la migration `CreateAchat`.
6. Creer la table `achat`.
7. Creer la migration `CreateCaisse`.
8. Creer les tables `caisse` et `mouvement_caisse`.
9. Creer la migration `CreateUser`.
10. Creer la table `users`.
11. Lancer les migrations :

```bash
php spark migrate
```

12. Inserer les donnees initiales avec `SupermarketSeeder`.

```bash
php spark db:seed SupermarketSeeder
```

13. Verifier les donnees inserees :

- 5 produits dans `produit`
- 2 caisses dans `caisse`

## Modeles existants

### `CaisseModel`

1. Table utilisee : `caisse`.
2. Cle primaire : `id`.
3. Champs autorises : `nom_caisse`, `statut`.
4. Retour des donnees en tableau.

### `MouvementCaisseModel`

1. Table utilisee : `mouvement_caisse`.
2. Cle primaire : `id`.
3. Champs autorises : `caisse_id`, `montant`, `date`, `type`, `achat_id`.
4. Methode : `getMouvementsAvecCaisse()`.

### `AchatModel`

1. table utilisee : `achat`.
2. Cle primaire : `id`.
3. Champs autorises : `produit_id`, `caisse_id`, `quantite`, `date`, `mvm_id`.
4. Methode : `getAchatsAvecDetails`.

### `ProduitModel`

1. table utilise : `produit`.
2. Cle primaire : `id`.
3. Champs autorises : `designation`, `prix`, `quantite_stock`, `created_at`.
4. Methode : `findAll`

### `AchatController`

- Methode : *achats()*, *saisirAchat()*

### `CaisseController`

- Methode : *index()*, *selectionner()*