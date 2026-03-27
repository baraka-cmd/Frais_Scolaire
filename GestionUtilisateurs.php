<?php
require('actions/verifierUtilisateurConnecte.php');
require ('gestionUtilisateur/enregistrer.php');
require ('gestionUtilisateur/supprimer.php');
require ('gestionUtilisateur/modifier.php');
require ('gestionUtilisateur/tableLogin.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Gestion des Utilisateurs | École Excellence</title>
    <link rel="stylesheet" href="GestionUtilisateurs.css?v<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-body-wrapper">
        <div id="sidebar-container"></div>
        
        <!-- Mobile Header -->
        <div class="mobile-header">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <h2><i class="fas fa-users-cog"></i> Utilisateurs</h2>
        </div>
        
        <main class="content-principal">
            <!-- En-tête de la page -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-title">
                        <h1><i class="fas fa-users-cog"></i> Gestion des Utilisateurs</h1>
                        <p>Administration des comptes et permissions système</p>
                    </div>
                    <div class="header-stats">
                        <div class="stat-card">
                            <i class="fas fa-users"></i>
                            <div>
                                <span class="stat-number"><?php echo $nombreUtilisateur; ?></span>
                                <span class="stat-label">Utilisateurs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages d'alerte -->
            <?php if(isset($errorMsg) || isset($succesMsg)): ?>
            <div class="alert-container">
                <?php if(isset($errorMsg)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="alert-content">
                            <strong>Erreur</strong>
                            <span><?php echo $errorMsg; ?></span>
                        </div>
                        <button class="alert-close">&times;</button>
                    </div>
                <?php elseif(isset($succesMsg)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-content">
                            <strong>Succès</strong>
                            <span><?php echo $succesMsg; ?></span>
                        </div>
                        <button class="alert-close">&times;</button>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="content-grid">
                <!-- Section de formulaire -->
                <div class="form-section">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-user-plus"></i> Formulaire de gestion</h3>
                            <p>Créer, modifier ou supprimer un utilisateur</p>
                        </div>
                        
                        <form method="POST" class="user-form">
                            <div class="form-grid responsive-grid">
                                <div class="form-group">
                                    <label for="matricule">
                                        <i class="fas fa-id-card"></i> Matricule *
                                    </label>
                                    <input type="text" id="matricule" name="matricule" 
                                           placeholder="Ex: USR001" 
                                           autocomplete="off"
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="roleUtilisateur">
                                        <i class="fas fa-user-tag"></i> Rôle *
                                    </label>
                                    <div class="select-wrapper">
                                        <select name="role" id="roleUtilisateur" required>
                                            <option value="" disabled selected>Sélectionner un rôle</option>
                                            <option value="Comptable">Comptable</option>
                                            <option value="Directeur">Directeur</option>
                                            <option value="Enseignant">Enseignant</option>
                                            <option value="Administrateur">Administrateur</option>
                                        </select>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nom">
                                        <i class="fas fa-user"></i> Nom d'utilisateur *
                                    </label>
                                    <input type="text" id="nom" name="nom" 
                                           placeholder="Entrer le nom complet" 
                                           autocomplete="off"
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="motDePasse">
                                        <i class="fas fa-lock"></i> Mot de passe *
                                    </label>
                                    <div class="password-wrapper">
                                        <input type="password" id="motDePasse" name="motDePasse" 
                                               placeholder="Minimum 8 caractères" 
                                               autocomplete="off"
                                               minlength="8"
                                               required>
                                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Afficher le mot de passe">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="password-hint">Minimum 8 caractères</small>
                                </div>
                            </div>
                            
                            <div class="form-actions responsive-actions">
                                <button type="submit" name="validateEnregistrer" class="btn btn-success">
                                    <i class="fas fa-save"></i> <span>Enregistrer</span>
                                </button>
                                <button type="submit" name="validateModifier" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> <span>Modifier</span>
                                </button>
                                <button type="submit" name="validateSupprimer" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> <span>Supprimer</span>
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> <span>Réinitialiser</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Section tableau des utilisateurs -->
                <div class="table-section">
                    <div class="card">
                        <div class="card-header">
                            <div class="table-header-content">
                                <div>
                                    <h3><i class="fas fa-list"></i> Liste des Utilisateurs</h3>
                                    <p><?php echo $nombreUtilisateur; ?> utilisateur(s) enregistré(s)</p>
                                </div>
                                <div class="table-controls">
                                    <div class="search-box">
                                        <i class="fas fa-search"></i>
                                        <input type="text" placeholder="Rechercher un utilisateur..." id="searchInput">
                                    </div>
                                    <button class="btn-refresh" id="refreshTable" aria-label="Actualiser">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive-wrapper">
                            <table class="user-table" id="usersTable">
                                <thead>
                                    <tr>
                                        <th data-sort="matricule">
                                            <div class="table-header-cell">
                                                <span>Matricule</span>
                                                <i class="fas fa-sort"></i>
                                            </div>
                                        </th>
                                        <th data-sort="fonction">
                                            <div class="table-header-cell">
                                                <span>Fonction</span>
                                                <i class="fas fa-sort"></i>
                                            </div>
                                        </th>
                                        <th data-sort="nom">
                                            <div class="table-header-cell">
                                                <span>Nom</span>
                                                <i class="fas fa-sort"></i>
                                            </div>
                                        </th>
                                        <th>Statut</th>
                                        <th class="actions-header">Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php if($nombreUtilisateur > 0): ?>
                                        <?php foreach($utilisateur as $utilisateurs): ?>
                                            <tr class="user-row" 
                                                data-matricule="<?= htmlspecialchars($utilisateurs['matricule']) ?>"
                                                data-role="<?= htmlspecialchars($utilisateurs['fonction']) ?>"
                                                data-nom="<?= htmlspecialchars($utilisateurs['nom']) ?>">
                                                <td>
                                                    <div class="user-matricule">
                                                        <i class="fas fa-hashtag"></i>
                                                        <span><?= htmlspecialchars($utilisateurs['matricule']) ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="role-badge role-<?= strtolower($utilisateurs['fonction']) ?>">
                                                        <i class="fas fa-user-tag"></i>
                                                        <?= htmlspecialchars($utilisateurs['fonction']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-avatar">
                                                            <?= strtoupper(substr($utilisateurs['nom'], 0, 1)) ?>
                                                        </div>
                                                        <div class="user-details">
                                                            <strong><?= htmlspecialchars($utilisateurs['nom']) ?></strong>
                                                            <small><?= htmlspecialchars($utilisateurs['matricule']) ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="status-indicator">
                                                        <span class="status-dot active"></span>
                                                        <span class="status-text">Actif</span>
                                                    </div>
                                                </td>
                                                <td class="actions-cell">
                                                    <div class="action-buttons">
                                                        <button class="action-btn edit-btn" 
                                                                data-matricule="<?= htmlspecialchars($utilisateurs['matricule']) ?>"
                                                                aria-label="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="action-btn delete-btn" 
                                                                data-matricule="<?= htmlspecialchars($utilisateurs['matricule']) ?>"
                                                                aria-label="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <button class="action-btn view-btn" 
                                                                data-matricule="<?= htmlspecialchars($utilisateurs['matricule']) ?>"
                                                                aria-label="Voir détails">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr class="empty-state-row">
                                            <td colspan="5">
                                                <div class="empty-state-content">
                                                    <i class="fas fa-user-slash"></i>
                                                    <h4>Aucun utilisateur trouvé</h4>
                                                    <p>Commencez par ajouter un nouvel utilisateur</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            
                            <?php if($nombreUtilisateur > 0): ?>
                            <div class="table-footer">
                                <div class="table-info">
                                    <span>Affichage de 1 à <?php echo $nombreUtilisateur; ?> sur <?php echo $nombreUtilisateur; ?> entrées</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <script>
        // Chargement de la sidebar
        fetch('BarreDeNavigation.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('sidebar-container').innerHTML = data;
                initializeSidebar();
            });

        function initializeSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            if (sidebar && mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', () => {
                    sidebar.classList.add('mobile-open');
                    mobileOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
                
                mobileOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('mobile-open');
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
                
                // Fermer sidebar en cliquant sur un lien
                sidebar.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('mobile-open');
                            mobileOverlay.classList.remove('active');
                            document.body.style.overflow = '';
                        }
                    });
                });
            }
        }

        // Fonctionnalité pour afficher/masquer le mot de passe
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const passwordInput = document.getElementById('motDePasse');
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.className = 'fas fa-eye-slash';
                    this.setAttribute('aria-label', 'Masquer le mot de passe');
                } else {
                    passwordInput.type = 'password';
                    icon.className = 'fas fa-eye';
                    this.setAttribute('aria-label', 'Afficher le mot de passe');
                }
            });
        }

        // Remplissage automatique du formulaire
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const matricule = this.getAttribute('data-matricule');
                const row = document.querySelector(`.user-row[data-matricule="${matricule}"]`);
                
                if (row) {
                    document.getElementById('matricule').value = matricule;
                    document.getElementById('nom').value = row.getAttribute('data-nom');
                    document.getElementById('roleUtilisateur').value = row.getAttribute('data-role');
                    document.getElementById('motDePasse').focus();
                    
                    // Scroll vers le formulaire sur mobile
                    if (window.innerWidth <= 768) {
                        document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });

        // Confirmation pour la suppression
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const matricule = this.getAttribute('data-matricule');
                const row = document.querySelector(`.user-row[data-matricule="${matricule}"]`);
                
                if (row) {
                    const userName = row.querySelector('.user-details strong').textContent;
                    
                    if (confirm(`Voulez-vous vraiment supprimer l'utilisateur "${userName}" (${matricule}) ? Cette action est irréversible.`)) {
                        document.getElementById('matricule').value = matricule;
                        document.querySelector('[name="validateSupprimer"]').click();
                    }
                }
            });
        });

        // Recherche en temps réel
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.user-row');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
                
                // Afficher/masquer l'état vide
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = document.querySelector('.empty-state-row');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 ? '' : 'none';
                }
            });
        }

        // Actualisation du tableau
        const refreshBtn = document.getElementById('refreshTable');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                this.classList.add('rotating');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            });
        }

        // Tri des colonnes
        document.querySelectorAll('.user-table th[data-sort]').forEach(th => {
            th.addEventListener('click', function() {
                const sortBy = this.getAttribute('data-sort');
                const icon = this.querySelector('.fa-sort');
                const isAsc = this.classList.toggle('asc');
                
                // Réinitialiser les autres tris
                document.querySelectorAll('.user-table th[data-sort]').forEach(otherTh => {
                    if (otherTh !== this) {
                        otherTh.classList.remove('asc', 'desc');
                        otherTh.querySelector('.fa-sort').className = 'fas fa-sort';
                    }
                });
                
                // Mettre à jour l'icône
                icon.className = isAsc ? 'fas fa-sort-up' : 'fas fa-sort-down';
                this.classList.toggle('desc', !isAsc);
                
                // Tri des données (simulation)
                console.log(`Tri par ${sortBy} en ordre ${isAsc ? 'croissant' : 'décroissant'}`);
            });
        });

        // Fermer les alertes
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.alert').style.display = 'none';
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            });
        }, 5000);

        // Adaptative layout
        function handleResize() {
            const sidebar = document.querySelector('.sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            if (window.innerWidth > 768) {
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        window.addEventListener('resize', handleResize);
        window.addEventListener('load', handleResize);

        // Touch gestures for mobile
        let touchStartX = 0;
        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', e => {
            const touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            
            // Swipe right to open sidebar (only on left edge)
            if (touchStartX < 50 && diff < -50) {
                const sidebar = document.querySelector('.sidebar');
                const mobileOverlay = document.getElementById('mobileOverlay');
                if (sidebar && window.innerWidth <= 768) {
                    sidebar.classList.add('mobile-open');
                    mobileOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }
            
            // Swipe left to close sidebar
            if (diff > 50) {
                const sidebar = document.querySelector('.sidebar');
                const mobileOverlay = document.getElementById('mobileOverlay');
                if (sidebar && window.innerWidth <= 768) {
                    sidebar.classList.remove('mobile-open');
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });
    </script>
</body>
</html>