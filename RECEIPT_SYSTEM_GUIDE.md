# 📄 Guide du Système de Reçus de Paiement

## 🎨 Templates Disponibles

Le système propose **2 templates de reçus** avec des designs différents :

### 1️⃣ **Reçu Premium** (Par défaut)
**Fichier:** `resources/views/payments/receipt-premium.blade.php`

#### ✨ Caractéristiques :
- 🎨 Design moderne et élégant avec gradients
- 🌈 En-tête coloré avec dégradé bleu
- 💎 Cards d'information avec icônes colorées
- 💰 Montant mis en valeur sur fond vert avec effet pulse
- 📊 Résumé financier complet (Total dû, Payé, Restant)
- 📋 Tableau historique des paiements avec style moderne
- ✍️ Sections signatures avec bordures élégantes
- 🎯 Footer professionnel sur fond noir
- 📱 Fully responsive (mobile, tablet, desktop)
- 🖨️ Optimisé pour l'impression

#### 🎨 Palette de couleurs :
- Primaire: Bleu (#2563eb)
- Secondaire: Vert (#10b981)
- Accent: Orange (#f59e0b)
- Fond: Blanc avec ombres subtiles

#### 📐 Layout :
- En-tête en dégradé avec logo et badge de reçu
- 3 cards d'information (Client, Campagne, Paiement)
- Zone de montant mise en évidence avec animation
- Tableau historique complet
- Signatures côte à côte
- Footer informatif

---

### 2️⃣ **Reçu Standard** (Compact)
**Fichier:** `resources/views/payments/receipt.blade.php`

#### ✨ Caractéristiques :
- 📄 Design classique et professionnel
- 🧭 Layout en 2 colonnes (gauche/droite)
- 💚 Résumé paiement sur fond vert
- 📋 Informations organisées en sections
- 📊 Mini tableau historique (8 derniers paiements)
- ✍️ Signatures simplifiées
- 🔍 Watermark logo en arrière-plan
- 📱 Responsive avec breakpoints optimisés
- 🖨️ Optimisé impression

#### 🎨 Style :
- Plus compact et dense en informations
- Header avec logo et informations d'entreprise
- Sections avec bordures colorées à gauche
- Tableau compact avec highlighting du paiement actuel

---

## 🚀 Utilisation

### Depuis l'interface web :

#### **1. Page détails du paiement**
```
Aller sur : Paiements > Voir détails d'un paiement
Cliquer sur : Bouton "Reçu Premium" (dropdown)
Choisir : Premium ou Standard
```

#### **2. Liste des paiements**
```
Aller sur : Paiements > Index
Cliquer sur : Icône reçu (dropdown)
Choisir : Premium ou Standard
```

### Via URL directe :

#### Reçu Premium (défaut) :
```
GET /payments/{id}/receipt
GET /payments/{id}/receipt?template=premium
```

#### Reçu Standard :
```
GET /payments/{id}/receipt?template=receipt
```

---

## 🔧 Configuration

### Variables d'agence (SystemSettings)

Les reçus utilisent les paramètres suivants de la table `system_settings` :

| Clé | Description | Utilisé dans |
|-----|-------------|--------------|
| `company_name` | Nom de l'agence | Header, Footer |
| `company_slogan` | Slogan/Tagline | Header Premium |
| `company_address` | Adresse complète | Header |
| `company_city` | Ville | Header |
| `company_phone` | Téléphone principal | Header, Footer |
| `company_phone2` | Téléphone secondaire | Contact |
| `company_email` | Email | Header, Footer |
| `company_website` | Site web | Footer |
| `company_logo` | Logo (chemin fichier) | Header, Watermark |
| `company_registration` | N° enregistrement | Footer |
| `currency_symbol` | Symbole monétaire | Montants |
| `legal_notice` | Mention légale | Footer |

---

## 📊 Données Affichées

### Informations Client/Pèlerin :
- ✅ Nom complet
- ✅ Email
- ✅ Téléphone
- ✅ Catégorie (Classic/VIP)

### Informations Campagne :
- ✅ Nom de la campagne
- ✅ Type (Hajj/Omra)
- ✅ Dates départ/retour
- ✅ Année hijri/grégorienne

### Détails Paiement :
- ✅ Montant encaissé
- ✅ Date du paiement
- ✅ Mode de paiement
- ✅ Référence
- ✅ Statut

### Résumé Financier :
- ✅ Total dû
- ✅ Total payé
- ✅ Reste à payer
- ✅ Pourcentage payé

### Historique :
- ✅ Liste de tous les paiements du pèlerin
- ✅ Highlighting du paiement actuel
- ✅ Dates, montants, modes, statuts

### Signatures :
- ✅ Espace signature client
- ✅ Espace cachet agence
- ✅ Nom du personnel ayant servi

---

## 🎨 Personnalisation

### Changer les couleurs (Premium) :

Éditer `receipt-premium.blade.php` :

```css
:root {
    --primary: #2563eb;      /* Bleu principal */
    --primary-dark: #1e40af; /* Bleu foncé */
    --secondary: #10b981;    /* Vert */
    --accent: #f59e0b;       /* Orange */
    --dark: #1f2937;         /* Texte foncé */
    --light: #f9fafb;        /* Fond clair */
}
```

### Ajouter un nouveau template :

1. **Créer le fichier** : `resources/views/payments/receipt-custom.blade.php`

2. **Modifier le contrôleur** : `app/Http/Controllers/Web/PaymentController.php`

```php
public function generateReceipt(Payment $payment)
{
    // ...

    $template = request()->get('template', 'premium');

    $viewName = match($template) {
        'premium' => 'payments.receipt-premium',
        'standard' => 'payments.receipt',
        'custom' => 'payments.receipt-custom',  // ← Ajouter ici
        default => 'payments.receipt-premium',
    };

    // ...
}
```

3. **Ajouter dans les dropdowns** des vues `index.blade.php` et `show.blade.php`

---

## 📱 Responsive Breakpoints

### Mobile (< 576px)
- ✅ Layout 1 colonne
- ✅ Font-size réduite
- ✅ Padding compact
- ✅ Éléments empilés verticalement

### Tablet (576px - 768px)
- ✅ Layout 2 colonnes adaptatif
- ✅ Font-size intermédiaire
- ✅ Navigation optimisée

### Desktop (> 768px)
- ✅ Layout complet
- ✅ Toutes les fonctionnalités visibles
- ✅ Effets hover

---

## 🖨️ Impression

Les deux templates sont optimisés pour l'impression :

### Optimisations Print :
- ✅ Suppression des ombres
- ✅ Couleurs optimisées pour noir & blanc
- ✅ Marges adaptées format A4
- ✅ Font-sizes ajustées
- ✅ Suppression des éléments interactifs

### Formats supportés :
- 📄 PDF (via DomPDF)
- 🖨️ Impression directe navigateur
- 💾 Téléchargement PDF

---

## 🔐 Sécurité

### Authentification :
- ✅ Accès restreint aux utilisateurs connectés
- ✅ Gate policy : `view-reports`
- ✅ Vérification propriété du paiement

### Données sensibles :
- ⚠️ Pas de données bancaires complètes
- ⚠️ Référence paiement seulement si existe
- ⚠️ Numéro reçu unique généré

---

## 📈 Évolutions Futures

### Fonctionnalités planifiées :
- [ ] QR Code sur le reçu (vérification authenticité)
- [ ] Signature électronique
- [ ] Envoi automatique par email
- [ ] Multi-langues (FR/EN/AR)
- [ ] Template builder visuel
- [ ] Statistiques d'impression
- [ ] Watermark "COPIE" pour les réimpressions
- [ ] Export multi-format (PNG, JPG)

---

## 🐛 Dépannage

### Le reçu ne se génère pas :
1. Vérifier DomPDF installé : `composer require dompdf/dompdf`
2. Vérifier permissions storage : `chmod -R 775 storage`
3. Vérifier logs Laravel : `storage/logs/laravel.log`

### Les couleurs ne s'affichent pas en PDF :
- DomPDF a des limitations CSS
- Utiliser propriétés CSS supportées
- Éviter `backdrop-filter`, `clip-path`, etc.

### Le logo ne s'affiche pas :
- Vérifier le chemin : `storage/app/public/logos/`
- Vérifier le lien symbolique : `php artisan storage:link`
- Utiliser base64 pour les images dans le PDF

---

## 📞 Support

Pour toute question ou demande d'assistance :

**Email :** admin@hajj.com
**Documentation :** Voir fichier `DOCUMENTATION_SYSTEME.md`

---

## 📝 Changelog

### Version 1.0.0 (29/09/2025)
- ✅ Création template Premium moderne
- ✅ Amélioration template Standard
- ✅ Système de sélection de template
- ✅ Responsive complet
- ✅ Optimisation impression
- ✅ Documentation complète

---

**Développé avec ❤️ par Claude Code**