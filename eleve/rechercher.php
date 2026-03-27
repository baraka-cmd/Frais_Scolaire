<?php

require ('actions/database.php');
$matricule ="";
$nom ="";
$postNom ="";
$classe ="";
$sexe ="";
$numero ="";
$errorMsg ="";
$succesMsg ="";
if(isset($_POST['validateRechercher'])){
    if(!empty($_POST['matricule'])){
        $matricule = htmlspecialchars($_POST['matricule']);
        $verifEleve =$bdd->prepare('SELECT * FROM tb_eleve WHERE matricule = ?');
        $verifEleve->execute([$matricule]);
        if($verifEleve->rowCount()>0){
            $donneesEleve =$verifEleve->fetch();
            $matricule = $donneesEleve['matricule'];
            $nom = $donneesEleve['nom'];
            $postNom = $donneesEleve['post_nom'];
            $classe = $donneesEleve['classe'];
            $sexe = $donneesEleve['sexe'];
            $numero = $donneesEleve['telephone'];
        }else{
            $errorMsg = "L'eleve non retrouve";
        }
    }else{
        $errorMsg = "Veuillez entrer le matricule de recherche";
    }
}


/*
require ('actions/database.php');

$matricule ="";
$nom ="";
$postNom ="";
$classe ="";
$sexe ="";
$numero ="";
$errorMsg ="";
$succesMsg ="";

// Récupérer les messages de la session s'ils existent
if(isset($_SESSION['errorMsg'])){
    $errorMsg = $_SESSION['errorMsg'];
    unset($_SESSION['errorMsg']);
}
if(isset($_SESSION['succesMsg'])){
    $succesMsg = $_SESSION['succesMsg'];
    unset($_SESSION['succesMsg']);
}

// Récupérer les données de la session si elles existent
if(isset($_SESSION['matricule'])){
    $matricule = $_SESSION['matricule'];
}
if(isset($_SESSION['nom'])){
    $nom = $_SESSION['nom'];
}
if(isset($_SESSION['postNom'])){
    $postNom = $_SESSION['postNom'];
}
if(isset($_SESSION['classe'])){
    $classe = $_SESSION['classe'];
}
if(isset($_SESSION['sexe'])){
    $sexe = $_SESSION['sexe'];
}
if(isset($_SESSION['numero'])){
    $numero = $_SESSION['numero'];
}

if(isset($_POST['validateRechercher'])){
    if(!empty($_POST['matricule'])){
        $matriculeRecherche = htmlspecialchars($_POST['matricule']);
        $verifEleve =$bdd->prepare('SELECT * FROM tb_eleve WHERE matricule = ?');
        $verifEleve->execute([$matriculeRecherche]);
        if($verifEleve->rowCount()>0){
            $donneesEleve =$verifEleve->fetch();
            $matricule = $donneesEleve['matricule'];
            $nom = $donneesEleve['nom'];
            $postNom = $donneesEleve['post_nom'];
            $classe = $donneesEleve['classe'];
            $sexe = $donneesEleve['sexe'];
            $numero = $donneesEleve['telephone'];
            
            // Stocker les données dans la session
            $_SESSION['matricule'] = $matricule;
            $_SESSION['nom'] = $nom;
            $_SESSION['postNom'] = $postNom;
            $_SESSION['classe'] = $classe;
            $_SESSION['sexe'] = $sexe;
            $_SESSION['numero'] = $numero;
            
            $succesMsg = "Élève trouvé avec succès!";
            
        }else{
            $errorMsg = "L'élève non retrouvé";
            
            // Réinitialiser les variables de session
            unset($_SESSION['matricule']);
            unset($_SESSION['nom']);
            unset($_SESSION['postNom']);
            unset($_SESSION['classe']);
            unset($_SESSION['sexe']);
            unset($_SESSION['numero']);
        }
    }else{
        $errorMsg = "Veuillez entrer le matricule de recherche";
    }
}
?>*/