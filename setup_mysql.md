# Configuration MySQL pour Système Hajj & Omra

## Étapes de Configuration

### 1. Démarrer XAMPP
- Ouvrir **XAMPP Control Panel**
- Démarrer **Apache** et **MySQL**
- Vérifier que les services sont en marche (indicateur vert)

### 2. Créer la base de données
Ouvrir phpMyAdmin (http://localhost/phpmyadmin) ou exécuter:
```sql
CREATE DATABASE hajj_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Migrer la base de données
```bash
cd C:\xampp\htdocs\hajj\hajj-laravel
php artisan migrate
```

### 4. Peupler la base avec des données
```bash
php artisan db:seed
```

### 5. Créer l'utilisateur admin
```bash
php artisan db:seed --class=UserSeeder
```

## Configuration Offline pour Agence

Le système est maintenant configuré pour fonctionner entièrement offline :

### ✅ Avantages MySQL pour votre agence :
- **Performance** : Plus rapide que SQLite pour de gros volumes
- **Fiabilité** : Meilleure gestion des transactions
- **Backup** : Sauvegarde facile via phpMyAdmin
- **Concurrence** : Plusieurs utilisateurs simultanés
- **Requêtes complexes** : Support complet SQL

### 📊 Fonctionnalités disponibles :
- ✅ Gestion complète des campagnes (Hajj/Omra)
- ✅ Inscription et gestion des pèlerins
- ✅ Upload et gestion des documents
- ✅ Système de paiements (à venir)
- ✅ Rapports et statistiques
- ✅ Gestion multi-utilisateurs avec rôles
- ✅ Interface moderne et responsive

### 🔒 Sécurité :
- Authentification sécurisée
- Contrôle d'accès par rôles
- Validation des fichiers uploadés
- Protection CSRF

### 📱 Interface :
- Design responsive (mobile/desktop)
- Interface en français
- Navigation intuitive
- Tableaux de bord avec statistiques

## Informations de connexion par défaut :
- **Email**: ismailahamadou5@gmail.com
- **Mot de passe**: 12345678

Une fois MySQL démarré, le système sera prêt à gérer votre agence de Hajj & Omra !