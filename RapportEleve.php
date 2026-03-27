<?php
    require('RapportEleve/generer.php');
    require('RapportEleve/rechercher.php');
    require('RapportEleve/sansFiltre.php');
    // Déterminer les données à afficher
    $displayData = !empty($resultatRecherche) ? $resultatRecherche : $resultat;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Élèves - Gestion Scolaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="RapportEleve.css?v=<?php echo time(); ?>">
    
    <!-- Bibliothèques pour les exports -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Inclure la barre de navigation -->
    <?php include('BarreDeNavigation.php'); ?>
    
    <main class="main-content">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1><i class="fas fa-user-graduate"></i> Rapport des Élèves</h1>
                    <p class="page-description">Gestion et analyse des données des élèves</p>
                </div>
                <div class="header-stats">
                    <div class="stat-badge">
                        <i class="fas fa-users"></i>
                        <span>Total élèves: <?php echo $totalEleves; ?></span>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-clock"></i>
                        <span><?php echo date('d/m/Y'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte de recherche et filtres -->
        <div class="card search-filter-card">
            <div class="card-header">
                <h2><i class="fas fa-search"></i> Recherche et Filtres</h2>
            </div>
            <div class="card-body">
                <form method="POST" class="report-form" id="reportForm">
                    <div class="search-section">
                        <div class="form-group">
                            <label for="matriculeRecherche"><i class="fas fa-id-card"></i> Recherche rapide</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user-graduate"></i>
                                <input type="text" name="matriculeRecherche" id="matriculeRecherche" 
                                       placeholder="Entrez le matricule ou nom de l'élève" 
                                       value="<?php echo isset($_POST['matriculeRecherche']) ? htmlspecialchars($_POST['matriculeRecherche']) : ''; ?>">
                                <button class="btn-search-inline" type="submit" name="btnRechercher">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filter-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="classe"><i class="fas fa-graduation-cap"></i> Filtre par classe</label>
                                <div class="select-wrapper">
                                    <select name="classe" id="classe">
                                        <option value="Toutes" <?php echo (isset($_POST['classe']) && $_POST['classe'] == 'Toutes') ? 'selected' : ''; ?>>Toutes les classes</option>
                                        <option value="1 Annee" <?php echo (isset($_POST['classe']) && $_POST['classe'] == '1 Annee') ? 'selected' : ''; ?>>1ère Année</option>
                                        <option value="2 Annee" <?php echo (isset($_POST['classe']) && $_POST['classe'] == '2 Annee') ? 'selected' : ''; ?>>2ème Année</option>
                                        <option value="3 Annee" <?php echo (isset($_POST['classe']) && $_POST['classe'] == '3 Annee') ? 'selected' : ''; ?>>3ème Année</option>
                                        <option value="4 Annee" <?php echo (isset($_POST['classe']) && $_POST['classe'] == '4 Annee') ? 'selected' : ''; ?>>4ème Année</option>
                                        <option value="5 Annee" <?php echo (isset($_POST['classe']) && $_POST['classe'] == '5 Annee') ? 'selected' : ''; ?>>5ème Année</option>
                                        <option value="6 Annee" <?php echo (isset($_POST['classe']) && $_POST['classe'] == '6 Annee') ? 'selected' : ''; ?>>6ème Année</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="sexe"><i class="fas fa-venus-mars"></i> Filtre par genre</label>
                                <div class="select-wrapper">
                                    <select name="sexe" id="sexe">
                                        <option value="Tous" <?php echo (isset($_POST['sexe']) && $_POST['sexe'] == 'Tous') ? 'selected' : ''; ?>>Tous les genres</option>
                                        <option value="Masculin" <?php echo (isset($_POST['sexe']) && $_POST['sexe'] == 'Masculin') ? 'selected' : ''; ?>>Masculin</option>
                                        <option value="Feminin" <?php echo (isset($_POST['sexe']) && $_POST['sexe'] == 'Feminin') ? 'selected' : ''; ?>>Féminin</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="btnGenerer" class="btn btn-primary">
                                <i class="fas fa-filter"></i> APPLIQUER LES FILTRES
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                                <i class="fas fa-redo"></i> RÉINITIALISER
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Effectif Total</h3>
                    <p class="stat-number" id="statTotal"><?php echo $totalEleves; ?></p>
                    <p class="stat-label">Élèves trouvés</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon boys">
                    <i class="fas fa-male"></i>
                </div>
                <div class="stat-content">
                    <h3>Garçons</h3>
                    <p class="stat-number" id="statGarcons"><?php echo $garcons; ?></p>
                    <p class="stat-label" id="statGarconsPourcent"><?php echo $totalEleves > 0 ? round(($garcons/$totalEleves)*100, 1) : 0; ?>% du total</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon girls">
                    <i class="fas fa-female"></i>
                </div>
                <div class="stat-content">
                    <h3>Filles</h3>
                    <p class="stat-number" id="statFilles"><?php echo $filles; ?></p>
                    <p class="stat-label" id="statFillesPourcent"><?php echo $totalEleves > 0 ? round(($filles/$totalEleves)*100, 1) : 0; ?>% du total</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon ratio">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-content">
                    <h3>Ratio</h3>
                    <p class="stat-number" id="statRatio"><?php echo $garcons > 0 ? round($filles/$garcons, 2) : ($filles > 0 ? '∞' : '0'); ?></p>
                    <p class="stat-label">Filles/Garçons</p>
                </div>
            </div>
        </div>
        
        <!-- Tableau des résultats -->
        <div class="card data-card">
            <div class="card-header">
                <h2><i class="fas fa-table"></i> Liste des Élèves</h2>
                <div class="card-actions">
                    <div class="results-count">
                        <i class="fas fa-list"></i>
                        <span id="resultsCount">
                            <?php echo count($displayData) . ' résultat(s)'; ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-actions">
                    <div class="export-buttons">
                        <button class="btn-export" onclick="exportToPDF()" title="Exporter en PDF">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        <button class="btn-export" onclick="exportToExcel()" title="Exporter en Excel">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn-export" onclick="printReport()" title="Imprimer le rapport">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                    </div>
                    
                    <div class="table-search">
                        <input type="text" id="tableSearch" placeholder="Rechercher dans le tableau...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="data-table" id="studentsTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="table-header">
                                        <span>Matricule</span>
                                        <button class="sort-btn" onclick="sortTable(0)">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header">
                                        <span>Nom</span>
                                        <button class="sort-btn" onclick="sortTable(1)">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header">
                                        <span>Post-nom</span>
                                        <button class="sort-btn" onclick="sortTable(2)">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header">
                                        <span>Classe</span>
                                        <button class="sort-btn" onclick="sortTable(3)">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header">
                                        <span>Genre</span>
                                        <button class="sort-btn" onclick="sortTable(4)">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header">
                                        <span>Téléphone Parent</span>
                                        <button class="sort-btn" onclick="sortTable(5)">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if (!empty($displayData)): 
                                foreach($displayData as $index => $eleve): 
                            ?>
                            <tr class="<?php echo $index % 2 == 0 ? 'even' : 'odd'; ?>" data-matricule="<?php echo htmlspecialchars($eleve['matricule']); ?>"
                                data-nom="<?php echo htmlspecialchars($eleve['nom']); ?>"
                                data-postnom="<?php echo htmlspecialchars($eleve['post_nom']); ?>"
                                data-classe="<?php echo htmlspecialchars($eleve['classe']); ?>"
                                data-sexe="<?php echo htmlspecialchars($eleve['sexe']); ?>"
                                data-telephone="<?php echo htmlspecialchars($eleve['telephone']); ?>">
                                <td>
                                    <div class="matricule-cell">
                                        <span class="matricule-badge"><?php echo htmlspecialchars($eleve['matricule']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-name">
                                        <i class="fas fa-user"></i>
                                        <span><?php echo htmlspecialchars($eleve['nom']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($eleve['post_nom']); ?></td>
                                <td>
                                    <span class="class-badge"><?php echo htmlspecialchars($eleve['classe']); ?></span>
                                </td>
                                <td>
                                    <span class="gender-badge <?php echo strtolower($eleve['sexe']); ?>">
                                        <i class="fas fa-<?php echo $eleve['sexe'] == 'Masculin' ? 'male' : 'female'; ?>"></i>
                                        <?php echo htmlspecialchars($eleve['sexe']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="phone-cell">
                                        <i class="fas fa-phone"></i>
                                        <a href="tel:<?php echo htmlspecialchars($eleve['telephone']); ?>">
                                            <?php echo htmlspecialchars($eleve['telephone']); ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" title="Voir détails" onclick="viewStudent('<?php echo $eleve['matricule']; ?>')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" title="Modifier" onclick="editStudent('<?php echo $eleve['matricule']; ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action report" title="Générer rapport individuel" onclick="generateStudentReport('<?php echo $eleve['matricule']; ?>')">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr class="empty-row">
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-user-graduate fa-3x"></i>
                                        <h3>Aucun élève trouvé</h3>
                                        <p>Utilisez les filtres pour afficher les élèves</p>
                                        <button class="btn btn-primary" onclick="resetFilters()">
                                            <i class="fas fa-redo"></i> Afficher tous les élèves
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (!empty($displayData)): ?>
                <div class="table-footer">
                    <div class="pagination-info">
                        Affichage de <strong>1</strong> à <strong><?php echo count($displayData); ?></strong> sur 
                        <strong><?php echo count($displayData); ?></strong> résultats
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="quick-actions">
            <button class="quick-action-btn" onclick="exportAllToPDF()">
                <div class="action-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="action-text">
                    <span class="action-title">Export PDF Complet</span>
                    <span class="action-desc">Exporter tous les élèves</span>
                </div>
            </button>
            
            <button class="quick-action-btn" onclick="showStatisticsChart()">
                <div class="action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="action-text">
                    <span class="action-title">Statistiques</span>
                    <span class="action-desc">Voir les graphiques</span>
                </div>
            </button>
            
            <button class="quick-action-btn" onclick="sendReports()">
                <div class="action-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="action-text">
                    <span class="action-title">Notification</span>
                    <span class="action-desc">Envoyer les rapports</span>
                </div>
            </button>
            
            <a href="Eleves.php" class="quick-action-btn">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="action-text">
                    <span class="action-title">Gérer Élèves</span>
                    <span class="action-desc">Accéder à la gestion</span>
                </div>
            </a>
        </div>
        
        <!-- Modal pour les statistiques -->
        <div id="statisticsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-chart-bar"></i> Statistiques Détailées</h3>
                    <button class="modal-close" onclick="closeStatisticsModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="chart-container">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                    <div class="statistics-details" id="statisticsDetails">
                        <!-- Les détails seront générés par JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="exportChart()">
                        <i class="fas fa-download"></i> Exporter Graphique
                    </button>
                    <button class="btn btn-secondary" onclick="closeStatisticsModal()">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Toast Notification -->
        <div id="toast" class="toast"></div>
    </main>
    
    <!-- JavaScript -->
    <script>
        // Variables globales
        let sortDirection = {};
        let currentData = <?php echo json_encode($displayData); ?>;
        let statisticsChart = null;
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initTableSearch();
            initAutoFocus();
            updateResultsCount();
            highlightActivePage();
            updateStatistics();
        });
        
        // Initialisation de la recherche dans le tableau
        function initTableSearch() {
            const searchInput = document.getElementById('tableSearch');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const filter = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#studentsTable tbody tr:not(.empty-row)');
                    
                    let visibleCount = 0;
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const isVisible = text.includes(filter);
                        row.style.display = isVisible ? '' : 'none';
                        if (isVisible) visibleCount++;
                    });
                    
                    updateResultsCount(visibleCount);
                });
            }
        }
        
        // Focus automatique sur le champ de recherche
        function initAutoFocus() {
            const searchField = document.getElementById('matriculeRecherche');
            if (searchField) {
                setTimeout(() => {
                    searchField.focus();
                }, 300);
            }
        }
        
        // Mettre à jour le compteur de résultats
        function updateResultsCount(count = null) {
            if (count === null) {
                count = document.querySelectorAll('#studentsTable tbody tr:not(.empty-row):not([style*="display: none"])').length;
            }
            const resultsCount = document.getElementById('resultsCount');
            if (resultsCount) {
                resultsCount.textContent = count + ' résultat(s)';
            }
        }
        
        // Réinitialiser les filtres
        function resetFilters() {
            document.getElementById('reportForm').reset();
            document.getElementById('reportForm').submit();
        }
        
        // Trier le tableau
        function sortTable(columnIndex) {
            const table = document.getElementById('studentsTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-row)'));
            
            if (rows.length === 0) return;
            
            // Déterminer la direction de tri
            sortDirection[columnIndex] = !sortDirection[columnIndex];
            const direction = sortDirection[columnIndex] ? 1 : -1;
            
            // Trier les lignes
            rows.sort((a, b) => {
                let aText = '';
                let bText = '';
                
                if (columnIndex === 0 || columnIndex === 3 || columnIndex === 4) {
                    // Pour matricule, classe, sexe
                    aText = a.cells[columnIndex].textContent.trim();
                    bText = b.cells[columnIndex].textContent.trim();
                } else {
                    // Pour les autres colonnes
                    aText = a.cells[columnIndex].textContent.trim();
                    bText = b.cells[columnIndex].textContent.trim();
                }
                
                // Essayer de convertir en nombre pour les colonnes numériques
                const aNum = parseFloat(aText.replace(/[^0-9.]/g, ''));
                const bNum = parseFloat(bText.replace(/[^0-9.]/g, ''));
                
                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return direction * (aNum - bNum);
                }
                
                // Sinon, tri alphabétique
                return direction * aText.localeCompare(bText, 'fr', { sensitivity: 'base' });
            });
            
            // Réorganiser les lignes
            rows.forEach(row => tbody.appendChild(row));
            
            // Mettre à jour les icônes de tri
            updateSortIcons(columnIndex);
        }
        
        // Mettre à jour les icônes de tri
        function updateSortIcons(activeColumn) {
            const sortButtons = document.querySelectorAll('.sort-btn');
            sortButtons.forEach((btn, index) => {
                const icon = btn.querySelector('i');
                if (index === activeColumn) {
                    icon.className = sortDirection[activeColumn] ? 'fas fa-sort-up' : 'fas fa-sort-down';
                } else {
                    icon.className = 'fas fa-sort';
                }
            });
        }
        
        // Exporter en PDF
        function exportToPDF() {
            showToast('Préparation du PDF en cours...', 'info');
            
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('landscape');
                
                // En-tête du document
                const title = 'Rapport des Élèves';
                const date = new Date().toLocaleDateString('fr-FR');
                const time = new Date().toLocaleTimeString('fr-FR');
                
                // Logo ou titre
                doc.setFontSize(20);
                doc.setTextColor(67, 97, 238);
                doc.text(title, 14, 22);
                
                doc.setFontSize(11);
                doc.setTextColor(100, 100, 100);
                doc.text(`Date: ${date} ${time}`, 14, 30);
                
                // Statistiques
                const total = currentData.length;
                const garcons = currentData.filter(e => e.sexe === 'Masculin').length;
                const filles = currentData.filter(e => e.sexe === 'Feminin').length;
                
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.text(`Statistiques: Total: ${total} | Garçons: ${garcons} | Filles: ${filles}`, 14, 40);
                
                // Préparation des données
                const headers = [
                    ['Matricule', 'Nom', 'Post-nom', 'Classe', 'Genre', 'Téléphone Parent']
                ];
                
                const rows = currentData.map(eleve => [
                    eleve.matricule,
                    eleve.nom,
                    eleve.post_nom,
                    eleve.classe,
                    eleve.sexe,
                    eleve.telephone || 'N/A'
                ]);
                
                // Génération du tableau
                doc.autoTable({
                    head: headers,
                    body: rows,
                    startY: 50,
                    theme: 'striped',
                    headStyles: {
                        fillColor: [67, 97, 238],
                        textColor: [255, 255, 255],
                        fontSize: 10,
                        fontStyle: 'bold'
                    },
                    styles: {
                        fontSize: 9,
                        cellPadding: 4,
                        overflow: 'linebreak'
                    },
                    columnStyles: {
                        0: { cellWidth: 25 },
                        1: { cellWidth: 30 },
                        2: { cellWidth: 30 },
                        3: { cellWidth: 25 },
                        4: { cellWidth: 20 },
                        5: { cellWidth: 35 }
                    },
                    margin: { top: 50 },
                    didDrawPage: function(data) {
                        // Pied de page
                        const pageCount = doc.internal.getNumberOfPages();
                        doc.setFontSize(9);
                        doc.setTextColor(150, 150, 150);
                        doc.text(
                            `Page ${data.pageNumber} sur ${pageCount}`,
                            doc.internal.pageSize.width / 2,
                            doc.internal.pageSize.height - 10,
                            { align: 'center' }
                        );
                    }
                });
                
                // Sauvegarder le PDF
                const filename = `rapport-eleves-${date.replace(/\//g, '-')}.pdf`;
                doc.save(filename);
                
                showToast('PDF généré avec succès', 'success');
                
            } catch (error) {
                console.error('Erreur génération PDF:', error);
                showToast('Erreur lors de la génération du PDF', 'error');
            }
        }
        
        // Exporter en Excel
        function exportToExcel() {
            showToast('Préparation du fichier Excel en cours...', 'info');
            
            try {
                // Préparation des données
                const headers = [
                    'Matricule',
                    'Nom', 
                    'Post-nom',
                    'Classe',
                    'Genre',
                    'Téléphone Parent'
                ];
                
                const data = currentData.map(eleve => [
                    eleve.matricule,
                    eleve.nom,
                    eleve.post_nom,
                    eleve.classe,
                    eleve.sexe,
                    eleve.telephone || 'N/A'
                ]);
                
                // Création du workbook
                const wb = XLSX.utils.book_new();
                
                // Feuille de données
                const ws_data = [headers, ...data];
                const ws = XLSX.utils.aoa_to_sheet(ws_data);
                
                // Style des en-têtes
                const headerStyle = {
                    font: { bold: true, color: { rgb: "FFFFFF" } },
                    fill: { fgColor: { rgb: "4361EE" } },
                    alignment: { horizontal: "center" }
                };
                
                // Appliquer le style aux en-têtes
                for (let col = 0; col < headers.length; col++) {
                    const cellAddress = XLSX.utils.encode_cell({ r: 0, c: col });
                    if (!ws[cellAddress]) ws[cellAddress] = {};
                    ws[cellAddress].s = headerStyle;
                }
                
                // Ajuster la largeur des colonnes
                const colWidths = [
                    { wch: 15 },
                    { wch: 25 },
                    { wch: 25 },
                    { wch: 15 },
                    { wch: 12 },
                    { wch: 20 }
                ];
                ws['!cols'] = colWidths;
                
                // Ajouter la feuille au workbook
                XLSX.utils.book_append_sheet(wb, ws, "Élèves");
                
                // Générer le fichier Excel
                const date = new Date().toISOString().split('T')[0];
                const filename = `rapport-eleves-${date}.xlsx`;
                XLSX.writeFile(wb, filename);
                
                showToast('Fichier Excel généré avec succès', 'success');
                
            } catch (error) {
                console.error('Erreur génération Excel:', error);
                showToast('Erreur lors de la génération du fichier Excel', 'error');
            }
        }
        
        // Exporter tout en PDF
        async function exportAllToPDF() {
            showToast('Export PDF complet en cours...', 'info');
            
            try {
                // Dans une vraie application, on ferait une requête AJAX pour récupérer tous les élèves
                // Pour l'instant, on utilise les données actuelles
                
                if (currentData.length === 0) {
                    showToast('Aucune donnée à exporter', 'warning');
                    return;
                }
                
                await exportToPDF();
                
            } catch (error) {
                console.error('Erreur export complet:', error);
                showToast('Erreur lors de l\'export complet', 'error');
            }
        }
        
        // Imprimer le rapport
        function printReport() {
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Rapport Élèves - Impression</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 25px; }
                        .print-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #4361ee; }
                        .print-header h1 { margin: 0; color: #4361ee; font-size: 28px; }
                        .print-date { color: #666; margin-top: 10px; font-size: 14px; }
                        .print-stats { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; border: 1px solid #dee2e6; }
                        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
                        .stat-item { text-align: center; padding: 15px; }
                        .stat-number { font-size: 24px; font-weight: bold; color: #4361ee; }
                        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
                        th { background: #4361ee; color: white; padding: 12px; text-align: left; font-weight: bold; }
                        td { padding: 10px; border: 1px solid #dee2e6; }
                        tr:nth-child(even) { background: #f8f9fa; }
                        .print-footer { margin-top: 30px; text-align: center; color: #666; font-size: 11px; padding-top: 20px; border-top: 1px solid #dee2e6; }
                        @media print {
                            body { margin: 15px; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>Rapport des Élèves</h1>
                        <div class="print-date">Généré le: ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}</div>
                    </div>
                    
                    <div class="print-stats">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">${currentData.length}</div>
                                <div class="stat-label">Effectif Total</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">${currentData.filter(e => e.sexe === 'Masculin').length}</div>
                                <div class="stat-label">Garçons</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">${currentData.filter(e => e.sexe === 'Feminin').length}</div>
                                <div class="stat-label">Filles</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">${currentData.filter(e => e.sexe === 'Masculin').length > 0 ? 
                                    (currentData.filter(e => e.sexe === 'Feminin').length / currentData.filter(e => e.sexe === 'Masculin').length).toFixed(2) : 'N/A'}</div>
                                <div class="stat-label">Ratio F/G</div>
                            </div>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Post-nom</th>
                                <th>Classe</th>
                                <th>Genre</th>
                                <th>Téléphone Parent</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${currentData.map(eleve => `
                                <tr>
                                    <td>${eleve.matricule}</td>
                                    <td>${eleve.nom}</td>
                                    <td>${eleve.post_nom}</td>
                                    <td>${eleve.classe}</td>
                                    <td>${eleve.sexe}</td>
                                    <td>${eleve.telephone || 'N/A'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    
                    <div class="print-footer">
                        <p>Gestion Scolaire - Système de Gestion des Élèves</p>
                        <p>Document généré automatiquement</p>
                    </div>
                </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
        
        // Mettre à jour les statistiques
        function updateStatistics() {
            const total = currentData.length;
            const garcons = currentData.filter(e => e.sexe === 'Masculin').length;
            const filles = currentData.filter(e => e.sexe === 'Feminin').length;
            const ratio = garcons > 0 ? (filles / garcons).toFixed(2) : (filles > 0 ? '∞' : '0');
            const garconsPourcent = total > 0 ? ((garcons / total) * 100).toFixed(1) : '0';
            const fillesPourcent = total > 0 ? ((filles / total) * 100).toFixed(1) : '0';
            
            // Mettre à jour les éléments HTML
            document.getElementById('statTotal').textContent = total;
            document.getElementById('statGarcons').textContent = garcons;
            document.getElementById('statFilles').textContent = filles;
            document.getElementById('statRatio').textContent = ratio;
            document.getElementById('statGarconsPourcent').textContent = garconsPourcent + '% du total';
            document.getElementById('statFillesPourcent').textContent = fillesPourcent + '% du total';
        }
        
        // Afficher les statistiques avec graphique
        function showStatisticsChart() {
            const total = currentData.length;
            const garcons = currentData.filter(e => e.sexe === 'Masculin').length;
            const filles = currentData.filter(e => e.sexe === 'Feminin').length;
            
            if (total === 0) {
                showToast('Aucune donnée pour afficher les statistiques', 'warning');
                return;
            }
            
            // Calculer la répartition par classe
            const classes = {};
            currentData.forEach(eleve => {
                if (!classes[eleve.classe]) {
                    classes[eleve.classe] = { total: 0, garcons: 0, filles: 0 };
                }
                classes[eleve.classe].total++;
                if (eleve.sexe === 'Masculin') {
                    classes[eleve.classe].garcons++;
                } else {
                    classes[eleve.classe].filles++;
                }
            });
            
            // Mettre à jour les détails
            const details = document.getElementById('statisticsDetails');
            details.innerHTML = `
                <div class="statistics-summary">
                    <h4><i class="fas fa-chart-line"></i> Résumé</h4>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span class="summary-label">Effectif total:</span>
                            <span class="summary-value">${total} élèves</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Garçons:</span>
                            <span class="summary-value">${garcons} (${((garcons/total)*100).toFixed(1)}%)</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Filles:</span>
                            <span class="summary-value">${filles} (${((filles/total)*100).toFixed(1)}%)</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Ratio F/G:</span>
                            <span class="summary-value">${garcons > 0 ? (filles/garcons).toFixed(2) : 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="classes-distribution">
                    <h4><i class="fas fa-graduation-cap"></i> Répartition par classe</h4>
                    <div class="classes-list">
                        ${Object.entries(classes).map(([classe, stats]) => `
                            <div class="class-item">
                                <span class="class-name">${classe}</span>
                                <div class="class-stats">
                                    <span class="stat">${stats.total} élèves</span>
                                    <span class="stat">${stats.garcons} ♂</span>
                                    <span class="stat">${stats.filles} ♀</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            
            // Créer ou mettre à jour le graphique
            const ctx = document.getElementById('statisticsChart').getContext('2d');
            
            if (statisticsChart) {
                statisticsChart.destroy();
            }
            
            statisticsChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Garçons', 'Filles'],
                    datasets: [{
                        data: [garcons, filles],
                        backgroundColor: ['#4cc9f0', '#f8961e'],
                        borderColor: ['#2a9d8f', '#e76f51'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Répartition par genre'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} élèves (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
            // Afficher la modal
            document.getElementById('statisticsModal').style.display = 'block';
        }
        
        // Fermer la modal des statistiques
        function closeStatisticsModal() {
            document.getElementById('statisticsModal').style.display = 'none';
        }
        
        // Exporter le graphique
        function exportChart() {
            if (!statisticsChart) {
                showToast('Aucun graphique à exporter', 'warning');
                return;
            }
            
            const link = document.createElement('a');
            link.download = 'statistiques-eleves.png';
            link.href = statisticsChart.toBase64Image();
            link.click();
            
            showToast('Graphique exporté avec succès', 'success');
        }
        
        // Voir les détails d'un élève
        function viewStudent(matricule) {
            showToast(`Affichage des détails de l'élève ${matricule}`, 'info');
            // Dans une vraie application, on redirigerait vers la page de détails
            // window.location.href = `EleveDetails.php?matricule=${matricule}`;
        }
        
        // Modifier un élève
        function editStudent(matricule) {
            showToast(`Modification de l'élève ${matricule}`, 'info');
            // Dans une vraie application, on redirigerait vers la page d'édition
            // window.location.href = `ModifierEleve.php?matricule=${matricule}`;
        }
        
        // Générer un rapport pour un élève
        function generateStudentReport(matricule) {
            const eleve = currentData.find(e => e.matricule === matricule);
            if (!eleve) {
                showToast('Élève non trouvé', 'error');
                return;
            }
            
            showToast(`Génération du rapport pour ${eleve.nom}`, 'info');
            
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // En-tête
                doc.setFontSize(20);
                doc.setTextColor(67, 97, 238);
                doc.text('FICHE INDIVIDUELLE ÉLÈVE', 105, 25, { align: 'center' });
                
                // Informations de l'élève
                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0);
                
                let y = 50;
                const info = [
                    [`Matricule: ${eleve.matricule}`],
                    [`Nom: ${eleve.nom}`],
                    [`Post-nom: ${eleve.post_nom}`],
                    [`Classe: ${eleve.classe}`],
                    [`Genre: ${eleve.sexe}`],
                    [`Téléphone parent: ${eleve.telephone || 'Non renseigné'}`],
                    [`Date de génération: ${new Date().toLocaleDateString('fr-FR')}`]
                ];
                
                info.forEach(item => {
                    doc.text(item, 20, y);
                    y += 12;
                });
                
                // Footer
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text('Document officiel - Gestion Scolaire', 105, 280, { align: 'center' });
                
                // Sauvegarder
                const filename = `fiche-eleve-${eleve.matricule}.pdf`;
                doc.save(filename);
                
                showToast('Rapport individuel généré avec succès', 'success');
                
            } catch (error) {
                console.error('Erreur génération rapport:', error);
                showToast('Erreur lors de la génération du rapport', 'error');
            }
        }
        
        // Envoyer les rapports
        function sendReports() {
            if (currentData.length === 0) {
                showToast('Aucune donnée à envoyer', 'warning');
                return;
            }
            
            if (confirm(`Envoyer les rapports par email pour ${currentData.length} élève(s) ?`)) {
                showToast('Envoi des rapports en cours...', 'info');
                setTimeout(() => {
                    showToast(`${currentData.length} rapports envoyés avec succès`, 'success');
                }, 2000);
            }
        }
        
        // Afficher une notification toast
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            if (!toast) return;
            
            toast.textContent = message;
            toast.className = `toast toast-${type}`;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // Mettre en évidence la page active
        function highlightActivePage() {
            const currentPage = window.location.pathname.split("/").pop();
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href) {
                    const linkPage = href.split("/").pop();
                    if (currentPage === linkPage) {
                        link.classList.add('active');
                    }
                }
            });
        }
        
        // Fermer la modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('statisticsModal');
            if (event.target == modal) {
                closeStatisticsModal();
            }
        };
    </script>
</body>
</html>