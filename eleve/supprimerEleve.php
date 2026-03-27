<?php

require('actions/database.php');
if(isset($_POST['validateSupprimer']) AND !empty($_POST['matricule'])){
    $matriculeEleve = htmlspecialchars($_POST['matricule']);
    $verifEleve =$bdd->prepare('SELECT * FROM tb_eleve WHERE matricule = ?');
    $verifEleve->execute([$matriculeEleve]);
    if($verifEleve->rowCount()>0){
        $effacerEleve = $bdd->prepare('DELETE FROM tb_eleve WHERE matricule=?');
        $effacerEleve->execute([$matriculeEleve]);
        if($effacerEleve->rowCount()>0){
            $succesMsg = "L'eleve avec le matricule ".$matriculeEleve." a ete supprime";
            $_POST['matricule'] = "";
        }else{
            $errorMsg = "Une erreur s'est produite lors de la suppression";
        }
    }else{
        $errorMsg = "cet eleve n'existe pas";
    }
    
 
    header('Location: Eleves.php');
}