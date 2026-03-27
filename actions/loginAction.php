<?php

// Inclusion de la connexion à la base de données
require('database.php');

if(isset($_POST['validateLogin'])){

    // 1. Vérifier que tous les champs sont remplis
    if(!empty($_POST['role']) && !empty($_POST['nom']) && !empty($_POST['pass'])){
        
        // Sécurisation des données saisies
        $role = htmlspecialchars($_POST['role']);
        $name = htmlspecialchars($_POST['nom']);
        $passwordSaisi = $_POST['pass']; // Le mot de passe en clair tapé par l'utilisateur

        // 2. Chercher l'utilisateur dans la base de données par son nom et sa fonction
        $checkUser = $bdd->prepare('SELECT * FROM admin_login WHERE nom = ?');
        $checkUser->execute([$name]);

        if($checkUser->rowCount() > 0){
            
            // On récupère les données de l'utilisateur trouvé
            $infosUtilisateur = $checkUser->fetch();
            $hashStockeEnBDD = $infosUtilisateur['motDePasse'];

            // 3. COMPARISON : On vérifie si le mot de passe saisi correspond au hash
            if(password_verify($passwordSaisi, $hashStockeEnBDD)){
                
                // Si c'est correct, on crée les variables de session
                $_SESSION['auth'] = true;
                $_SESSION['fonction'] = $infosUtilisateur['fonction'];
                $_SESSION['nom'] = $infosUtilisateur['nom'];
                $_SESSION['id'] = $infosUtilisateur['id']; // Utile pour identifier l'user plus tard

                // Redirection vers la page d'accueil/navigation
                header('Location: MenuPrincipal.php');
                exit(); // Toujours mettre exit après une redirection

            } else {
                $errorMsg = "Le mot de passe est incorrect.";
            }

        } else {
            $errorMsg = "Aucun utilisateur trouvé avec ce nom pour le rôle : " . $role;
        }

    } else {
        $errorMsg = "Veuillez compléter tous les champs du formulaire.";
    }
}
?>
