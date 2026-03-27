<?php
    require('actions/verifierUtilisateurConnecte.php');
    require ('frais/rechercher.php');
    require ('frais/enregistrer.php');
    require ('frais/tableFrais.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Frais Scolaires</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="BarreDeNavigation.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Frais.css?v=<?php echo time(); ?>">
    <style>
        /* Correction pour l'espace entre la barre de navigation et le contenu */
        .main-content {
            padding-top: 0;
            margin-top: 0;
        }
        
        .page-header {
            margin-top: 0;
        }
        
        /* Amélioration de l'affichage pour les petits écrans */
        @media (max-width: 992px) {
            .main-content {
                padding-top: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <?php include('BarreDeNavigation.php'); ?>
    
    <!-- Contenu principal -->
    <main class="main-content">
        <!-- Header -->
        <div class="page-header">
            <h1><i class="fas fa-money-bill-wave"></i> Gestion des Frais Scolaires</h1>
            <p class="page-description">Suivi et gestion des paiements des frais scolaires</p>
        </div>
        
        <!-- Messages -->
        <?php if(isset($errorMsg) || isset($succesMsg)): ?>
        <div class="alert-container">
            <?php if(isset($errorMsg)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $errorMsg; ?></span>
                </div>
            <?php endif; ?>
            <?php if(isset($succesMsg)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $succesMsg; ?></span>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Student Search Card -->
        <div class="card search-card">
            <div class="card-header">
                <h2><i class="fas fa-search"></i> Rechercher un Élève</h2>
            </div>
            <div class="card-body">
                <form method="POST" class="search-form">
                    <div class="search-container">
                        <div class="search-input-group">
                            <div class="input-with-icon">
                                <i class="fas fa-id-card"></i>
                                <input type="text" name="matriculeRecherche" id="champsRecherche" 
                                       placeholder="Entrez le matricule ou nom de l'élève" autocomplete="off"
                                       value="<?php echo isset($_POST['matriculeRecherche']) ? htmlspecialchars($_POST['matriculeRecherche']) : ''; ?>">
                            </div>
                            <button class="btn btn-primary btn-search" name="validateRechercher" id="btnRechercher">
                                <i class="fas fa-search"></i> RECHERCHER
                            </button>
                        </div>
                        
                        <div class="year-selection">
                            <div class="form-group">
                                <label for="anneeScolaire"><i class="fas fa-calendar-alt"></i> Année Scolaire</label>
                                <div class="select-wrapper">
                                    <select name="annee" id="anneeScolaire">
                                        <option value="2025 - 2026" <?php echo (isset($annee) && $annee == '2025 - 2026')?'selected' : ''; ?>>2025 - 2026</option>
                                        <option value="2026 - 2027" <?php echo (isset($annee) && $annee == '2026 - 2027')?'selected' : ''; ?>>2026 - 2027</option>
                                        <option value="2027 - 2028" <?php echo (isset($annee) && $annee == '2027 - 2028')?'selected' : ''; ?>>2027 - 2028</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if(isset($matricule) && !empty($matricule)): ?>
        <!-- Student Info Card -->
        <div class="card student-info-card">
            <div class="card-header">
                <h2><i class="fas fa-user-graduate"></i> Informations de l'Élève</h2>
            </div>
            <div class="card-body">
                <div class="student-info-grid">
                    <div class="info-item">
                        <label><i class="fas fa-hashtag"></i> Matricule</label>
                        <div class="info-value badge"><?php echo htmlspecialchars($matricule); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <label><i class="fas fa-user"></i> Nom</label>
                        <div class="info-value"><?php echo htmlspecialchars($nom); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <label><i class="fas fa-users"></i> Post-nom</label>
                        <div class="info-value"><?php echo htmlspecialchars($postNom); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <label><i class="fas fa-graduation-cap"></i> Classe</label>
                        <div class="info-value">
                            <span class="class-badge"><?php echo htmlspecialchars($classe); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Financial Info Card -->
        <div class="card financial-card">
            <div class="card-header">
                <h2><i class="fas fa-chart-line"></i> Situation Financière</h2>
            </div>
            <div class="card-body">
                <form method="POST" id="paymentForm">
                    <input type="hidden" name="matricule_hidden" value="<?php echo htmlspecialchars($matricule); ?>">
                    
                    <div class="financial-grid">
                        <!-- Financial Summary -->
                        <div class="financial-summary">
                            <div class="summary-item total">
                                <div class="summary-icon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="summary-content">
                                    <label>Frais totaux</label>
                                    <div class="input-group">
                                        <span class="currency">$</span>
                                        <input type="text" name="fraisTotal" id="montantTotal" 
                                               value="<?php echo htmlspecialchars($montantTotal); ?>" 
                                               placeholder="0.00" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="summary-item paid">
                                <div class="summary-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="summary-content">
                                    <label>Montant payé</label>
                                    <div class="amount-display">
                                        <span class="currency">$</span>
                                        <span class="amount"><?php echo number_format($montantPaye, 2); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="summary-item remaining">
                                <div class="summary-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="summary-content">
                                    <label>Reste à payer</label>
                                    <div class="amount-display">
                                        <span class="currency">$</span>
                                        <span class="amount"><?php echo number_format($restePaye, 2); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Input -->
                        <div class="payment-input">
                            <h3><i class="fas fa-credit-card"></i> Nouveau Paiement</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="montantAPaye"><i class="fas fa-money-bill"></i> Montant à payer</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                        <input type="text" name="montantAPaye" id="montantAPaye" 
                                               placeholder="Entrez le montant payé" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="date"><i class="fas fa-calendar"></i> Date de paiement</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-calendar-day"></i>
                                        <input type="date" name="date" id="date" 
                                               value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="payment-actions">
                                <button type="submit" name="validateEnregistrer" id="btnEnregistrer" class="btn btn-success">
                                    <i class="fas fa-floppy-disk"></i> ENREGISTRER LE PAIEMENT
                                </button>
                                <button type="button" class="btn btn-info" name="validateImprimer" onclick="window.print()">
                                    <i class="fas fa-print"></i> IMPRIMER LE REÇU
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Actions Card -->
        <div class="card actions-card">
            <div class="card-header">
                <h2><i class="fas fa-cogs"></i> Actions Rapides</h2>
            </div>
            <div class="card-body">
                <div class="action-buttons">
                    <button class="btn-action btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> NOUVELLE RECHERCHE
                    </button>
                    <button class="btn-action btn-info" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> EXPORT PDF
                    </button>
                    <button class="btn-action btn-warning">
                        <i class="fas fa-box-archive"></i> ARCHIVER
                    </button>
                    <a href="RapportFrais.php" class="btn-action btn-primary">
                        <i class="fas fa-chart-bar"></i> RAPPORTS
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Payment History Card -->
        <?php if(isset($_POST['validateRechercher']) && isset($historique)): ?>
        <div class="card history-card">
            <div class="card-header">
                <h2><i class="fas fa-history"></i> Historique des Paiements</h2>
                <div class="stats-badge">
                    <i class="fas fa-receipt"></i>
                    <span>Total : <strong><?php echo $nombreHistorique; ?></strong> paiements</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar"></i> Date du paiement</th>
                                <th><i class="fas fa-money-bill-wave"></i> Montant versé</th>
                                <th><i class="fas fa-receipt"></i> Référence</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($nombreHistorique > 0): ?>
                                <?php 
                                // Inverser l'ordre pour afficher le premier paiement en premier
                                $historique_reverse = array_reverse($historique);
                                foreach($historique_reverse as $index => $historiques): 
                                ?>
                                <tr class="<?php echo $index % 2 == 0 ? 'even' : 'odd'; ?>">
                                    <td>
                                        <div class="date-cell">
                                            <i class="fas fa-calendar-check"></i>
                                            <?php echo date('d/m/Y', strtotime($historiques['date_paiement'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="amount-cell">
                                            <span class="currency">$</span>
                                            <span class="amount"><?php echo number_format($historiques['montant_verse'], 2); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="reference">PAY-<?php echo str_pad($nombreHistorique - $index, 3, '0', STR_PAD_LEFT); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="empty-row">
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <i class="fas fa-receipt fa-3x"></i>
                                            <h3>Aucun historique enregistré pour cet élève</h3>
                                            <p>Les paiements apparaîtront ici après enregistrement</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Validation Message -->
        <div id="message" class="validation-message"></div>
        
        <!-- Statistiques résumées -->
        <?php if(isset($matricule) && !empty($matricule)): ?>
        <div class="stats-summary">
            <div class="stat-item">
                <i class="fas fa-wallet"></i>
                <div>
                    <h3>Total payé</h3>
                    <p>$<?php echo number_format($montantPaye, 2); ?></p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h3>Premier paiement</h3>
                    <p>
                        <?php 
                        if(isset($historique) && count($historique) > 0) {
                            // Afficher le premier paiement (le plus ancien)
                            $first_payment = end($historique); // Récupérer le dernier élément car historique est dans l'ordre inverse
                            echo date('d/m/Y', strtotime($first_payment['date_paiement']));
                        } else {
                            echo '--/--/----';
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-percentage"></i>
                <div>
                    <h3>Progression</h3>
                    <p><?php echo $montantTotal > 0 ? number_format(($montantPaye / $montantTotal) * 100, 1) : 0; ?>%</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>
    
    <script>
        // ===== VALIDATION DU FORMULAIRE =====
        document.getElementById("btnEnregistrer")?.addEventListener("click", function(e) {
            const MontantTotal = document.getElementById("montantTotal").value.trim();
            const MontantPaye = document.getElementById("montantAPaye").value.trim();
            const DateField = document.getElementById("date").value.trim();
            const Message = document.getElementById("message");
            
            Message.textContent = "";
            Message.className = "validation-message";
            
            // Validation du montant total
            if(MontantTotal === "") {
                Message.textContent = "❌ Veuillez entrer le montant total";
                Message.classList.add("error");
                e.preventDefault();
                return false;
            }
            
            if(!/^\d+(\.\d{1,2})?$/.test(MontantTotal)) {
                Message.textContent = "❌ Le montant total doit être un nombre (ex: 100 ou 100.50)";
                Message.classList.add("error");
                e.preventDefault();
                return false;
            }
            
            // Validation du montant payé
            if(MontantPaye === "") {
                Message.textContent = "❌ Veuillez entrer le montant payé";
                Message.classList.add("error");
                e.preventDefault();
                return false;
            }
            
            if(!/^\d+(\.\d{1,2})?$/.test(MontantPaye)) {
                Message.textContent = "❌ Le montant payé doit être un nombre (ex: 100 ou 100.50)";
                Message.classList.add("error");
                e.preventDefault();
                return false;
            }
            
            const total = parseFloat(MontantTotal);
            const payment = parseFloat(MontantPaye);
            const paidAmount = parseFloat("<?php echo $montantPaye; ?>") || 0;
            
            if((paidAmount + payment) > total) {
                Message.textContent = "❌ Le montant payé ne peut pas dépasser le montant total";
                Message.classList.add("error");
                e.preventDefault();
                return false;
            }
            
            // Validation de la date
            if(DateField === "") {
                Message.textContent = "❌ Veuillez sélectionner une date";
                Message.classList.add("error");
                e.preventDefault();
                return false;
            }
            
            // Vérifier que l'élève est bien recherché
            const matricule = "<?php echo isset($matricule) ? $matricule : ''; ?>";
            if(!matricule) {
                Message.textContent = "❌ Veuillez d'abord rechercher un élève";
                Message.classList.add("error");
                e.preventDefault();
                return false;
            }
            
            Message.textContent = "✅ Validation réussie ! Enregistrement en cours...";
            Message.classList.add("success");
            
            // Animation de chargement
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ENREGISTREMENT...';
            this.disabled = true;
            
            return true;
        });
        
        // Formatage automatique des montants
        document.querySelectorAll('input[type="text"][id*="montant"]').forEach(input => {
            input.addEventListener('blur', function() {
                if(this.value && !isNaN(this.value)){
                    const value = parseFloat(this.value);
                    this.value = value.toFixed(2);
                }
            });
            
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
                const parts = this.value.split('.');
                if(parts.length > 2){
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }
                if(parts.length === 2 && parts[1].length > 2){
                    this.value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            });
        });
        
        // Auto-focus sur le champ de recherche
        document.addEventListener('DOMContentLoaded', function() {
            const searchField = document.getElementById('champsRecherche');
            if(searchField){
                setTimeout(() => searchField.focus(), 300);
            }
            
            // Mettre à jour la date du jour
            const dateField = document.getElementById('date');
            if(dateField && !dateField.value){
                const today = new Date();
                dateField.value = today.toISOString().split('T')[0];
            }
        });
        
        // Fonctions utilitaires
        function resetForm() {
            window.location.href = 'Frais.php';
        }
        
        function exportToPDF() {
            alert('Fonction d\'export PDF à implémenter');
        }
    </script>
</body>
</html>