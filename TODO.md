# TODO - Système de Gestion Hajj & Omra Laravel

## 📋 Liste complète des tâches restantes

### 🔥 PHASE 1 - API BACKEND (PRIORITÉ CRITIQUE)

#### 1.1 Contrôleurs API
- [ ] **AuthController** - Authentification
  - [ ] `login()` - Connexion avec email/password
  - [ ] `logout()` - Déconnexion et révocation token
  - [ ] `me()` - Informations utilisateur connecté
  - [ ] `refresh()` - Renouvellement token
  - [ ] `changePassword()` - Changement mot de passe

- [ ] **CampaignController** - Gestion des campagnes
  - [ ] `index()` - Liste paginée des campagnes
  - [ ] `show($id)` - Détails d'une campagne
  - [ ] `store()` - Création nouvelle campagne
  - [ ] `update($id)` - Modification campagne
  - [ ] `destroy($id)` - Suppression campagne
  - [ ] `activate($id)` - Activer/désactiver campagne
  - [ ] `statistics($id)` - Statistiques campagne

- [ ] **PilgrimController** - Gestion des pèlerins
  - [ ] `index()` - Liste paginée avec filtres (campagne, statut, genre)
  - [ ] `show($id)` - Détails pèlerin avec relations
  - [ ] `store()` - Enregistrement nouveau pèlerin
  - [ ] `update($id)` - Modification informations pèlerin
  - [ ] `destroy($id)` - Suppression pèlerin
  - [ ] `updateStatus($id)` - Changement statut pèlerin
  - [ ] `search()` - Recherche par nom, email, téléphone
  - [ ] `export()` - Export Excel/PDF liste pèlerins

- [ ] **PaymentController** - Gestion des paiements
  - [ ] `index()` - Liste paiements avec filtres
  - [ ] `show($id)` - Détails paiement
  - [ ] `store()` - Enregistrement nouveau paiement
  - [ ] `update($id)` - Modification paiement
  - [ ] `destroy($id)` - Annulation paiement
  - [ ] `receipt($id)` - Génération reçu PDF
  - [ ] `pilgrims($pilgrimId)` - Historique paiements pèlerin
  - [ ] `statistics()` - Statistiques paiements

- [ ] **DocumentController** - Gestion des documents
  - [ ] `show($pilgrimId)` - Documents d'un pèlerin
  - [ ] `upload()` - Upload documents (photo, CNI, passeport, visa, vaccin)
  - [ ] `download($id)` - Téléchargement document
  - [ ] `delete($id)` - Suppression document
  - [ ] `checkCompleteness($pilgrimId)` - Vérification complétude
  - [ ] `updateStatus($pilgrimId)` - Mise à jour statut documents

- [ ] **ReportController** - Rapports et statistiques
  - [ ] `dashboard()` - Données tableau de bord
  - [ ] `campaigns()` - Rapport campagnes
  - [ ] `payments()` - Rapport financier
  - [ ] `pilgrims()` - Rapport pèlerins
  - [ ] `documents()` - Rapport documents
  - [ ] `export($type)` - Export rapports

- [ ] **UserController** - Gestion utilisateurs
  - [ ] `index()` - Liste utilisateurs
  - [ ] `show($id)` - Détails utilisateur
  - [ ] `store()` - Création utilisateur
  - [ ] `update($id)` - Modification utilisateur
  - [ ] `destroy($id)` - Suppression utilisateur
  - [ ] `resetPassword($id)` - Réinitialisation mot de passe

- [ ] **SystemSettingController** - Paramètres système
  - [ ] `index()` - Liste paramètres
  - [ ] `show($key)` - Valeur paramètre
  - [ ] `update()` - Mise à jour paramètres
  - [ ] `categories()` - Paramètres par catégorie

#### 1.2 Middlewares
- [ ] **Authentification**
  - [ ] `auth:sanctum` - Vérification token API
  - [ ] `PermissionMiddleware` - Vérification permissions utilisateur
  - [ ] `RoleMiddleware` - Vérification rôle utilisateur

#### 1.3 FormRequests - Validation
- [ ] **Auth**
  - [ ] `LoginRequest` - Validation login
  - [ ] `ChangePasswordRequest` - Validation changement mot de passe

- [ ] **Campaign**
  - [ ] `StoreCampaignRequest` - Validation création campagne
  - [ ] `UpdateCampaignRequest` - Validation modification campagne

