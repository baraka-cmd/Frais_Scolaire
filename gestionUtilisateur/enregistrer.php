<?php
require ('actions/database.php');
if(isset($_POST['validateEnregistrer'])){
    if(!empty($_POST['matricule']) and $_POST['role'] and !empty($_POST['nom']) and !empty($_POST['motDePasse'])){
        $matricule = htmlspecialchars($_POST['matricule']);
        $fonction = htmlspecialchars($_POST['role']);
        $nom = htmlspecialchars($_POST['nom']);
        $motDePasse = htmlspecialchars($_POST['motDePasse']);

        $insertUser = $bdd->prepare('INSERT INTO admin_login(matricule, fonction, nom, motDePasse) VALUES (?,?,?,?)');
        $insertUser->execute([
            $matricule,
            $fonction,
            $nom,
            password_hash($motDePasse, PASSWORD_DEFAULT)
        ]);
        $succesMsg="Enregistrement reussi";
    }else{
        $errorMsg = "Veuillez remplir tous les champs";
    }
    

echo"Succes!";
}
