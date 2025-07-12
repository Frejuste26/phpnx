<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔥 PHPNX - Le Phoenix s'élève !</title>
    <link rel="icon" href="/static/favicon.ico" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* Animation de particules Phoenix */
        .phoenix-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #ff6b6b;
            border-radius: 50%;
            animation: float 6s infinite ease-in-out;
            opacity: 0.7;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        /* Header */
        header {
            text-align: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 1;
        }

        .logo {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* Main content */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        .status-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 2rem;
            margin: 1rem 0;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            max-width: 600px;
            width: 100%;
        }

        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
            animation: blink 2s infinite;
        }

        .status-active {
            background: #4CAF50;
        }

        .status-inactive {
            background: #f44336;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.5; }
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
            width: 100%;
            max-width: 1000px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.15);
        }

        .info-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #ffd700;
        }

        .info-card p {
            opacity: 0.9;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
            color: white;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #ff5252, #ff7979);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.2);
            padding: 2rem 1rem;
            text-align: center;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            color: #ffd700;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .footer-section p, .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            line-height: 1.6;
        }

        .footer-section a:hover {
            color: #ffd700;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: #ffd700;
            color: #333;
            transform: translateY(-2px);
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .title {
                font-size: 2rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Particules Phoenix -->
    <div class="phoenix-particles" id="particles"></div>

    <header>
        <div class="logo">🔥</div>
        <h1 class="title">PHPNX</h1>
        <p class="subtitle">Le Phoenix s'élève ! Environnement PHP portable et élégant</p>
    </header>

    <main>
        <div class="status-card">
            <h2>🚀 Statut du Serveur</h2>
            <p>
                <span class="status-indicator status-active"></span>
                Serveur PHP & NGINX actifs
            </p>
            <p>Votre environnement de développement est prêt !</p>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h3>🐘 PHP</h3>
                <p>Version: <?php echo phpversion(); ?></p>
                <p>Moteur FastCGI prêt</p>
            </div>
            
            <div class="info-card">
                <h3>🌐 NGINX</h3>
                <p>Serveur web haute performance</p>
                <p>Port: <?php echo $_SERVER['SERVER_PORT']; ?></p>
            </div>
            
            <div class="info-card">
                <h3>📁 Projet</h3>
                <p>Dossier: /app</p>
                <p>Environnement portable</p>
            </div>
            
            <div class="info-card">
                <h3>🔧 Outils</h3>
                <p>Configuration automatique</p>
                <p>Logs intégrés</p>
            </div>
        </div>

        <div class="action-buttons">
            <a href="/info.php" class="btn btn-primary">
                📊 Informations PHP
            </a>
            <a href="/static/" class="btn btn-secondary">
                📁 Fichiers statiques
            </a>
            <button class="btn btn-secondary" onclick="window.location.reload()">
                🔄 Actualiser
            </button>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-section">
                    <h4>🔥 PHPNX</h4>
                    <p>Environnement de développement PHP portable, rapide et élégant.</p>
                    <p>Conçu pour les développeurs qui méritent mieux qu'un localhost bricolé.</p>
                </div>
                
                <div class="footer-section">
                    <h4>🚀 Fonctionnalités</h4>
                    <p>✅ Zéro configuration manuelle</p>
                    <p>✅ 100% portable</p>
                    <p>✅ Interface interactive</p>
                    <p>✅ Démarrage en un clic</p>
                </div>
                
                <div class="footer-section">
                    <h4>📧 Contact</h4>
                    <p>Développé par <strong>Kei Prince Frejuste</strong></p>
                    <p>Email: <a href="mailto:keifrejuste26@gmail.com">keifrejuste26@gmail.com</a></p>
                    <div class="social-links">
                        <a href="https://github.com/frejuste26" class="social-link" title="GitHub">
                            🐱
                        </a>
                        <a href="https://portfolio-edumanagers-projects.vercel.app/" class="social-link" title="Portfolio">
                            🌐
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>© 2025 PHPNX - "Comme le Phoenix, tout projet peut renaître de ses cendres."</p>
            </div>
        </div>
    </footer>

    <script>
        // Génération des particules Phoenix
        function createParticles() {
            const container = document.getElementById('particles');
            const colors = ['#ff6b6b', '#ffd700', '#ff8e8e', '#ffb347'];
            
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                container.appendChild(particle);
            }
        }

        // Animation au chargement
        window.addEventListener('load', () => {
            createParticles();
            
            // Animation d'apparition des cartes
            const cards = document.querySelectorAll('.status-card, .info-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });

        // Effet de hover sur les boutons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseover', () => {
                btn.style.transform = 'translateY(-2px) scale(1.05)';
            });
            
            btn.addEventListener('mouseout', () => {
                btn.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Message de bienvenue dans la console
        console.log(`
🔥 PHPNX - Le Phoenix s'élève !
==================================
Environnement PHP portable et élégant
Développé par Kei Prince Frejuste
GitHub: https://github.com/frejuste26
Email: keifrejuste26@gmail.com
==================================
        `);
    </script>
</body>
</html>
