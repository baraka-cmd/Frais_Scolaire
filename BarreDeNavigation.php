<?php
// BarreDeNavigation.php
require('actions/database.php');

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['auth']) || $_SESSION['auth'] !== true){
    header('Location: loginFraisScolaire.php');
    exit();
}

// Récupérer le nom de l'utilisateur connecté
$nomUtilisateur = isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : 'Utilisateur';
$fonctionUtilisateur = isset($_SESSION['fonction']) ? htmlspecialchars($_SESSION['fonction']) : '';

// Déterminer la page active
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="BarreDeNavigation.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Bouton menu mobile -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Overlay pour fermer le menu sur mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Barre de Navigation -->
    <nav class="sidebar" id="sidebar">
        <!-- En-tête de la sidebar -->
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>Gestion</h1>
                    <h2>Frais Scolaire</h2>
                </div>
            </div>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Informations utilisateur -->
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <h3><?php echo $nomUtilisateur; ?></h3>
                <p class="user-role">
                    <i class="fas fa-user-tag"></i>
                    <?php echo $fonctionUtilisateur; ?>
                </p>
            </div>
            <div class="user-status online"></div>
        </div>
        
        <!-- Menu de navigation -->
        <div class="sidebar-menu">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="Eleves.php" class="nav-link <?php echo ($currentPage == 'Eleves.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <i class="fas fa-user-graduate"></i>
                        </span>
                        <span class="nav-text">Élèves</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="Frais.php" class="nav-link <?php echo ($currentPage == 'Frais.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                        <span class="nav-text">Frais</span>
                    </a>
                </li>
                
                <!-- Section Rapports -->
                <li class="nav-section">
                    <span class="section-label">
                        <i class="fas fa-chart-bar"></i>
                        Rapports
                    </span>
                </li>
                
                <li class="nav-item">
                    <a href="RapportEleve.php" class="nav-link <?php echo ($currentPage == 'RapportEleve.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <i class="fas fa-file-invoice"></i>
                        </span>
                        <span class="nav-text">Rapports Élèves</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="RapportFrais.php" class="nav-link <?php echo ($currentPage == 'RapportFrais.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </span>
                        <span class="nav-text">Rapports Frais</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="RapportDette.php" class="nav-link <?php echo ($currentPage == 'RapportDette.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <i class="fas fa-triangle-exclamation"></i>
                        </span>
                        <span class="nav-text">Rapports Dettes</span>
                    </a>
                </li>
                
                <!-- Section Administration -->
                <li class="nav-section">
                    <span class="section-label">
                        <i class="fas fa-cogs"></i>
                        Administration
                    </span>
                </li>
                
                <li class="nav-item">
                    <a href="GestionUtilisateurs.php" class="nav-link <?php echo ($currentPage == 'GestionUtilisateurs.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <i class="fas fa-users-cog"></i>
                        </span>
                        <span class="nav-text">Gestion Utilisateurs</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="AdminLogin.php" class="nav-link <?php echo ($currentPage == 'AdminLogin.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <i class="fas fa-user-gear"></i>
                        </span>
                        <span class="nav-text">Paramètres Admin</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Pied de la sidebar -->
        <div class="sidebar-footer">
            <div class="system-status">
                <div class="status-item">
                    <i class="fas fa-server status-online"></i>
                    <span>Système en ligne</span>
                </div>
                <div class="status-item">
                    <i class="fas fa-database"></i>
                    <span>BDD connectée</span>
                </div>
            </div>
            
            <div class="sidebar-actions">
                <a href="actions/deconnexion.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
                <button class="toggle-sidebar" id="toggleSidebar" title="Réduire/Aggrandir">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Contenu principal -->
    <main class="main-content" id="mainContent">

