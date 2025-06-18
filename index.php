<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        body {
            width: 100vw;
            height: 100vh;
            background: #000;
        }
        .video-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: 1;
        }
        .fade-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            background: #000;
            z-index: 2;
            opacity: 1;
            transition: opacity 2s;
        }
        .fade-overlay.hide {
            opacity: 0;
            pointer-events: none;
        }
        .blur-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 3;
            pointer-events: none;
            backdrop-filter: blur(8px);
            opacity: 0;
            transition: opacity 1s;
        }
        .blur-overlay.show {
            opacity: 1;
        }
        .enter-btn {
            position: fixed;
            left: 50%;
            bottom: 15em;
            transform: translateX(-50%);
            background: #000;
            color: #fff; 
            border: none;
            border-radius: 30px;
            padding: 18px 48px;
            font-size: 1.3em;
            font-weight: bold;
            cursor: pointer;
            z-index: 4;
            opacity: 0;
            transition: opacity 1s, box-shadow 0.3s;
            box-shadow: 0 0 16px 4px #fff, 0 4px 24px rgba(0,0,0,0.15);
            outline: 2px solid #fff;
        }
        .enter-btn.show {
            opacity: 1;
        }
        .enter-btn:hover {
            box-shadow: 0 0 48px 16px #fff, 0 4px 32px rgba(0,0,0,0.25);
            outline: 3px solid #fff;
        }
        .site-title {
            position: fixed;
            top: 180px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            font-size: 4.8em;
            font-weight: bold;
            letter-spacing: 0.18em;
            z-index: 4;
            opacity: 0;
            text-shadow: 0 0 24px #000, 0 0 8px #000;
            transition: opacity 1s, text-shadow 0.3s;
            user-select: none;
        }
        .site-title.show {
            opacity: 1;
        }
        .site-title:hover {
            text-shadow: 0 0 64px #000, 0 0 16px #fff;
        }
        .form-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #111;
            border-radius: 24px;
            box-shadow: 0 0 48px 8px #fff, 0 0 0 4px #fff;
            padding: 40px 32px 32px 32px;
            z-index: 10;
            min-width: 340px;
            display: none;
            flex-direction: column;
            align-items: stretch;
            animation: fadeIn 0.7s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
        .form-header {
            display: flex;
            margin-bottom: 24px;
        }
        .form-header button {
            flex: 1;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.2em;
            font-weight: bold;
            padding: 16px 0;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: border 0.2s, background 0.2s;
        }
        .form-header button.active {
            border-bottom: 3px solid #fff;
            background: rgba(255,255,255,0.08);
        }
        .form-fields {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .form-fields input {
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 1em;
            background: #222;
            color: #fff;
            outline: 2px solid #fff;
        }
        .form-fields input:focus {
            outline: 2px solid #fff;
            box-shadow: 0 0 8px #fff;
        }
        .form-fields button {
            margin-top: 12px;
            background: #fff;
            color: #111;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 0 16px #fff;
            transition: box-shadow 0.2s;
        }
        .form-fields button:hover {
            box-shadow: 0 0 32px #fff;
        }
        .form-error {
            color: #ff6666;
            margin-bottom: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <span class="site-title" id="siteTitle">SKINVAULT</span>
    <video class="video-bg" id="metroVideo" src="images/background/metro.mp4" autoplay muted playsinline></video>
    <div class="fade-overlay" id="fadeOverlay"></div>
    <div class="blur-overlay" id="blurOverlay"></div>
    <button class="enter-btn" id="enterBtn">Start Shopping</button>
    <div class="form-container" id="formContainer">
        <div class="form-header">
            <button id="btnSignup" class="active">INSCRIPTION</button>
            <button id="btnLogin">CONNEXION</button>
        </div>
        <form id="signupForm" class="form-fields">
            <div class="form-error" id="signupError"></div>
            <input type="text" id="signupUsername" placeholder="Nom d'utilisateur" required>
            <input type="email" id="signupEmail" placeholder="Email" required>
            <input type="password" id="signupPassword" placeholder="Mot de passe" required>
            <button type="submit">S'inscrire</button>
        </form>
        <form id="loginForm" class="form-fields" style="display:none;">
            <div class="form-error" id="loginError"></div>
            <input type="text" id="loginUsername" placeholder="Nom d'utilisateur" required>
            <input type="password" id="loginPassword" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
    <script>
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

        // Switch between signup/login
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

        // Hide title and button, show form on Start Shopping
        enterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            siteTitle.style.display = 'none';
            enterBtn.style.display = 'none';
            formContainer.style.display = 'flex';
        });

        // Simulated authentication (replace with AJAX/PHP in prod)
        signupForm.onsubmit = function(e) {
            e.preventDefault();
            // ... ici, AJAX pour inscription ...
            // Pour la démo, on simule un utilisateur non-admin
            window.location.href = 'pages/home.php';
        };
        loginForm.onsubmit = function(e) {
            e.preventDefault();
            // ... ici, AJAX pour connexion ...
            // Pour la démo, on simule un admin si username = admin
            if (loginUsername.value === 'admin') {
                window.location.href = 'pages/dashboard.php';
            } else {
                window.location.href = 'pages/home.php';
            }
        };
    </script>
</body>
</html>
