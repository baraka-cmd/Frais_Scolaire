<?php
require ('actions/database.php');
$matricule ="";
$nom ="";
$postNom ="";
$classe ="";
$montantTotal ="0";
$montantPaye ="0";
$restePaye ="0";
$annee ="";
$numero ="";
$errorMsg ="";
$succesMsg ="";
if(isset($_POST['validateRechercher'])){
    if(!empty($_POST['matriculeRecherche'])){
        $matriculeRecherche = htmlspecialchars($_POST['matriculeRecherche']);
        $nomRecherche = htmlspecialchars($_POST['matriculeRecherche']);
        if(!empty($_POST['annee'])){
            $annee = htmlspecialchars($_POST['annee']);
            $verifEleve =$bdd->prepare('SELECT matricule, nom, post_nom, classe FROM tb_eleve WHERE matricule = ? OR nom = ?');
            $verifEleve->execute([$matriculeRecherche, $nomRecherche]);
            if($verifEleve->rowCount()>0){
                $donneesEleve =$verifEleve->fetch();
                $matricule = $donneesEleve['matricule'];
                $nom = $donneesEleve['nom'];
                $postNom = $donneesEleve['post_nom'];
                $classe = $donneesEleve['classe'];
                
                $verifFrais=$bdd->prepare('SELECT frais_total, montant_paye, reste_a_payer FROM tb_frais WHERE matricule_eleve = ?');
                $verifFrais->execute([$matricule]);
                if($verifFrais->rowCount()>0){
                    $donneesFrais =$verifFrais->fetch();
                    $montantTotal = $donneesFrais['frais_total'];
                    $montantPaye = $donneesFrais['montant_paye'];
                    $restePaye = $donneesFrais['reste_a_payer'];


                    
                }else{
                    $errorMsg = "Aucun frais trouve";
                }
            }else{
                $errorMsg = "L'eleve non retrouve";
            }
        }else{
            $errorMsg = "Veuillez entrer l'Annee de recherche";
    }
    }else{
        $errorMsg = "Veuillez entrer le matricule de recherche";
    }
}