<script>
// ===== SCRIPT JAVASCRIPT POUR LA BARRE DE NAVIGATION =====
document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const logoutBtn = document.querySelector('.logout-btn');
    
    // ===== FONCTION POUR VÉRIFIER LA TAILLE DE L'ÉCRAN =====
    function checkScreenSize() {
        const isMobile = window.innerWidth <= 992;
        
        if (isMobile) {
            // MODE MOBILE/TABLETTE
            if (mobileMenuToggle) {
                mobileMenuToggle.style.display = 'flex';
                mobileMenuToggle.style.opacity = '1';
                mobileMenuToggle.style.pointerEvents = 'all';
            }
            
            // S'assurer que la sidebar n'est pas réduite sur mobile
            if (sidebar) {
                sidebar.classList.remove('collapsed');
                // Cacher par défaut sur mobile
                sidebar.classList.remove('active');
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            
            // Ajuster le padding du contenu principal
            const mainContent = document.getElementById('mainContent');
            if (mainContent) {
                mainContent.style.paddingTop = '80px';
            }
            
        } else {
            // MODE DESKTOP
            if (mobileMenuToggle) {
                mobileMenuToggle.style.display = 'none';
            }
            
            // Toujours visible sur desktop
            if (sidebar) {
                sidebar.classList.add('active');
                // Restaurer l'état réduit si sauvegardé
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                }
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            
            // Réinitialiser le padding du contenu principal
            const mainContent = document.getElementById('mainContent');
            if (mainContent) {
                mainContent.style.paddingTop = '';
            }
        }
        
        // Log pour débogage
        console.log('Screen size checked:', {
            width: window.innerWidth,
            isMobile: isMobile,
            sidebarActive: sidebar ? sidebar.classList.contains('active') : 'N/A',
            sidebarCollapsed: sidebar ? sidebar.classList.contains('collapsed') : 'N/A'
        });
    }
    
    // ===== INITIALISATION AU CHARGEMENT =====
    checkScreenSize();
    highlightActivePage();
    
    // ===== GESTION DU MENU MOBILE =====
    if (mobileMenuToggle && sidebar) {
        // Ouvrir le menu mobile
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Menu toggle clicked');
            
            sidebar.classList.add('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
            
            // Animation du bouton
            this.style.transform = 'rotate(90deg)';
            setTimeout(() => {
                this.style.transform = '';
            }, 300);
        });
        
        // Fermer avec le bouton X
        if (sidebarClose) {
            sidebarClose.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Sidebar close clicked');
                
                sidebar.classList.remove('active');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                }
                document.body.style.overflow = 'auto';
            });
        }
        
        // Fermer avec l'overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Overlay clicked');
                
                sidebar.classList.remove('active');
                this.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }
    }
    
    // ===== RÉDUIRE/AGRANDIR LA SIDEBAR SUR DESKTOP =====
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Seulement sur desktop
            if (window.innerWidth > 992 && sidebar) {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                
                // Animation du bouton
                this.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 300);
                
                console.log('Sidebar toggled:', sidebar.classList.contains('collapsed'));
            }
        });
    }
    
    // ===== FERMER LE MENU MOBILE EN CLIQUANT SUR UN LIEN =====
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            // Si on est sur mobile, fermer le menu après le clic
            if (window.innerWidth <= 992) {
                setTimeout(() => {
                    if (sidebar) sidebar.classList.remove('active');
                    if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }, 300);
            }
        });
    });
    
    // ===== CONFIRMATION DE DÉCONNEXION =====
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            // Si c'est un lien normal, ajouter une confirmation
            if (this.tagName === 'A') {
                e.preventDefault();
                const confirmLogout = confirm('Voulez-vous vraiment vous déconnecter ?');
                if (confirmLogout) {
                    window.location.href = this.href;
                }
            }
        });
    }
    
    // ===== FONCTION POUR METTRE EN ÉVIDENCE LA PAGE ACTIVE =====
    function highlightActivePage() {
        const currentPage = window.location.pathname.split("/").pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            // Retirer d'abord toutes les classes active
            link.classList.remove('active');
            
            const href = link.getAttribute('href');
            if (href) {
                const linkPage = href.split("/").pop();
                
                // Vérifier plusieurs formats possibles
                if (currentPage === linkPage || 
                    (currentPage === '' && linkPage === 'index.php') ||
                    (currentPage && currentPage.includes(linkPage.replace('.php', '').replace('.html', '')))) {
                    link.classList.add('active');
                }
            }
        });
        
        console.log('Active page highlighted:', currentPage);
    }
    
    // ===== GESTION DU REDIMENSIONNEMENT =====
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            checkScreenSize();
            highlightActivePage();
        }, 250);
    });
    
    // ===== FERMETURE AVEC TOUCHE ESCAPE =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth <= 992) {
            if (sidebar) sidebar.classList.remove('active');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
            console.log('Sidebar closed with Escape key');
        }
    });
    
    // ===== FERMETURE EN CLIQUANT EN DEHORS =====
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992 && 
            sidebar && sidebar.classList.contains('active') && 
            !sidebar.contains(e.target) && 
            mobileMenuToggle && !mobileMenuToggle.contains(e.target)) {
            
            sidebar.classList.remove('active');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
    
    // ===== DÉBOGAGE : LOG DE TOUS LES ÉLÉMENTS =====
    console.log('Navigation elements loaded:', {
        mobileMenuToggle: !!mobileMenuToggle,
        sidebar: !!sidebar,
        sidebarClose: !!sidebarClose,
        sidebarOverlay: !!sidebarOverlay,
        toggleSidebar: !!toggleSidebar,
        navLinks: document.querySelectorAll('.nav-link').length,
        logoutBtn: !!logoutBtn
    });
});
</script>