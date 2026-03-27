<?php
require ('actions/database.php');

if(isset($_POST['validateRechercher'])){
    if(!empty($_POST['matriculeRecherche'])){
        $matriculeRecherche = htmlspecialchars($_POST['matriculeRecherche']);
        $recupereHistorique = $bdd->prepare('SELECT date_paiement, montant_verse FROM tb_historique_paiement WHERE matricule_eleve = ? ORDER BY date_paiement ASC');
        $recupereHistorique->execute([$matriculeRecherche]);
        if($recupereHistorique->rowCount()>0){
            $historique = $recupereHistorique->fetchAll(PDO::FETCH_ASSOC);
            $nombreHistorique = count($historique);
                                
        }else{
            $historique=[];
            $nombreHistorique = 0;
            
        }
    }else{
            $errorMsg = "Veuillez entrer le matricule de recherche";
    }
}
