<?php
require ('actions/database.php');


if(isset($_POST['validateModifier'])){
    $matricule = htmlspecialchars($_POST['matricule']);
    $nom = htmlspecialchars($_POST['nom']);
    $postNom = htmlspecialchars($_POST['postNom']);
    $classe = htmlspecialchars($_POST['classe']);
    $sexe = htmlspecialchars($_POST['sexe']);
    $numero = htmlspecialchars($_POST['numero']);

    if(!empty($_POST['matricule']) and !empty($_POST['nom']) and !empty($_POST['postNom']) and !empty($_POST['classe']) and !empty($_POST['sexe'])){
        $checkEleve =$bdd->prepare('SELECT * FROM tb_eleve WHERE matricule = ?');
        $checkEleve->execute([$matricule]);
        if($checkEleve->rowCount()>0){
            $updateEleve = $bdd->prepare('UPDATE tb_eleve SET nom = ?, post_nom = ?, classe = ?, sexe = ?, telephone = ? WHERE matricule = ?');
            $resultat = $updateEleve->execute([
                $nom,
                $postNom,
                $classe,
                $sexe,
                $numero,
                $matricule
            ]);
        }else{
            $errorMsg = "Erreur lors de la mise en jour";
        }
    }else{
        $errorMsg = "Veuillez remplir tous les champs";
    }
}