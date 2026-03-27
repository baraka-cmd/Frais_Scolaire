
<?php
    // Inclusion du script de traitement (le code générer.php que je vous ai donné précédemment)
    require('rapportFrais/generer.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Frais Scolaires</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="RapportFrais.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="main-body-wrapper">
            
            <div id="sidebar-container"></div>
                <?php include('BarreDeNavigation.php'); ?>
            <main class="content-principal">
                
                <?php if (!empty($errorMsg)): ?>
                    <div style="background: #fee2e2; color: #dc2626; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #fecaca;">
                        <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($errorMsg) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="recherche">
                        <div class="contenuRechercheBtn">
                            <input type="text" name="matriculeRecherche" placeholder="Matricule ou Nom..." value="<?= htmlspecialchars($_POST['matriculeRecherche'] ?? '') ?>">
                            <button type="submit" class="btnRechercher" name="btnRecherche">
                                <i class="fa-solid fa-magnifying-glass"></i> RECHERCHER
                            </button>
                        </div>
                    </div>

                    <div class="filtrerEleve">
                        <div class="filtreDate">
                            <label>Du :</label>
                            <input name="dateDebut" type="date" value="<?= htmlspecialchars($_POST['dateDebut'] ?? '') ?>">
                            <label>Au :</label>
                            <input name="dateFin" type="date" value="<?= htmlspecialchars($_POST['dateFin'] ?? '') ?>">
                        </div>
                        
                        <div class="contenuFiltreEleve">
                            <select name="filtreAnnee" id="filtreAnnee">
                                <option value="Toutes" <?= (($_POST['filtreAnnee'] ?? '') === 'Toutes') ? 'selected' : '' ?>>Toutes les classes</option>
                                <?php for($i=1; $i<=6; $i++): ?>
                                    <option value="<?= $i ?> Annee" <?= (($_POST['filtreAnnee'] ?? '') === "$i Annee") ? 'selected' : '' ?>><?= $i ?>ère Année</option>
                                <?php endfor; ?>
                            </select>

                            <select name="filtreSexe" id="filtreSexe">
                                <option value="Tous" <?= (($_POST['filtreSexe'] ?? '') === 'Tous') ? 'selected' : '' ?>>Tous les genres</option>
                                <option value="Masculin" <?= (($_POST['filtreSexe'] ?? '') === 'Masculin') ? 'selected' : '' ?>>Masculin</option>
                                <option value="Feminin" <?= (($_POST['filtreSexe'] ?? '') === 'Feminin') ? 'selected' : '' ?>>Feminin</option>
                            </select>

                            <select name="filtreAnneeScolaire" id="filtreannee">
                                <option value="Toutes">Toutes les années</option>
                                <option value="2025 - 2026" <?= (($_POST['filtreAnneeScolaire'] ?? '') === '2025 - 2026') ? 'selected' : '' ?>>2025 - 2026</option>
                                <option value="2026 - 2027" <?= (($_POST['filtreAnneeScolaire'] ?? '') === '2026 - 2027') ? 'selected' : '' ?>>2026 - 2027</option>
                                <option value="2027 - 2028" <?= (($_POST['filtreAnneeScolaire'] ?? '') === '2027 - 2028') ? 'selected' : '' ?>>2027 - 2028</option>
                            </select>
                        </div>
                    </div>

                    <div class="btnGenerer">
                        <button type="submit" name="btnGenerer">
                            <i class="fa-solid fa-file-invoice"></i> GÉNÉRER LE RAPPORT
                        </button>
                    </div>
                </form>

                <div class="tbRapportFrais">
                    <table class="tableau">
                        <thead>
                            <tr class="entete">
                                <th>Date paiement</th>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Post-nom</th>
                                <th>Classe</th>
                                <th>Genre</th>
                                <th>Montant payé</th>
                                <th>Année Scolaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($resultat)): ?>
                                <?php foreach($resultat as $frais): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($frais['date_paiement'])) ?></td>
                                        <td><strong><?= htmlspecialchars($frais['matricule']) ?></strong></td>
                                        <td><?= htmlspecialchars($frais['nom']) ?></td>
                                        <td><?= htmlspecialchars($frais['post_nom']) ?></td>
                                        <td><?= htmlspecialchars($frais['classe']) ?></td>
                                        <td><?= htmlspecialchars($frais['sexe']) ?></td>
                                        <td style="font-weight: bold; color: #2ecc71;">
                                            <?= number_format($frais['montant_verse'], 2, ',', ' ') ?> $
                                        </td>
                                        <td><?= htmlspecialchars($frais['annee_scolaire']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">
                                        Aucune donnée disponible. Modifiez les filtres et cliquez sur "Générer".
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="somme">
                    <div class="contenuRecherche">
                        <div class="rubriques">
                            <p><strong>TOTAL PERÇU :</strong></p>
                        </div>
                        <div class="contenuRubriques">
                            <p style="font-size: 1.5rem; font-weight: 800; color: #2ecc71;">
                                <?= $montantTotalFormatted ?> $
                            </p>
                        </div>     
                    </div>
                </div> 
                
                <div class="Action">
                    <div class="btnAction">
                        <button onclick="window.print()"><i class="fa-solid fa-print"></i> IMPRIMER</button>
                        <button><i class="fa-solid fa-file-pdf"></i> EXPORT PDF</button>
                        <button><i class="fa-solid fa-file-excel"></i> EXPORT EXCEL</button>
                        <button><i class="fa-solid fa-sort"></i> TRIER</button>
                    </div>
                </div>
                
            </main>
        </div>

    <script>
        // Chargement de la barre de navigation
        fetch('BarreDeNavigation.html').then(response=>response.text()).then(data=>{
            document.getElementById('sidebar-container').innerHTML=data;
        });

        // Gestion de l'état actif dans la nav
        document.addEventListener("DOMContentLoaded", () => {
            const links = document.querySelectorAll(".nav-link");
            const currentPage = window.location.pathname.split("/").pop();
            links.forEach(link => {
                if(link.getAttribute("href") === currentPage){
                    link.classList.add("active");
                }
            });
        });
    </script>
</body>
</html>
