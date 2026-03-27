<?php
// session_start(); // À activer si vous gérez des sessions
require('actions/database.php'); // Assurez-vous que ce fichier initialise la variable $bdd

// Initialisation des variables pour éviter les erreurs "undefined" dans le HTML
$resultat = [];
$totalMontantVerse = 0;
$montantTotalFormatted = "0,00";
$errorMsg = "";
$formSubmitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnGenerer'])) {
    $formSubmitted = true;

    // 1. Récupération des filtres du formulaire
    $dateDebut = $_POST['dateDebut'] ?? '';
    $dateFin = $_POST['dateFin'] ?? '';
    $filtreClasse = $_POST['filtreAnnee'] ?? 'Toutes';
    $filtreSexe = $_POST['filtreSexe'] ?? 'Tous';
    $filtreAnneeScolaire = $_POST['filtreAnneeScolaire'] ?? 'Toutes';

    // 2. Gestion des dates par défaut (équivalent du Java)
    // Si vide, on prend une plage très large
    if (empty($dateDebut)) {
        $dateDebut = '2000-01-01';
    }
    if (empty($dateFin)) {
        $dateFin = '2099-12-31';
    }

    try {
        // 3. Construction de la requête SQL (Dynamique comme StringBuilder en Java)
        $sql = "SELECT h.date_paiement, e.matricule, e.nom, e.post_nom, e.classe, e.sexe, 
                       h.montant_verse, h.annee_scolaire 
                FROM tb_historique_paiement h 
                JOIN tb_eleve e ON h.matricule_eleve = e.matricule 
                WHERE h.date_paiement BETWEEN :dateDebut AND :dateFin";

        $params = [
            ':dateDebut' => $dateDebut,
            ':dateFin' => $dateFin
        ];

        // Ajout conditionnel filtre Année Scolaire
        if ($filtreAnneeScolaire !== 'Toutes' && !empty($filtreAnneeScolaire)) {
            $sql .= " AND h.annee_scolaire = :anneeScolaire";
            $params[':anneeScolaire'] = $filtreAnneeScolaire;
        }

        // Ajout conditionnel filtre Classe
        if ($filtreClasse !== 'Toutes') {
            $sql .= " AND e.classe = :classe";
            $params[':classe'] = $filtreClasse;
        }

        // Ajout conditionnel filtre Sexe
        if ($filtreSexe !== 'Tous') {
            $sql .= " AND e.sexe = :sexe";
            $params[':sexe'] = $filtreSexe;
        }

        // 4. Tri (équivalent ORDER BY Java)
        $sql .= " ORDER BY h.date_paiement ASC, e.nom ASC";

        // 5. Préparation et Exécution
        $stmt = $bdd->prepare($sql);
        $stmt->execute($params);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Calcul du total (équivalent de la boucle while rs.next())
        foreach ($resultat as $frais) {
            $totalMontantVerse += (double)$frais['montant_verse'];
        }

        // Formatage pour l'affichage
        $montantTotalFormatted = number_format($totalMontantVerse, 2, ',', ' ');

    } catch (PDOException $e) {
        $errorMsg = "Erreur SQL : " . $e->getMessage();
    }
}

// Logique pour le bouton RECHERCHE (par matricule ou nom)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnRecherche'])) {
    $formSubmitted = true;
    $recherche = $_POST['matriculeRecherche'] ?? '';

    if (!empty($recherche)) {
        try {
            $sql = "SELECT h.date_paiement, e.matricule, e.nom, e.post_nom, e.classe, e.sexe, 
                           h.montant_verse, h.annee_scolaire 
                    FROM tb_historique_paiement h 
                    JOIN tb_eleve e ON h.matricule_eleve = e.matricule 
                    WHERE e.matricule LIKE :search OR e.nom LIKE :search
                    ORDER BY h.date_paiement DESC";
            
            $stmt = $bdd->prepare($sql);
            $stmt->execute([':search' => "%$recherche%"]);
            $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultat as $frais) {
                $totalMontantVerse += (double)$frais['montant_verse'];
            }
            $montantTotalFormatted = number_format($totalMontantVerse, 2, ',', ' ');

        } catch (PDOException $e) {
            $errorMsg = "Erreur recherche : " . $e->getMessage();
        }
    }
}
