<?php
require_once('actions/database.php');

// Traitement de la recherche
    if(isset($_POST['btnRechercher'])){
        $matriculeEleve = htmlspecialchars($_POST['matriculeRecherche']);
        
        if(!empty($matriculeEleve)){
            $recherche = $bdd->prepare('SELECT matricule, nom, post_nom, classe, sexe, telephone FROM tb_eleve WHERE matricule = ? OR nom LIKE ? OR post_nom LIKE ? ORDER BY nom');
            $recherche->execute([$matriculeEleve, "%$matriculeEleve%", "%$matriculeEleve%"]);
            $resultatRecherche = $recherche->fetchAll();
            
            // Calculer les statistiques pour la recherche
            if(!empty($resultatRecherche)){
                $totalEleves = count($resultatRecherche);
                $garcons = count(array_filter($resultatRecherche, function($e) { return $e['sexe'] == 'Masculin'; }));
                $filles = $totalEleves - $garcons;
                $filtresActifs = true;
            }
        }
    }