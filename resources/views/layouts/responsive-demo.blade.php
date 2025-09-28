<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Responsive - Hajj Management System</title>

    <!-- Import du framework responsive -->
    <link rel="stylesheet" href="{{ asset('css/responsive-framework.css') }}">

    <style>
        /* Styles spécifiques à cette démo */
        .demo-section {
            background: white;
            margin-bottom: var(--spacing-lg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .demo-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: var(--spacing-lg);
            text-align: center;
        }

        .demo-content {
            padding: var(--spacing-lg);
        }

        .color-demo {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-sm);
        }

        .bg-primary { background: #007bff; }
        .bg-success { background: #28a745; }
        .bg-warning { background: #ffc107; color: #333; }
        .bg-danger { background: #dc3545; }
        .bg-info { background: #17a2b8; }
        .bg-dark { background: #343a40; }

        .feature-card {
            background: #f8f9fa;
            padding: var(--spacing-md);
            border-radius: var(--radius-md);
            border-left: 4px solid #007bff;
            margin-bottom: var(--spacing-sm);
        }

        .stats-card {
            background: white;
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .stats-number {
            font-size: var(--font-size-xxxl);
            font-weight: 900;
            color: #007bff;
            margin-bottom: var(--spacing-xs);
        }

        .stats-label {
            font-size: var(--font-size-sm);
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="client-summary">
    <div class="responsive-container">
        <!-- Header de démonstration -->
        <div class="demo-section">
            <div class="demo-header">
                <h1 class="heading-responsive-1">🚀 Système Responsive</h1>
                <p class="text-responsive-lg">Framework CSS responsive pour Hajj Management System</p>
            </div>
        </div>

        <!-- Grille responsive -->
        <div class="demo-section">
            <div class="demo-content">
                <h2 class="heading-responsive-2">📊 Grille Responsive Auto</h2>
                <div class="responsive-grid grid-auto">
                    <div class="stats-card">
                        <div class="stats-number">1,247</div>
                        <div class="stats-label">Pèlerins</div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-number">₣850M</div>
                        <div class="stats-label">Paiements</div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-number">15</div>
                        <div class="stats-label">Campagnes</div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-number">98%</div>
                        <div class="stats-label">Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flexbox responsive -->
        <div class="demo-section">
            <div class="demo-content">
                <h2 class="heading-responsive-2">🎨 Flexbox Responsive</h2>
                <div class="flex-responsive-between">
                    <div class="w-responsive-50">
                        <h3 class="heading-responsive-3">Fonctionnalités</h3>
                        <div class="feature-card">
                            <strong>✅ Responsive Design</strong><br>
                            <span class="text-responsive-sm">S'adapte à tous les écrans</span>
                        </div>
                        <div class="feature-card">
                            <strong>⚡ Performance Optimisée</strong><br>
                            <span class="text-responsive-sm">CSS optimisé et léger</span>
                        </div>
                        <div class="feature-card">
                            <strong>📱 Mobile First</strong><br>
                            <span class="text-responsive-sm">Conçu d'abord pour mobile</span>
                        </div>
                    </div>
                    <div class="w-responsive-50">
                        <h3 class="heading-responsive-3">Couleurs</h3>
                        <div class="color-demo bg-primary">Primary (#007bff)</div>
                        <div class="color-demo bg-success">Success (#28a745)</div>
                        <div class="color-demo bg-warning">Warning (#ffc107)</div>
                        <div class="color-demo bg-danger">Danger (#dc3545)</div>
                        <div class="color-demo bg-info">Info (#17a2b8)</div>
                        <div class="color-demo bg-dark">Dark (#343a40)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards responsives -->
        <div class="demo-section">
            <div class="demo-content">
                <h2 class="heading-responsive-2">🃏 Cards Responsives</h2>
                <div class="responsive-grid grid-3">
                    <div class="responsive-card">
                        <h3 class="heading-responsive-3">💳 Paiements</h3>
                        <p class="text-responsive-base">Gestion complète des paiements avec reçus automatiques et historique détaillé.</p>
                        <button class="btn-responsive bg-primary" style="background: #007bff; color: white;">
                            Voir les paiements
                        </button>
                    </div>
                    <div class="responsive-card">
                        <h3 class="heading-responsive-3">👥 Pèlerins</h3>
                        <p class="text-responsive-base">Base de données complète des pèlerins avec documents et statuts.</p>
                        <button class="btn-responsive bg-success" style="background: #28a745; color: white;">
                            Gérer les pèlerins
                        </button>
                    </div>
                    <div class="responsive-card">
                        <h3 class="heading-responsive-3">🕌 Campagnes</h3>
                        <p class="text-responsive-base">Organisation des campagnes Hajj et Omra avec prix différenciés.</p>
                        <button class="btn-responsive bg-info" style="background: #17a2b8; color: white;">
                            Voir les campagnes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableaux responsifs -->
        <div class="demo-section">
            <div class="demo-content">
                <h2 class="heading-responsive-2">📋 Tableau Responsive</h2>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Campagne</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th class="hide-mobile">Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Ahmed DIALLO</strong></td>
                                <td>Hajj 2024</td>
                                <td>₣2,500,000</td>
                                <td><span style="background: #d1ecf1; color: #0c5460; padding: 4px 8px; border-radius: 12px; font-size: 12px;">✅ Payé</span></td>
                                <td class="hide-mobile">15/03/2024</td>
                                <td>
                                    <button class="btn-responsive" style="background: #f8f9fa; border: 1px solid #dee2e6; font-size: 12px; padding: 4px 8px;">
                                        Voir
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Fatou NDIAYE</strong></td>
                                <td>Omra Ramadan</td>
                                <td>₣1,200,000</td>
                                <td><span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 12px; font-size: 12px;">⏳ Partiel</span></td>
                                <td class="hide-mobile">12/03/2024</td>
                                <td>
                                    <button class="btn-responsive" style="background: #f8f9fa; border: 1px solid #dee2e6; font-size: 12px; padding: 4px 8px;">
                                        Voir
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Mamadou BA</strong></td>
                                <td>Hajj VIP 2024</td>
                                <td>₣4,000,000</td>
                                <td><span style="background: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 12px; font-size: 12px;">❌ Impayé</span></td>
                                <td class="hide-mobile">10/03/2024</td>
                                <td>
                                    <button class="btn-responsive" style="background: #f8f9fa; border: 1px solid #dee2e6; font-size: 12px; padding: 4px 8px;">
                                        Voir
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formulaire responsive -->
        <div class="demo-section">
            <div class="demo-content">
                <h2 class="heading-responsive-2">📝 Formulaire Responsive</h2>
                <form class="form-responsive">
                    <div class="responsive-grid grid-2">
                        <div class="form-group">
                            <label for="firstname">Prénom</label>
                            <input type="text" id="firstname" class="form-control" placeholder="Entrez le prénom">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Nom</label>
                            <input type="text" id="lastname" class="form-control" placeholder="Entrez le nom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="email@exemple.com">
                    </div>
                    <div class="responsive-grid grid-2">
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" class="form-control" placeholder="+221 77 123 45 67">
                        </div>
                        <div class="form-group">
                            <label for="campaign">Campagne</label>
                            <select id="campaign" class="form-control">
                                <option>Sélectionner une campagne</option>
                                <option>Hajj 2024</option>
                                <option>Omra Ramadan 2024</option>
                                <option>Hajj VIP 2024</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-responsive-center">
                        <button type="submit" class="btn-responsive" style="background: #007bff; color: white; padding: 12px 24px;">
                            💾 Enregistrer
                        </button>
                        <button type="reset" class="btn-responsive" style="background: #6c757d; color: white; padding: 12px 24px;">
                            🔄 Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Utilitaires responsive -->
        <div class="demo-section">
            <div class="demo-content">
                <h2 class="heading-responsive-2">🛠️ Utilitaires Responsive</h2>
                <div class="responsive-grid grid-2">
                    <div>
                        <h3 class="heading-responsive-3">Visibilité</h3>
                        <div class="p-responsive-md" style="background: #e9ecef; border-radius: 8px; margin-bottom: 10px;">
                            <div class="show-mobile" style="background: #dc3545; color: white; padding: 8px; border-radius: 4px;">
                                📱 Visible sur mobile uniquement
                            </div>
                            <div class="hide-mobile" style="background: #28a745; color: white; padding: 8px; border-radius: 4px;">
                                💻 Masqué sur mobile
                            </div>
                            <div class="show-tablet" style="background: #17a2b8; color: white; padding: 8px; border-radius: 4px; margin-top: 4px;">
                                📱 Visible sur tablette uniquement
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="heading-responsive-3">Typography</h3>
                        <div class="text-responsive-xxxl" style="color: #007bff;">Titre XXL</div>
                        <div class="text-responsive-xxl" style="color: #28a745;">Titre XL</div>
                        <div class="text-responsive-xl" style="color: #ffc107;">Titre Large</div>
                        <div class="text-responsive-lg" style="color: #dc3545;">Titre Medium</div>
                        <div class="text-responsive-base">Texte de base</div>
                        <div class="text-responsive-sm" style="color: #6c757d;">Texte petit</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions d'utilisation -->
        <div class="demo-section">
            <div class="demo-content">
                <h2 class="heading-responsive-2">📖 Guide d'utilisation</h2>
                <div class="responsive-grid grid-1">
                    <div class="feature-card">
                        <strong>1. Import du framework</strong><br>
                        <code style="background: #f8f9fa; padding: 2px 4px; border-radius: 4px;">
                            &lt;link rel="stylesheet" href="{{ asset('css/responsive-framework.css') }}"&gt;
                        </code>
                    </div>
                    <div class="feature-card">
                        <strong>2. Conteneur responsive</strong><br>
                        <code style="background: #f8f9fa; padding: 2px 4px; border-radius: 4px;">
                            &lt;div class="responsive-container"&gt;...&lt;/div&gt;
                        </code>
                    </div>
                    <div class="feature-card">
                        <strong>3. Grille responsive</strong><br>
                        <code style="background: #f8f9fa; padding: 2px 4px; border-radius: 4px;">
                            &lt;div class="responsive-grid grid-3"&gt;...&lt;/div&gt;
                        </code>
                    </div>
                    <div class="feature-card">
                        <strong>4. Composants</strong><br>
                        <code style="background: #f8f9fa; padding: 2px 4px; border-radius: 4px;">
                            &lt;div class="responsive-card"&gt;...&lt;/div&gt;
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script pour tester le responsive
        console.log('🚀 Framework Responsive chargé');

        // Afficher la taille d'écran actuelle
        function displayScreenSize() {
            const width = window.innerWidth;
            let device = '';

            if (width <= 480) device = '📱 Mobile XS';
            else if (width <= 576) device = '📱 Mobile';
            else if (width <= 768) device = '📱 Tablette';
            else if (width <= 992) device = '💻 Desktop';
            else if (width <= 1200) device = '💻 Desktop Large';
            else device = '🖥️ Desktop XL';

            console.log(`Taille d'écran: ${width}px (${device})`);
        }

        // Afficher la taille au chargement et au redimensionnement
        displayScreenSize();
        window.addEventListener('resize', displayScreenSize);
    </script>
</body>
</html>