<?php
require ('actions/database.php');
$recupereUtilisateur = $bdd->query('SELECT * FROM admin_login ORDER BY matricule ASC');
if($recupereUtilisateur->rowCount()>0){
    $utilisateur = $recupereUtilisateur->fetchAll(PDO::FETCH_ASSOC);
    $nombreUtilisateur = count($utilisateur);
                                
}else{
    $utilisateur=[];
    $nombreUtilisateur = 0;
            
}

