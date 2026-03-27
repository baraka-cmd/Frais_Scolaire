<?php
require ('actions/database.php');

if(isset($_POST['validateEnregistrer'])){
    
    // 1. Vérification initiale des champs non vides
    if(empty($_POST['montantAPaye'])){
        $errorMsg = "Veuillez entrer le montant à payer";
    }
    elseif(empty($_POST['date'])){
        $errorMsg = "Veuillez entrer la date de paiement";
    }
    elseif(empty($_POST['fraisTotal'])){
        $errorMsg = "Veuillez entrer le montant total de paiement";
    }
    elseif(empty($_POST['matricule_hidden'])){
        $errorMsg = "Veuillez d'abord rechercher un élève";
    }
    else{
        // 2. Récupération des données
        $montantAPaye = htmlspecialchars($_POST['montantAPaye']);
        $datePaiement = htmlspecialchars($_POST['date']);
        $montantTotal = htmlspecialchars($_POST['fraisTotal']);
        $matricule = htmlspecialchars($_POST['matricule_hidden']);
        
        // Récupérer l'année scolaire depuis la base de données pour cet élève
        // Si l'élève n'a pas encore de frais, on utilise l'année courante par défaut
        $getAnnee = $bdd->prepare('SELECT annee_scolaire FROM tb_frais WHERE matricule_eleve = ? LIMIT 1');
        $getAnnee->execute([$matricule]);
        
        if($getAnnee->rowCount() > 0){
            $anneeData = $getAnnee->fetch();
            $annee = $anneeData['annee_scolaire'];
        } else {
            // Si l'élève n'a pas encore d'enregistrement de frais
            // On peut aussi récupérer depuis la table des élèves si elle contient l'année
            // Ou utiliser une valeur par défaut
            $annee = date('Y') . ' - ' . (date('Y') + 1);
        }

        // 3. VALIDATION DU MONTANT
        if(!is_numeric($montantAPaye)){
            $errorMsg = "Le montant doit être un nombre";
        }
        else{
            $montantFloat = (float) $montantAPaye;
            $totalFloat = (float) $montantTotal;
            
            if($montantFloat <= 0){
                $errorMsg = "Le montant doit être supérieur à 0";
            }
            elseif($montantFloat > 1000000){
                $errorMsg = "Le montant est trop élevé";
            }
            else{
                // Vérification des décimales
                if(strpos($montantAPaye, '.') !== false){
                    $parties = explode('.', $montantAPaye);
                    if(isset($parties[1]) && strlen($parties[1]) > 2){
                        $errorMsg = "Maximum 2 décimales autorisées";
                    }
                }
                
                // 4. VALIDATION DE LA DATE
                if(empty($errorMsg)){
                    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datePaiement)){
                        $errorMsg = "Format de date invalide";
                    }
                    else{
                        $dateParts = explode('-', $datePaiement);
                        if(!checkdate($dateParts[1], $dateParts[2], $dateParts[0])){
                            $errorMsg = "Date invalide";
                        }
                    }
                }
            }
        }
        
        // 5. INSERTION SI TOUTES LES VALIDATIONS SONT PASSÉES
        if(empty($errorMsg)){
            
            // Vérifier si l'élève a déjà un enregistrement
            $verifierSiEleveAUnPaiement = $bdd->prepare('SELECT montant_paye FROM tb_frais WHERE matricule_eleve = ?');
            $verifierSiEleveAUnPaiement->execute([$matricule]);
            
            if($verifierSiEleveAUnPaiement->rowCount() > 0){
                // Mise à jour pour un élève existant
                $resultatAncienPaye = $verifierSiEleveAUnPaiement->fetch();
                $ancienPaye = $resultatAncienPaye['montant_paye'];
                $nouveauPaye = $ancienPaye + $montantFloat;
                
                if($nouveauPaye > $totalFloat){
                    $errorMsg = "Le montant payé ne peut pas être supérieur au montant total";
                } else {
                    $restePaye = $totalFloat - $nouveauPaye;

                    $mettreAJour = $bdd->prepare('UPDATE tb_frais SET montant_paye = ?, reste_a_payer = ? WHERE matricule_eleve = ?');
                    $mettreAJour->execute([$nouveauPaye, $restePaye, $matricule]);
                    
                    if($mettreAJour->rowCount() > 0){
                        $succesMsg = "Paiement mis à jour avec succès !";
                    } else {
                        $errorMsg = "Erreur lors de la mise à jour en base de données.";
                    }
                }

            } else {
                // Nouvel enregistrement pour un élève
                if($montantFloat > $totalFloat){
                    $errorMsg = "Le montant payé ne peut pas être supérieur au montant total";
                } else {
                    $restePaye = $totalFloat - $montantFloat;

                    $insereFrais = $bdd->prepare('INSERT INTO tb_frais(matricule_eleve, frais_total, montant_paye, reste_a_payer, annee_scolaire) VALUES (?, ?, ?, ?, ?)');
                    $success = $insereFrais->execute([$matricule, $totalFloat, $montantFloat, $restePaye, $annee]);

                    if($success){
                        $succesMsg = "Paiement inséré avec succès !";
                    } else {
                        $errorMsg = "Erreur lors de l'enregistrement en base de données.";
                    }
                }
            }

            // Enregistrer dans l'historique seulement si pas d'erreur
            if(empty($errorMsg)){
                $insererHistorique = $bdd->prepare('INSERT INTO tb_historique_paiement (matricule_eleve, montant_verse, date_paiement, annee_scolaire) VALUES (?, ?, ?, ?)');
                $succesHistorique = $insererHistorique->execute([
                    $matricule,
                    $montantFloat,
                    $datePaiement,
                    $annee
                ]);
                
                if($succesHistorique){
                    if(!isset($succesMsg)){
                        $succesMsg = "Paiement enregistré avec succès !";
                    }
                } else {
                    $errorMsg = "Erreur lors de l'enregistrement de l'historique.";
                }
            }
        }
    }
}