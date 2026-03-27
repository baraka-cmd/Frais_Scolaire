<?php
require ('actions/database.php');
// Initialiser les variables pour éviter les erreurs
    $resultat = [];
    $resultatRecherche = [];
    $totalEleves = 0;
    $garcons = 0;
    $filles = 0;
    $filtresActifs = false;
    
    // Traitement du formulaire de génération
    if(isset($_POST['btnGenerer'])){
        $classeFiltre = htmlspecialchars($_POST['classe']);
        $sexeFiltre = htmlspecialchars($_POST['sexe']);
        
        $donneesEleve = 'SELECT matricule, nom, post_nom, classe, sexe, telephone FROM tb_eleve WHERE 1=1';
        $params = [];
        
        if(!empty($classeFiltre) && $classeFiltre != "Toutes"){
            $donneesEleve .= " AND classe = ?";
            $params[] = $classeFiltre;
            $filtresActifs = true;
        }

        if(!empty($sexeFiltre) && $sexeFiltre != "Tous"){
            $donneesEleve .= " AND sexe = ?";
            $params[] = $sexeFiltre;
            $filtresActifs = true;
        }

        $donneesEleve .= " ORDER BY nom";
        
        $stm = $bdd->prepare($donneesEleve);
        $stm->execute($params);
        $resultat = $stm->fetchAll();
        
        // Calculer les statistiques basées sur les filtres
        $statsQuery = 'SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN sexe = "Masculin" THEN 1 ELSE 0 END) as garcons,
            SUM(CASE WHEN sexe = "Feminin" THEN 1 ELSE 0 END) as filles
            FROM tb_eleve WHERE 1=1';
            
        if(!empty($classeFiltre) && $classeFiltre != "Toutes"){
            $statsQuery .= " AND classe = ?";
        }

        if(!empty($sexeFiltre) && $sexeFiltre != "Tous"){
            $statsQuery .= " AND sexe = ?";
        }
        
        $statsStm = $bdd->prepare($statsQuery);
        $statsStm->execute($params);
        $stats = $statsStm->fetch();
        
        $totalEleves = $stats['total'] ?? 0;
        $garcons = $stats['garcons'] ?? 0;
        $filles = $stats['filles'] ?? 0;
    }
    