- [ ] **Pilgrim**
  - [ ] `StorePilgrimRequest` - Validation enregistrement pèlerin
  - [ ] `UpdatePilgrimRequest` - Validation modification pèlerin

- [ ] **Payment**
  - [ ] `StorePaymentRequest` - Validation nouveau paiement
  - [ ] `UpdatePaymentRequest` - Validation modification paiement

- [ ] **Document**
  - [ ] `UploadDocumentRequest` - Validation upload documents

- [ ] **User**
  - [ ] `StoreUserRequest` - Validation création utilisateur
  - [ ] `UpdateUserRequest` - Validation modification utilisateur

#### 1.4 Routes API
- [ ] **Définir toutes les routes** dans `routes/api.php`
  - [ ] Routes publiques (login)
  - [ ] Routes protégées (auth:sanctum)
  - [ ] Groupes par permissions/rôles
  - [ ] Versioning API (/api/v1/)

#### 1.5 Resources API (Formatage réponses)
- [ ] **UserResource** - Format réponse utilisateur
- [ ] **CampaignResource** - Format réponse campagne
- [ ] **PilgrimResource** - Format réponse pèlerin
- [ ] **PaymentResource** - Format réponse paiement
- [ ] **DocumentResource** - Format réponse document

### 🛠 PHASE 2 - CONFIGURATION & SERVICES

#### 2.1 Configuration
- [ ] **CORS** - Configurer `config/cors.php` pour frontend
- [ ] **Filesystem** - Configurer storage pour uploads
  - [ ] Dossiers : `documents/`, `photos/`, `receipts/`
  - [ ] Permissions et sécurité fichiers
- [ ] **Queue** - Configuration pour traitement asynchrone
- [ ] **Cache** - Configuration Redis/Database pour performances
- [ ] **Logging** - Configuration logs applicatifs

#### 2.2 Services
- [ ] **PaymentService** - Logique métier paiements
  - [ ] Calcul montants automatique
  - [ ] Génération numéros reçus
  - [ ] Mise à jour statuts pèlerins
- [ ] **DocumentService** - Logique gestion documents
  - [ ] Validation fichiers
  - [ ] Redimensionnement images
  - [ ] Génération thumbnails
- [ ] **ReportService** - Génération rapports
  - [ ] Export PDF
  - [ ] Export Excel
  - [ ] Calculs statistiques
- [ ] **NotificationService** - Notifications
  - [ ] Email notifications
  - [ ] SMS notifications (optionnel)

#### 2.3 Seeders & Factories
- [ ] **DatabaseSeeder** - Orchestration générale
- [ ] **UserRoleSeeder** - Rôles et permissions (déjà fait)
- [ ] **UserSeeder** - Utilisateurs de test
  - [ ] Super Admin
  - [ ] Admin
  - [ ] Comptable
  - [ ] Caissier
  - [ ] Agent
- [ ] **CampaignSeeder** - Campagnes de test
- [ ] **PilgrimSeeder** - Pèlerins de test avec Faker
- [ ] **PaymentSeeder** - Paiements de test
- [ ] **SystemSettingSeeder** - Paramètres par défaut
- [ ] **Factories** pour génération données de test

### 🎨 PHASE 3 - INTERFACE WEB (OPTIONNEL)

#### 3.1 Layouts Blade
- [ ] **app.blade.php** - Layout principal
- [ ] **auth.blade.php** - Layout authentification
- [ ] **admin.blade.php** - Layout administration

#### 3.2 Vues d'authentification
- [ ] **login.blade.php** - Page connexion
- [ ] **dashboard.blade.php** - Tableau de bord

#### 3.3 Vues principales
- [ ] **Campagnes**
  - [ ] `campaigns/index.blade.php` - Liste
  - [ ] `campaigns/show.blade.php` - Détails
  - [ ] `campaigns/create.blade.php` - Création
  - [ ] `campaigns/edit.blade.php` - Modification

- [ ] **Pèlerins**
  - [ ] `pilgrims/index.blade.php` - Liste avec filtres
  - [ ] `pilgrims/show.blade.php` - Profil complet
  - [ ] `pilgrims/create.blade.php` - Enregistrement
  - [ ] `pilgrims/edit.blade.php` - Modification

- [ ] **Paiements**
  - [ ] `payments/index.blade.php` - Liste
  - [ ] `payments/show.blade.php` - Détails
  - [ ] `payments/create.blade.php` - Nouveau paiement

