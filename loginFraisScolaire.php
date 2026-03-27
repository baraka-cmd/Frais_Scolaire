<?php 
session_start();
require('actions/loginAction.php'); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Gestion Scolaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginFraisScolaire.css">
</head>
<body>
    
    <div class="main-wrapper">
        <div class="login-container">
            <div class="brand-section">
                <div class="brand-content">
                    <img src="padlock.png" alt="Logo" class="main-logo">
                    <h1>Portail Sécurisé</h1>
                    <p>Accédez à votre espace de gestion scolaire en toute confiance.</p>
                </div>
            </div>

            <div class="form-section">
                <div class="form-header">
                    <h2>Connexion</h2>
                    <p>Identifiez-vous pour continuer</p>
                </div>

                <?php if(isset($errorMsg)): ?>
                    <div class="alert-box">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span><?php echo $errorMsg; ?></span>
                    </div>
                <?php endif; ?>

                <form id="formLogin" method="POST">
                    <div class="input-group">
                        <label for="roleUtilisateur">Votre Rôle</label>
                        <div class="input-field">
                            <i class="fa-solid fa-user-shield"></i>
                            <select id="roleUtilisateur" name="role">
                                <option value="Directeur">Directeur</option>
                                <option value="Comptable">Comptable</option>
                                <option value="Enseignant">Enseignant</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="nom">Nom d'utilisateur</label>
                        <div class="input-field">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" id="nom" name="nom" placeholder="Ex: admin_01" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="pass">Mot de passe</label>
                        <div class="input-field">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="pass" name="pass" placeholder="••••••••" required>
                            <i class="fa-solid fa-eye toggle-pass" id="eyeIcon"></i>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" name="validateLogin" class="btn-confirm">
                            <span>CONFIRMER</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>

                    <div class="secondary-actions">
                        <button type="reset" class="btn-text">Effacer</button>
                        <button type="button" id="btnRetour" class="btn-text">Retour</button>
                    </div>
                </form>

                <p id="message-js" class="status-msg"></p>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        const eyeIcon = document.getElementById('eyeIcon');
        const passInput = document.getElementById('pass');

        eyeIcon.addEventListener('click', () => {
            const type = passInput.type === 'password' ? 'text' : 'password';
            passInput.type = type;
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        // Beacon for Logout on close
        window.addEventListener('beforeunload', function() {
            navigator.sendBeacon('actions/deconnexion.php');
        });
    </script>
</body>
</html>