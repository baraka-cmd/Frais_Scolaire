<?php
if(isset($_POST['validatenouveau'])){
    unset($_POST);
    $succesMgs =  "Formulaire reinitialise";
    header('Location: Eleves.php');
}