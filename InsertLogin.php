
<?php
$bdd = new PDO('mysql:host=localhost;dbname=fraisscolaire;charset=utf8;','root','');
$insertUser = $bdd->prepare('INSERT INTO admin_login(fonction, nom, motDePasse) VALUES (?,?,?)');
$insertUser->execute([
    'Directeur',
    'felix',
    password_hash('felix', PASSWORD_DEFAULT)
]);
echo"Succes!";