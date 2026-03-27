<?php
require ('actions/database.php');


if(isset($_POST['validateModifier'])){
    
    if(!empty($_POST['matricule']) and !empty($_POST['role']) and !empty($_POST['nom']) and !empty($_POST['motDePasse'])){
        $matricule = htmlspecialchars($_POST['matricule']);
        $fonction = htmlspecialchars($_POST['role']);
        $nom = htmlspecialchars($_POST['nom']);
        $motDePasse = htmlspecialchars($_POST['motDePasse']);

        $checkEleve =$bdd->prepare('SELECT * FROM admin_login WHERE matricule = ?');
        $checkEleve->execute([$matricule]);
        if($checkEleve->rowCount()>0){
            $updateEleve = $bdd->prepare('UPDATE admin_login SET fonction = ?, nom = ?, motDePasse = ? WHERE matricule = ?');
            $resultat = $updateEleve->execute([
                $fonction,
                $nom,
                password_hash($motDePasse, PASSWORD_DEFAULT),
                $matricule
            ]);
        }else{
            $errorMsg = "Erreur lors de la mise en jour";
        }
    }else{
        $errorMsg = "Veuillez remplir tous les champs";
    }
}