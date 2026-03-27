<?php
require ('actions/database.php');
$reqEleve = $bdd->query('SELECT * FROM tb_eleve ORDER BY matricule ASC');
if($reqEleve->rowCount()>0){
    $eleves = $reqEleve->fetchAll(PDO::FETCH_ASSOC);
    $nombreEleves = count($eleves);
}else{
    $eleves=[];
    $nombreEleves=0;
}