- [ ] **Rapports**
  - [ ] `reports/dashboard.blade.php` - Tableau de bord
  - [ ] `reports/campaigns.blade.php` - Rapports campagnes
  - [ ] `reports/financial.blade.php` - Rapports financiers

#### 3.4 Composants
- [ ] **Navigation** - Menu principal
- [ ] **Tables** - Composants tableaux réutilisables
- [ ] **Forms** - Composants formulaires
- [ ] **Cards** - Cartes statistiques
- [ ] **Modals** - Fenêtres modales

### 📦 PHASE 4 - PACKAGES ADDITIONNELS

#### 4.1 Packages utiles à installer
- [ ] `spatie/laravel-permission` - Gestion avancée permissions
- [ ] `maatwebsite/excel` - Export Excel
- [ ] `barryvdh/laravel-dompdf` - Génération PDF
- [ ] `spatie/laravel-medialibrary` - Gestion fichiers média
- [ ] `intervention/image` - Traitement images
- [ ] `laravel/telescope` - Debug et monitoring
- [ ] `spatie/laravel-backup` - Sauvegarde automatique

#### 4.2 Configuration packages
- [ ] **Configuration de chaque package**
- [ ] **Publication des configs et migrations**
- [ ] **Intégration dans les modèles existants**

### 🧪 PHASE 5 - TESTS & QUALITÉ

#### 5.1 Tests API
- [ ] **Tests d'authentification**
  - [ ] Login réussi/échoué
  - [ ] Logout
  - [ ] Accès routes protégées
- [ ] **Tests CRUD pour chaque entité**
  - [ ] Campagnes
  - [ ] Pèlerins
  - [ ] Paiements
  - [ ] Documents
- [ ] **Tests logique métier**
  - [ ] Calculs montants
  - [ ] Changements statuts
  - [ ] Upload documents

#### 5.2 Tests d'intégration
- [ ] **Workflow complet pèlerin**
- [ ] **Process paiement**
- [ ] **Upload et validation documents**

#### 5.3 Performance
- [ ] **Optimisation requêtes** (N+1 queries)
- [ ] **Mise en cache** données fréquentes
- [ ] **Pagination** listes importantes
- [ ] **Indexation** base de données

### 📚 PHASE 6 - DOCUMENTATION & DÉPLOIEMENT

#### 6.1 Documentation API
- [ ] **OpenAPI/Swagger** - Documentation interactive
- [ ] **Collection Postman** - Tests API
- [ ] **Guide d'intégration** pour développeurs
- [ ] **Exemples de code** frontend

#### 6.2 Documentation technique
- [ ] **README.md** - Installation et configuration
- [ ] **DEPLOYMENT.md** - Guide déploiement
- [ ] **API.md** - Documentation endpoints
- [ ] **DATABASE.md** - Structure base de données

#### 6.3 Déploiement
- [ ] **Configuration serveur production**
- [ ] **Variables d'environnement**
- [ ] **SSL/HTTPS**
- [ ] **Backup automatique**
- [ ] **Monitoring et logs**

### 🔧 PHASE 7 - MAINTENANCE & ÉVOLUTIONS

#### 7.1 Monitoring
- [ ] **Logs d'erreurs**
- [ ] **Métriques performance**
- [ ] **Surveillance uptime**
- [ ] **Rapports d'usage**

#### 7.2 Évolutions futures
- [ ] **API mobile native**
- [ ] **Notifications push**
- [ ] **Intégration paiement en ligne**
- [ ] **Module comptabilité avancée**
- [ ] **Gestion multi-agences**

---

## 🎯 ORDRE DE PRIORITÉ RECOMMANDÉ

1. **Semaine 1** : Phase 1.1 (Contrôleurs API) + Phase 1.4 (Routes)
2. **Semaine 2** : Phase 1.3 (Validation) + Phase 2.1 (Configuration)
3. **Semaine 3** : Phase 2.2 (Services) + Phase 2.3 (Seeders)
4. **Semaine 4** : Tests API + Documentation de base
5. **Semaines suivantes** : Interface web et fonctionnalités avancées

---

## 📝 NOTES IMPORTANTES

- **Tester chaque endpoint** au fur et à mesure
- **Valider avec données réelles** dès que possible
- **Documenter les choix techniques** importants
- **Prévoir la sauvegarde** des données de production
- **Former les utilisateurs** aux nouvelles fonctionnalités

**Status:** 🟡 En cours - Base Laravel créée, models et migrations terminés
**Prochaine étape:** Création des contrôleurs API