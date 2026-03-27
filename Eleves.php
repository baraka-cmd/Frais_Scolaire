<?php
require('actions/verifierUtilisateurConnecte.php');
require('eleve/ajouterEleve.php');
require('eleve/supprimerEleve.php');
require('eleve/nouveau.php');
require('eleve/rechercher.php');
require('eleve/modifier.php');
require('eleve/tableEleve.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Élèves</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Eleves.css?v<?php echo time(); ?>">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar chargée dynamiquement -->
        <div id="sidebar-container"></div>
        
        <!-- Contenu principal avec scroll -->
        <main class="main-content">
            <!-- En-tête de page -->
            <div class="page-header">
                <h1><i class="fas fa-user-graduate"></i> Gestion des Élèves</h1>
                <p class="page-description">Interface d'administration des élèves de l'établissement</p>
            </div>
            
            <!-- Messages d'alerte -->
            <?php if(isset($errorMsg) || isset($succesMsg)): ?>
                <div class="message-container">
                    <?php if(isset($errorMsg)): ?>
                        <div class="alert-message error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span><?php echo $errorMsg; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($succesMsg)): ?>
                        <div class="alert-message success">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo $succesMsg; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Formulaire principal -->
            <div class="form-container">
                <form id="formulaire" method="POST">
                    <div class="form-header">
                        <h2><i class="fas fa-user-edit"></i> Formulaire Élève</h2>
                        <button class="btn-rechercher" name="validateRechercher">
                            <i class="fa-solid fa-magnifying-glass"></i> RECHERCHER
                        </button>
                    </div>
                    
                    <div class="form-body">
                        <!-- Section identifiants -->
                        <div class="identifiant">
                            <div class="gauche">
                                <div class="form-group">
                                    <label for="matricule"><i class="fas fa-id-card"></i> Matricule</label>
                                    <input type="text" value="<?php echo htmlspecialchars($matricule);?>" name="matricule" id="matricule" placeholder="Entrer le matricule de l'élève" autocomplete="off">
                                </div>
                                
                                <div class="form-group">
                                    <label for="nom"><i class="fas fa-user"></i> Nom</label>
                                    <input type="text" name="nom" value="<?php echo htmlspecialchars($nom);?>" id="nom" placeholder="Entrer le nom de l'élève" autocomplete="off">
                                </div>
                                
                                <div class="form-group">
                                    <label for="postNom"><i class="fas fa-users"></i> Post-nom</label>
                                    <input type="text" name="postNom" value="<?php echo htmlspecialchars($postNom);?>" id="postNom" placeholder="Entrer le post-nom de l'élève" autocomplete="off">
                                </div>
                            </div>

                            <div class="droite">
                                <div class="form-group">
                                    <label for="classe"><i class="fas fa-graduation-cap"></i> Classe</label>
                                    <select name="classe" id="classe">
                                        <option value="1 Annee" <?php echo ($classe == '1 Annee')?'selected' : '';?> selected>1ère Année</option>
                                        <option value="2 Annee" <?php echo ($classe == '2 Annee')?'selected' : '';?>>2ème Année</option>
                                        <option value="3 Annee" <?php echo ($classe == '3 Annee')?'selected' : '';?>>3ème Année</option>
                                        <option value="4 Annee" <?php echo ($classe == '4 Annee')?'selected' : '';?>>4ème Année</option>
                                        <option value="5 Annee" <?php echo ($classe == '5 Annee')?'selected' : '';?>>5ème Année</option>
                                        <option value="6 Annee" <?php echo ($classe == '6 Annee')?'selected' : '';?>>6ème Année</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="genre"><i class="fas fa-venus-mars"></i> Genre</label>
                                    <select name="sexe" id="genre">
                                        <option value="Tous">Tous</option>
                                        <option value="Masculin">Masculin</option>
                                        <option value="Feminin">Féminin</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="telephone"><i class="fas fa-phone"></i> Téléphone Parent</label>
                                    <input type="text" name="numero" value="<?php echo htmlspecialchars($numero);?>" id="telephone" placeholder="Entrer le numéro du parent" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="actionBtn">
                            <button class="btn-enregistrer" name="validateEnregistrer" id="btnEnregistrer">
                                <i class="fa-solid fa-floppy-disk"></i> AJOUTER
                            </button>
                            <button class="btn-modifier" name="validateModifier" id="btnModifier">
                                <i class="fa-solid fa-pen-to-square"></i> MODIFIER
                            </button>
                            <button class="btn-supprimer" name="validateSupprimer" id="btnSupprimer">
                                <i class="fa-solid fa-trash-can"></i> SUPPRIMER
                            </button>
                            <button class="btn-nouveau" name="validatenouveau" id="btnNouveau">
                                <i class="fa-solid fa-plus"></i> NOUVEAU
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Section tableau des élèves -->
            <div class="table-section">
                <div class="table-header">
                    <h2><i class="fas fa-list-alt"></i> Liste des Élèves</h2>
                    <div class="stats">
                        <span class="stat-item">
                            <i class="fas fa-users"></i>
                            <span>Total élèves : <strong><?= $nombreEleves ?></strong></span>
                        </span>
                    </div>
                </div>
                
                <div class="tbEleve">
                    <table class="tableau">
                        <thead>
                            <tr class="entete">
                                <th><i class="fas fa-id-badge"></i> Matricule</th>
                                <th><i class="fas fa-user"></i> Nom</th>
                                <th><i class="fas fa-user-friends"></i> Post-nom</th>
                                <th><i class="fas fa-graduation-cap"></i> Classe</th>
                                <th><i class="fas fa-venus-mars"></i> Genre</th>
                                <th><i class="fas fa-phone"></i> Téléphone Parent</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php if($nombreEleves > 0):?>
                                <?php foreach($eleves as $index => $eleve):?>
                                    <tr class="<?php echo $index % 2 == 0 ? 'even' : 'odd'; ?>">
                                        <td>
                                            <span class="badge matricule"><?= htmlspecialchars($eleve['matricule'])?></span>
                                        </td>
                                        <td><?= htmlspecialchars($eleve['nom'])?></td>
                                        <td><?= htmlspecialchars($eleve['post_nom'])?></td>
                                        <td>
                                            <span class="badge classe"><?= htmlspecialchars($eleve['classe'])?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo strtolower($eleve['sexe']) == 'masculin' ? 'genre-masculin' : 'genre-feminin'; ?>">
                                                <i class="fas fa-<?php echo strtolower($eleve['sexe']) == 'masculin' ? 'mars' : 'venus'; ?>"></i>
                                                <?= htmlspecialchars($eleve['sexe'])?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($eleve['telephone'])?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="empty-row">
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-user-graduate fa-3x"></i>
                                            <p>Aucun élève enregistré dans la base de données</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="table-footer">
                    <div class="pagination-info">
                        Affichage de <?= $nombreEleves ?> élève(s)
                    </div>
                </div>
            </div>

            <!-- Message de validation -->
            <p id="message" class="validation-message"></p>
        </main>
    </div>
    
    <script>
        // Chargement de la sidebar
        fetch('BarreDeNavigation.php').then(response=>response.text()).then(data=>{
            document.getElementById('sidebar-container').innerHTML=data;
      
            const navLinks = document.querySelectorAll(".nav-link");
            const currentPage = window.location.pathname.split("/").pop();
            navLinks.forEach(link=>{
                link.classList.remove('active');
                const linkHref = link.getAttribute('href');
                const linkPage = linkHref.split("/").pop();
                if((currentPage===''||currentPage==='Eleves.php')&&(linkPage===''||linkPage==='Eleves.php')){
                    link.classList.add("active");
                }else if(currentPage===linkPage){
                    link.classList.add('active');
                }
            });
        }).catch(error=>console.error('Erreur:',error));

        // Validation améliorée
        document.getElementById("btnEnregistrer")?.addEventListener("click", function(e){
            const Matricule = document.getElementById("matricule").value.trim();
            const Nom = document.getElementById("nom").value.trim();
            const PostNom = document.getElementById("postNom").value.trim();
            const Classe = document.getElementById("classe").value.trim();
            const Genre = document.getElementById("genre").value.trim();
            const Telephone = document.getElementById("telephone").value.trim();
            const Message = document.getElementById("message");
            
            Message.textContent = "";
            Message.className = "validation-message";
            
            if(!Matricule || !Nom || !PostNom || !Classe || !Genre || !Telephone){
                Message.textContent = "⚠️ Tous les champs sont obligatoires !";
                Message.classList.add("error");
                e.preventDefault();
                return;
            }

            const matriculeRegex = /^[0-9]{1,9}$/;
            if(!matriculeRegex.test(Matricule)){
                Message.textContent = "❌ Le matricule doit contenir uniquement des chiffres (max 9)";
                Message.classList.add("error");
                e.preventDefault();
                return;
            }

            const nomPrenomRegex = /^[A-Za-zÀ-ÿ\s'-]+$/;
            if(!nomPrenomRegex.test(Nom)){
                Message.textContent = "❌ Le nom contient des caractères invalides";
                Message.classList.add("error");
                e.preventDefault();
                return;
            }
            
            if(!nomPrenomRegex.test(PostNom)){
                Message.textContent = "❌ Le post-nom contient des caractères invalides";
                Message.classList.add("error");
                e.preventDefault();
                return;
            }

            const phoneRegex = /^\+?[0-9\s]{7,20}$/;
            if(!phoneRegex.test(Telephone)){
                Message.textContent = "❌ Le numéro de téléphone est invalide";
                Message.classList.add("error");
                e.preventDefault();
                return;
            }
            
            Message.textContent = "✅ Validation réussie !";
            Message.classList.add("success");
        });

        // Réinitialisation du formulaire
        document.getElementById("btnNouveau")?.addEventListener("click", function(e){
            e.preventDefault();
            document.getElementById("formulaire").reset();
            document.getElementById("message").textContent = "";
            document.getElementById("message").className = "validation-message";
            
            // Réinitialiser les sélecteurs à leurs valeurs par défaut
            document.getElementById("classe").value = "1 Annee";
            document.getElementById("genre").value = "Masculin";
            
            // Afficher un message de confirmation
            const msg = document.getElementById("message");
            msg.textContent = "✅ Formulaire réinitialisé avec succès !";
            msg.className = "validation-message success";
            
            setTimeout(() => {
                msg.textContent = "";
                msg.className = "validation-message";
            }, 3000);
        });

        // Conversion en majuscules
        document.getElementById("nom")?.addEventListener("input", function(){
            this.value = this.value.toUpperCase();
        });

        document.getElementById("postNom")?.addEventListener("input", function(){
            this.value = this.value.toUpperCase();
        });
        
        // Amélioration de l'expérience utilisateur
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            element.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
        
        // Ajout d'effets visuels sur les boutons
        document.querySelectorAll('.actionBtn button').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
            });
        });
        
        // Assurer que la page entière est scrollable
        document.body.style.overflow = 'auto';
        document.querySelector('.main-content').style.overflow = 'visible';
    </script>
</body>
</html>