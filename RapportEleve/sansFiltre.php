<?php
require ('actions/database.php');
// Si aucun filtre n'est actif, afficher tous les élèves et statistiques globales
    if(!$filtresActifs && !isset($_POST['btnGenerer']) && !isset($_POST['btnRechercher'])){
        $query = $bdd->query('SELECT matricule, nom, post_nom, classe, sexe, telephone FROM tb_eleve ORDER BY nom LIMIT 100');
        $resultat = $query->fetchAll();
        
        $stats = $bdd->query('SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN sexe = "Masculin" THEN 1 ELSE 0 END) as garcons,
            SUM(CASE WHEN sexe = "Feminin" THEN 1 ELSE 0 END) as filles
            FROM tb_eleve')->fetch();
            
        $totalEleves = $stats['total'] ?? 0;
        $garcons = $stats['garcons'] ?? 0;
        $filles = $stats['filles'] ?? 0;
    }