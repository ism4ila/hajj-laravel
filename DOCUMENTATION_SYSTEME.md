# 📋 Documentation du Système de Gestion Hajj

## 🔐 Système d'Authentification et Connexion

### Accès au Système
- **URL de connexion**: `http://localhost/hajj/hajj-laravel/login`
- **Redirection après connexion**: Dashboard principal
- **Session sécurisée**: Authentification Laravel avec protection CSRF

### 🧪 Comptes de Test Disponibles

| Nom | Email | Mot de passe | Statut |
|-----|-------|--------------|---------|
| **Ahmed El Mansouri** | `ahmed.mansouri@email.com` | `password` | ✅ Administrateur |
| **Fatima Zahra** | `fatima.zahra@email.com` | `password` | ✅ Administrateur |
| **Hassan Ben Ali** | `hassan.benali@email.com` | `password` | ✅ Administrateur |
| **Khadija Bennani** | `khadija.bennani@email.com` | `password` | ✅ Administrateur |
| **Omar El Fassi** | `omar.elfassi@email.com` | `password` | ✅ Administrateur |

> **Note**: Tous les comptes de test ont des privilèges administrateur complets.

### Comptes Système Existants
| Nom | Email | Statut |
|-----|-------|---------|
| **Administrateur** | `admin@hajj.com` | ✅ Administrateur |
| **Ismael Hamadou** | `ismailahamadou5@gmail.com` | ✅ Administrateur |

---

## 🏠 Dashboard Principal

Le dashboard fournit une vue d'ensemble complète du système :
- **Statistiques générales** : Nombre de clients, pèlerins, campagnes, paiements
- **Graphiques** : Évolution des inscriptions et des paiements
- **Accès rapide** : Liens vers toutes les fonctionnalités principales
- **Interface responsive** : Optimisé pour mobile et desktop

---

## 🎯 Fonctionnalités Principales

### 1. 👥 Gestion des Clients
**URL**: `/clients`

#### Fonctionnalités disponibles :
- ✅ **Liste des clients** avec pagination et recherche avancée
- ✅ **Création de nouveaux clients** avec formulaire responsive
- ✅ **Modification des informations** client
- ✅ **Visualisation détaillée** du profil client
- ✅ **Suppression** avec confirmation
- ✅ **Recherche AJAX** en temps réel
- ✅ **Filtrage par département** et statut
- ✅ **Actions groupées** (activation/désactivation en masse)
- ✅ **Export Excel** de la liste des clients
- ✅ **Interface responsive** optimisée pour tous les écrans

#### Actions spécifiques :
- `GET /clients` - Liste paginée des clients
- `GET /clients/create` - Formulaire de création
- `POST /clients` - Enregistrement nouveau client
- `GET /clients/{id}` - Profil détaillé du client
- `GET /clients/{id}/edit` - Formulaire de modification
- `PUT /clients/{id}` - Mise à jour du client
- `DELETE /clients/{id}` - Suppression du client
- `POST /clients/{id}/toggle-status` - Activer/désactiver client
- `POST /clients/bulk-action` - Actions groupées

### 2. 🕌 Gestion des Campagnes
**URL**: `/campaigns`

#### Fonctionnalités disponibles :
- ✅ **Création de campagnes Hajj/Omra** avec dates et tarifs
- ✅ **Gestion des quotas** et places disponibles
- ✅ **Activation/désactivation** des campagnes
- ✅ **Suivi des inscriptions** par campagne
- ✅ **Rapports détaillés** par campagne

#### Actions spécifiques :
- `GET /campaigns` - Liste des campagnes
- `POST /campaigns` - Création nouvelle campagne
- `GET /campaigns/{id}` - Détails de la campagne
- `PUT /campaigns/{id}` - Modification campagne
- `POST /campaigns/{id}/activate` - Activer campagne
- `POST /campaigns/{id}/deactivate` - Désactiver campagne

### 3. 🧑‍🤝‍🧑 Gestion des Pèlerins
**URL**: `/pilgrims`

#### Fonctionnalités disponibles :
- ✅ **Inscription des pèlerins** avec informations complètes
- ✅ **Assignation aux campagnes** et clients
- ✅ **Gestion des documents** requis (passeport, photos, certificats)
- ✅ **Suivi du statut** des dossiers
- ✅ **Import/Export Excel** pour traitement en masse
- ✅ **Historique complet** des modifications

#### Actions spécifiques :
- `GET /pilgrims` - Liste des pèlerins
- `POST /pilgrims` - Nouvel enregistrement
- `GET /pilgrims/{id}` - Dossier complet du pèlerin
- `PUT /pilgrims/{id}` - Mise à jour informations
- `GET /pilgrims/export/excel` - Export Excel
- `POST /pilgrims/import/excel` - Import Excel

### 4. 💰 Gestion des Paiements
**URL**: `/payments`

#### Fonctionnalités disponibles :
- ✅ **Enregistrement des paiements** avec méthodes multiples
- ✅ **Génération de reçus** automatique (multiple formats)
- ✅ **Suivi des échéances** et relances
- ✅ **Rapports financiers** détaillés
- ✅ **Gestion des remboursements** et ajustements
- ✅ **Reçus responsive** optimisés pour impression

#### Formats de reçus disponibles :
- **Reçu standard** : Format classique avec logo
- **Reçu v2** : Design moderne avec gradients
- **Résumé client** : Vue claire des montants par client

