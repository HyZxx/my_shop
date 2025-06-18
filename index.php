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
            bottom: 150px;
            transform: translateX(-50%);
            background: #000; /* Changement ici */
            color: #fff;      /* Changement ici */
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
    </style>
</head>
<body>
    <video class="video-bg" id="metroVideo" src="images/background/metro.mp4" autoplay muted playsinline></video>
    <div class="fade-overlay" id="fadeOverlay"></div>
    <div class="blur-overlay" id="blurOverlay"></div>
    <button class="enter-btn" id="enterBtn">Start Shopping</button>
    <script>
        const video = document.getElementById('metroVideo');
        const fadeOverlay = document.getElementById('fadeOverlay');
        const blurOverlay = document.getElementById('blurOverlay');
        const enterBtn = document.getElementById('enterBtn');

        // Fade out overlay when video is ready to play
        video.addEventListener('canplay', () => {
            setTimeout(() => {
                fadeOverlay.classList.add('hide');
                // Show button after fade
                setTimeout(() => {
                    enterBtn.classList.add('show');
                }, 1500);
            }, 800); // petit délai pour effet
        });

        // Show blur at the end of the video
        video.addEventListener('ended', () => {
            blurOverlay.classList.add('show');
        });

        // Optionally, keep the last frame of the video visible
        video.addEventListener('ended', () => {
            video.pause();
        });

        // Button click: redirect
        enterBtn.addEventListener('click', () => {
            window.location.href = 'home.php';
        });
    </script>
</body>
</html>
