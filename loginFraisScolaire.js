// Initialisation après le chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initLoginSystem();
});

function initLoginSystem() {
    // Éléments DOM
    const loginForm = document.getElementById('loginForm');
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');
    const roleSelect = document.getElementById('roleSelect');
    const usernameInput = document.getElementById('usernameInput');
    const jsAlert = document.querySelector('.js-alert');
    const submitBtn = loginForm.querySelector('.submit-btn');
    const btnLoader = submitBtn.querySelector('.btn-loader');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnIcon = submitBtn.querySelector('.btn-icon');
    
    // Configuration par défaut
    const CONFIG = {
        minPasswordLength: 8,
        sessionTimeout: 30, // minutes
        apiEndpoint: 'actions/loginAction.php',
        debugMode: false
    };

    // 1. Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // 2. Validation en temps réel
    usernameInput.addEventListener('input', validateUsername);
    passwordInput.addEventListener('input', validatePassword);
    roleSelect.addEventListener('change', validateRole);

    // 3. Gestion de la soumission du formulaire
    loginForm.addEventListener('submit', handleFormSubmit);

    // 4. Bouton Retour fonctionnel
    const backBtn = document.getElementById('btnRetour');
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            if (confirm('Voulez-vous vraiment quitter la page de connexion ?')) {
                window.location.href = window.location.origin || '/';
            }
        });
    }

    // 5. Bouton Effacer fonctionnel
    const resetBtn = document.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Masquer les messages d'erreur
            hideJsAlert();
            
            // Réinitialiser les indicateurs de validation
            resetValidation();
            
            // Afficher un message de confirmation
            showToast('Formulaire réinitialisé', 'info');
        });
    }

    // 6. Gestion du "Se souvenir de moi"
    const rememberMe = document.getElementById('rememberMe');
    if (localStorage.getItem('rememberedUsername')) {
        usernameInput.value = localStorage.getItem('rememberedUsername');
        if (localStorage.getItem('rememberedRole')) {
            roleSelect.value = localStorage.getItem('rememberedRole');
        }
        rememberMe.checked = true;
    }

    // 7. Changement de langue
    const languageSelect = document.getElementById('languageSelect');
    if (languageSelect) {
        languageSelect.addEventListener('change', function() {
            changeLanguage(this.value);
        });
    }

    // 8. Gestion des raccourcis clavier
    document.addEventListener('keydown', function(e) {
        // Ctrl + Enter pour soumettre
        if (e.ctrlKey && e.key === 'Enter') {
            loginForm.requestSubmit();
        }
        
        // Échap pour fermer les modals
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // 9. Monitoring de la session
    startSessionMonitoring();

    // Fonctions de validation
    function validateUsername() {
        const value = usernameInput.value.trim();
        const isValid = value.length >= 3 && /^[a-zA-Z0-9._-]+$/.test(value);
        
        updateInputValidation(usernameInput, isValid);
        return isValid;
    }

    function validatePassword() {
        const value = passwordInput.value;
        const strength = calculatePasswordStrength(value);
        
        updatePasswordStrength(strength);
        updateInputValidation(passwordInput, value.length >= CONFIG.minPasswordLength);
        
        return value.length >= CONFIG.minPasswordLength;
    }

    function validateRole() {
        const value = roleSelect.value;
        const isValid = value !== '';
        
        updateInputValidation(roleSelect, isValid);
        return isValid;
    }

    function calculatePasswordStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        return Math.min(score, 4);
    }

    function updatePasswordStrength(strength) {
        const strengthBar = document.querySelector('.strength-bar');
        const strengthText = document.querySelector('.strength-text');
        
        if (!strengthBar) return;
        
        const percentage = (strength / 4) * 100;
        strengthBar.style.width = percentage + '%';
        
        let color, text;
        switch(strength) {
            case 0:
            case 1:
                color = '#f72585';
                text = 'Très faible';
                break;
            case 2:
                color = '#f8961e';
                text = 'Faible';
                break;
            case 3:
                color = '#4cc9f0';
                text = 'Bon';
                break;
            case 4:
                color = '#4ade80';
                text = 'Excellent';
                break;
        }
        
        strengthBar.style.backgroundColor = color;
        strengthText.textContent = `Force du mot de passe : ${text}`;
    }

    function updateInputValidation(element, isValid) {
        if (isValid) {
            element.style.borderColor = '#4ade80';
            element.style.background = 'rgba(74, 222, 128, 0.05)';
        } else {
            element.style.borderColor = '#f72585';
            element.style.background = 'rgba(247, 37, 133, 0.05)';
        }
    }

    function resetValidation() {
        [usernameInput, passwordInput, roleSelect].forEach(el => {
            el.style.borderColor = '';
            el.style.background = '';
        });
    }

    // Gestion de la soumission
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        // Validation finale
        const isUsernameValid = validateUsername();
        const isPasswordValid = validatePassword();
        const isRoleValid = validateRole();
        
        if (!isUsernameValid || !isPasswordValid || !isRoleValid) {
            showError('Veuillez corriger les erreurs dans le formulaire');
            return;
        }
        
        // Sauvegarder "Se souvenir de moi"
        if (rememberMe.checked) {
            localStorage.setItem('rememberedUsername', usernameInput.value);
            localStorage.setItem('rememberedRole', roleSelect.value);
        } else {
            localStorage.removeItem('rememberedUsername');
            localStorage.removeItem('rememberedRole');
        }
        
        // Afficher le loader
        showLoading(true);
        
        try {
            // Simulation d'une requête API (remplacez par votre véritable appel)
            if (CONFIG.debugMode) {
                console.log('Données soumises:', {
                    role: roleSelect.value,
                    nom: usernameInput.value,
                    pass: '***'
                });
            }
            
            // Le formulaire sera soumis normalement via POST
            // Cette partie est pour la simulation d'API
            await simulateApiCall();
            
            // Redirection sera gérée par PHP
            // En cas d'échec, PHP affichera $errorMsg
            
        } catch (error) {
            showError('Erreur de connexion. Veuillez réessayer.');
            showLoading(false);
        }
    }

    function simulateApiCall() {
        return new Promise(resolve => {
            setTimeout(resolve, 1000);
        });
    }

    function showLoading(show) {
        if (show) {
            btnText.style.opacity = '0';
            btnIcon.style.opacity = '0';
            btnLoader.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.8';
        } else {
            btnText.style.opacity = '1';
            btnIcon.style.opacity = '1';
            btnLoader.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
        }
    }

    function showError(message) {
        const errorMsg = document.getElementById('jsErrorMsg');
        errorMsg.textContent = message;
        jsAlert.style.display = 'flex';
        
        // Auto-hide après 5 secondes
        setTimeout(() => {
            hideJsAlert();
        }, 5000);
    }

    function hideJsAlert() {
        jsAlert.style.display = 'none';
    }

    function showToast(message, type = 'info') {
        // Créer le toast si il n'existe pas
        let toast = document.getElementById('global-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'global-toast';
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                transform: translateX(150%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            document.body.appendChild(toast);
        }
        
        // Définir la couleur selon le type
        const colors = {
            success: '#4ade80',
            error: '#f72585',
            info: '#4361ee',
            warning: '#f8961e'
        };
        
        toast.style.background = colors[type] || colors.info;
        toast.textContent = message;
        
        // Afficher
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        // Cacher après 3 secondes
        setTimeout(() => {
            toast.style.transform = 'translateX(150%)';
        }, 3000);
    }

    function changeLanguage(lang) {
        // Ici, vous pouvez implémenter le changement de langue
        // Pour l'instant, on montre juste un message
        showToast(`Langue changée en ${lang === 'fr' ? 'Français' : 'English'}`, 'info');
        
        // Sauvegarder la préférence
        localStorage.setItem('preferredLanguage', lang);
    }

    function startSessionMonitoring() {
        let idleTime = 0;
        
        const resetIdleTime = () => {
            idleTime = 0;
        };
        
        // Reset sur les événements utilisateur
        ['mousemove', 'keypress', 'click', 'scroll'].forEach(event => {
            document.addEventListener(event, resetIdleTime);
        });
        
        // Vérifier toutes les minutes
        setInterval(() => {
            idleTime++;
            
            if (idleTime === CONFIG.sessionTimeout - 5) {
                showToast('Votre session expire dans 5 minutes', 'warning');
            }
            
            if (idleTime >= CONFIG.sessionTimeout) {
                if (confirm('Session expirée. Recharger la page ?')) {
                    window.location.reload();
                }
            }
        }, 60000); // 1 minute
    }
}

// Fonctions pour le modal d'aide
function openModal() {
    const modal = document.getElementById('helpModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('helpModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Beacon pour la déconnexion
window.addEventListener('beforeunload', function() {
    if (navigator.sendBeacon) {
        navigator.sendBeacon('actions/deconnexion.php');
    }
});

// Gestion des liens "Mot de passe oublié" et "Demander un accès"
document.querySelectorAll('.forgot-link, .register-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Cette fonctionnalité n\'est pas encore disponible. Veuillez contacter l\'administrateur système.');
    });
});

// Gestion des boutons de login social
document.querySelectorAll('.social-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const provider = this.classList.contains('google-btn') ? 'Google' : 'Microsoft';
        alert(`Login avec ${provider} - Cette fonctionnalité sera bientôt disponible`);
    });
});