#### Actions spécifiques :
- `GET /payments` - Liste des paiements
- `POST /payments` - Nouveau paiement
- `GET /payments/{id}/receipt` - Génération reçu
- `PUT /payments/{id}` - Modification paiement

### 5. 📄 Gestion des Documents
**URL**: `/pilgrims/{id}/documents`

#### Fonctionnalités disponibles :
- ✅ **Upload de documents** (passeport, photos, certificats médicaux)
- ✅ **Validation des documents** avec checklist
- ✅ **Téléchargement sécurisé** des fichiers
- ✅ **Suppression contrôlée** des documents
- ✅ **Statut de complétude** du dossier

#### Types de documents gérés :
- Passeport (copie et scan)
- Photos d'identité
- Certificat médical
- Certificat de vaccination
- Pièces d'identité complémentaires

### 6. 📊 Rapports et Statistiques
**URL**: `/reports`

#### Rapports disponibles :
- ✅ **Rapport campagnes** : Statistiques par campagne
- ✅ **Rapport clients** : Analyse de la clientèle
- ✅ **Rapport paiements** : Suivi financier
- ✅ **Rapport pèlerins** : Statut des dossiers
- ✅ **Rapport documents** : Complétude des dossiers

#### Exports disponibles :
- Excel avec données filtrées
- PDF pour impression
- Graphiques et visualisations

### 7. ⚙️ Paramètres Système
**URL**: `/settings`

#### Configuration disponible :
- ✅ **Informations entreprise** (nom, logo, adresse)
- ✅ **Paramètres généraux** du système
- ✅ **Configuration des notifications**
- ✅ **Gestion des devises** et taux de change

### 8. 👤 Gestion du Profil
**URL**: `/profile`

#### Fonctionnalités utilisateur :
- ✅ **Modification du profil** (nom, email)
- ✅ **Changement de mot de passe** sécurisé
- ✅ **Gestion des préférences** utilisateur
- ✅ **Suppression de compte** (avec confirmation)

---

## 🔄 API et Intégrations

### API REST Disponible
Le système dispose d'une API complète pour intégrations externes :

#### Endpoints principaux :
- `POST /api/auth/login` - Authentification API
- `GET /api/pilgrims` - Liste des pèlerins
- `POST /api/payments` - Création paiement
- `GET /api/campaigns` - Campagnes actives
- `GET /api/reports/{type}` - Génération rapports

---

## 🚀 Fonctionnalités Techniques

### Responsive Design
- ✅ **Mobile-first** : Optimisé pour smartphones
- ✅ **Breakpoints** : 320px → 1400px+
- ✅ **Touch-friendly** : Boutons 44px minimum
- ✅ **Grilles CSS** : Layouts adaptatifs
- ✅ **Typography fluide** : clamp() pour textes

### Performance et UX
- ✅ **Auto-save** : Sauvegarde automatique des formulaires
- ✅ **Validation temps réel** : Feedback instantané
- ✅ **Animations** : Transitions fluides
- ✅ **Search AJAX** : Recherche instantanée
- ✅ **Pagination** : Navigation optimisée

### Sécurité
- ✅ **Authentification Laravel** : Session sécurisée
- ✅ **Protection CSRF** : Tokens anti-forgery
- ✅ **Validation serveur** : Contrôles stricts
- ✅ **Permissions** : Système de rôles
- ✅ **Logs d'audit** : Traçabilité des actions

---

## 🎨 Interface Utilisateur

### Design System
- **Framework** : Bootstrap 5 + CSS custom
- **Couleurs** : Palette moderne avec gradients
- **Typography** : Inter + DejaVu Sans
- **Icons** : Font Awesome + Heroicons
- **Animations** : AOS (Animate On Scroll)

### Composants Réutilisables
- Cards statistiques animées
- Formulaires multi-sections
- Tables responsives
- Modales de confirmation
- Notifications toast
- Progress indicators

---

## 🔧 Guide de Test

### Scénarios de Test Recommandés

#### 1. Test Authentification
1. Utiliser un des comptes de test
2. Vérifier redirection vers dashboard
3. Tester déconnexion

#### 2. Test Gestion Clients
1. Créer un nouveau client
2. Modifier ses informations
3. Rechercher dans la liste
4. Exporter en Excel

#### 3. Test Paiements
1. Créer un paiement
2. Générer le reçu
3. Tester impression
4. Vérifier responsive mobile

#### 4. Test Responsive
1. Ouvrir sur mobile (320px)
2. Vérifier navigation
3. Tester formulaires
4. Valider touch targets

---

## 📞 Support et Maintenance

### Informations Techniques
- **Laravel Version** : 10.x
- **PHP Version** : 8.1+
- **Database** : MySQL 8.0
- **Frontend** : Blade + Bootstrap 5
- **Server** : Apache (XAMPP)

### Logs et Debugging
- **Application logs** : `storage/logs/laravel.log`
- **Error reporting** : Activé en développement
- **Debug bar** : Disponible pour débogage

---

## 🎯 Bonnes Pratiques d'Utilisation

### Pour les Administrateurs
1. **Sauvegarde régulière** de la base de données
2. **Mise à jour des campagnes** selon calendrier Hajj
3. **Vérification des documents** avant validation
4. **Export périodique** des données importantes

### Pour les Utilisateurs
1. **Connexion sécurisée** uniquement
2. **Déconnexion** après chaque session
3. **Vérification des données** avant validation
4. **Signalement** des anomalies

---

*Documentation générée le 28 septembre 2025*
*Version système : 1.0.0*
*Contact support : admin@hajj.com*