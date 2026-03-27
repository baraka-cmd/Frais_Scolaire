<?php
require('actions/verifierUtilisateurConnecte.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Professionnel</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="MenuPrincipal.css">
</head>
<body>
    <div class="layout-wrapper">
        <aside id="sidebar-container"></aside>
        
        <main class="main-content">
            <header class="content-header">
                <div class="header-titles">
                    <h1>Tableau de bord</h1>
                    <p>Bienvenue, <strong><?= $_SESSION['nom'] ?></strong> (<?= $_SESSION['fonction'] ?>)</p>
                </div>
                <div class="header-actions">
                    <button class="btn-notif"><i class="fa-regular fa-bell"></i></button>
                    <div class="user-avatar"><?= substr($_SESSION['nom'], 0, 1) ?></div>
                </div>
            </header>

            <div class="stats-grid">
                <div class="card stat-card">
                    <div class="icon-box blue"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <span class="label">Total Élèves</span>
                        <h2 class="value">1,250</h2>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="icon-box green"><i class="fa-solid fa-wallet"></i></div>
                    <div>
                        <span class="label">Recettes Jour</span>
                        <h2 class="value">450 $</h2>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="icon-box purple"><i class="fa-solid fa-file-invoice"></i></div>
                    <div>
                        <span class="label">Paiements en attente</span>
                        <h2 class="value">12</h2>
                    </div>
                </div>
            </div>

            <div class="card main-visual-card">
                <div class="visual-text">
                    <h2>Gestion des Finances</h2>
                    <p>Accédez rapidement aux rapports financiers et aux suivis des frais scolaires en quelques clics.</p>
                    <button class="btn-primary">Consulter les rapports</button>
                </div>
                <div class="visual-img">
                    <img src="ARGENT.jpg" alt="Gestion Financière">
                </div>
            </div>
        </main>
    </div>

    <script>
        // Chargement du menu
        fetch('BarreDeNavigation.php')
            .then(res => res.text())
            .then(data => {
                document.getElementById('sidebar-container').innerHTML = data;
                
                // Marquer le lien actif
                const links = document.querySelectorAll(".nav-link");
                const current = window.location.pathname.split("/").pop();
                links.forEach(l => {
                    if(l.getAttribute("href") === current) l.classList.add("active");
                });
            });
    </script>
</body>
</html>