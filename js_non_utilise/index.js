const video = document.getElementById('metroVideo');
const fadeOverlay = document.getElementById('fadeOverlay');
const blurOverlay = document.getElementById('blurOverlay');
const enterBtn = document.getElementById('enterBtn');
const siteTitle = document.getElementById('siteTitle');
const formContainer = document.getElementById('formContainer');
const btnSignup = document.getElementById('btnSignup');
const btnLogin = document.getElementById('btnLogin');
const signupForm = document.getElementById('signupForm');
const loginForm = document.getElementById('loginForm');
const signupError = document.getElementById('signupError');
const loginError = document.getElementById('loginError');

video.addEventListener('canplay', () => {
    setTimeout(() => {
        fadeOverlay.classList.add('hide');
        setTimeout(() => {
            enterBtn.classList.add('show');
            siteTitle.classList.add('show');
        }, 1500);
    }, 800); 
});

video.addEventListener('ended', () => {
    blurOverlay.classList.add('show');
});

video.addEventListener('ended', () => {
    video.pause();
});

btnSignup.onclick = () => {
    btnSignup.classList.add('active');
    btnLogin.classList.remove('active');
    signupForm.style.display = '';
    loginForm.style.display = 'none';
};
btnLogin.onclick = () => {
    btnLogin.classList.add('active');
    btnSignup.classList.remove('active');
    signupForm.style.display = 'none';
    loginForm.style.display = '';
};

enterBtn.addEventListener('click', (e) => {
    e.preventDefault();
    siteTitle.style.display = 'none';
    enterBtn.style.display = 'none';
    formContainer.style.display = 'flex';
});

signupForm.onsubmit = async function(e) {
    e.preventDefault();
    signupError.textContent = '';
    loginError.textContent = '';
    const username = document.getElementById('signupUsername').value;
    const email = document.getElementById('signupEmail').value;
    const password = document.getElementById('signupPassword').value;
    try {
        const res = await fetch('db/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
        });
        let data, text;
        try {
            text = await res.text();
            data = JSON.parse(text);
        } catch (jsonErr) {
            signupError.textContent = 'Réponse serveur non JSON : ' + text;
            return;
        }
        if (data.success) {
            loginError.textContent = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
            btnLogin.classList.add('active');
            btnSignup.classList.remove('active');
            signupForm.style.display = 'none';
            loginForm.style.display = '';
        } else {
            signupError.textContent = data.message || 'Erreur lors de l\'inscription.';
        }
    } catch (err) {
        signupError.textContent = 'Erreur serveur JS : ' + err;
    }
};

loginForm.onsubmit = async function(e) {
    e.preventDefault();
    loginError.textContent = '';
    const username = document.getElementById('loginUsername').value;
    const password = document.getElementById('loginPassword').value;
    try {
        const res = await fetch('db/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        });
        const data = await res.json();
        if (data.success) {
            if (parseInt(data.admin) === 1) {
                window.location.href = 'pages/dashboard.php';
            } else {
                window.location.href = 'pages/home.php';
            }
        } else {
            loginError.textContent = data.message || 'Erreur de connexion.';
        }
    } catch (err) {
        loginError.textContent = 'Erreur serveur.';
    }
};
