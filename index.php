<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SkinVault</title>
    <link rel="stylesheet" href="css/index.css">
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
    <script src="js/index.js"></script>
</body>
</html>
