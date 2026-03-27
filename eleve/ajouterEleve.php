<?php
require ('actions/database.php');

if(isset($_POST['validateEnregistrer'])){
    if(!empty($_POST['matricule']) and !empty($_POST['nom']) and !empty($_POST['postNom']) and !empty($_POST['classe']) and !empty($_POST['sexe'])){
        $matricule = htmlspecialchars($_POST['matricule']);
        $nom = htmlspecialchars($_POST['nom']);
        $postNom = htmlspecialchars($_POST['postNom']);
        $classe = htmlspecialchars($_POST['classe']);
        $sexe = htmlspecialchars($_POST['sexe']);
        $numero = htmlspecialchars($_POST['numero']);

        $verifierSiUtilisateurExiste = $bdd->prepare('SELECT matricule FROM tb_eleve WHERE matricule = ?');
        $verifierSiUtilisateurExiste->execute([$matricule]);
        if($verifierSiUtilisateurExiste->rowCount()>0){
            $errorMsg = "Le matricule existe deja";
        }else{
            $insertEleve = $bdd->prepare('INSERT INTO tb_eleve(matricule, nom, post_nom, classe, sexe, telephone) VALUES (?,?,?,?,?,?)');
            $insertEleve->execute(
            [
                $matricule,
                $nom,
                $postNom,
                $classe,
                $sexe,
                $numero
                
            ]
           
        );
        $succesMsg="Enregistrement reussi";
        }
        
    }else{
        $errorMsg = "Veuillez remplir tous les champs";
    }
}