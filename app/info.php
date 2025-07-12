<?php
// PHPNX - Page d'accueil
// Auteur: Kei Prince Frejuste

// Fonction pour obtenir des informations système
function getSystemInfo() {
    $info = [
        'php_version' => phpversion(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'server_name' => $_SERVER['SERVER_NAME'] ?? 'localhost',
        'server_port' => $_SERVER['SERVER_PORT'] ?? '80',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? '',
        'request_time' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
    
    return $info;
}

// Fonction pour obtenir les extensions PHP chargées
function getLoadedExtensions() {
    $extensions = get_loaded_extensions();
    sort($extensions);
    return $extensions;
}

// Fonction pour vérifier le statut des fonctionnalités
function checkFeatures() {
    $features = [
        'cURL' => extension_loaded('curl'),
        'GD' => extension_loaded('gd'),
        'PDO' => extension_loaded('pdo'),
        'JSON' => extension_loaded('json'),
        'XML' => extension_loaded('xml'),
        'ZIP' => extension_loaded('zip'),
        'OpenSSL' => extension_loaded('openssl'),
        'MySQLi' => extension_loaded('mysqli'),
        'SQLite' => extension_loaded('sqlite3'),
        'Redis' => extension_loaded('redis'),
        'Memcached' => extension_loaded('memcached'),
    ];
    
    return $features;
}

$systemInfo = getSystemInfo();
$extensions = getLoadedExtensions();
$features = checkFeatures();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔥 PHPNX - Le Phoenix s'élève !</title>
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
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
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
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            color: #ffd700;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #ffd700;
        }

        .info-value {
            color: #fff;
            opacity: 0.9;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
        }

        .status-icon {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .status-active {
            background: #4CAF50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }

        .status-inactive {
            background: #f44336;
            box-shadow: 0 0 10px rgba(244, 67, 54, 0.5);
        }

        .extensions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .extension-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem;
            border-radius: 5px;
            text-align: center;
            font-size: 0.9rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
            flex-wrap: wrap;
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

        .footer {
            text-align: center;
            margin-top: 3rem;
            padding: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer p {
            opacity: 0.7;
            margin-bottom: 0.5rem;
        }

        .footer a {
            color: #ffd700;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .title {
                font-size: 2rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔥</div>
            <h1 class="title">PHPNX</h1>
            <p class="subtitle">Le Phoenix s'élève ! Environnement PHP portable et élégant</p>
        </div>

        <div class="grid">
            <!-- Informations système -->
            <div class="card">
                <h2>📊 Informations Système</h2>
                <div class="info-item">
                    <span class="info-label">Version PHP:</span>
                    <span class="info-value"><?= $systemInfo['php_version'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Serveur:</span>
                    <span class="info-value"><?= $systemInfo['server_software'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Host:</span>
                    <span class="info-value"><?= $systemInfo['server_name'] ?>:<?= $systemInfo['server_port'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Démarré le:</span>
                    <span class="info-value"><?= $systemInfo['request_time'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mémoire limite:</span>
                    <span class="info-value"><?= $systemInfo['memory_limit'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Temps d'exécution:</span>
                    <span class="info-value"><?= $systemInfo['max_execution_time'] ?>s</span>
                </div>
            </div>

            <!-- Statut des fonctionnalités -->
            <div class="card">
                <h2>🚀 Statut des Fonctionnalités</h2>
                <div class="status-grid">
                    <?php foreach ($features as $feature => $status): ?>
                        <div class="status-item">
                            <div class="status-icon <?= $status ? 'status-active' : 'status-inactive' ?>"></div>
                            <span><?= $feature ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Configuration -->
            <div class="card">
                <h2>⚙️ Configuration</h2>
                <div class="info-item">
                    <span class="info-label">Upload max:</span>
                    <span class="info-value"><?= $systemInfo['upload_max_filesize'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">POST max:</span>
                    <span class="info-value"><?= $systemInfo['post_max_size'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Extensions:</span>
                    <span class="info-value"><?= count($extensions) ?> chargées</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Document root:</span>
                    <span class="info-value"><?= basename($systemInfo['document_root']) ?></span>
                </div>
            </div>

            <!-- Extensions -->
            <div class="card">
                <h2>🧩 Extensions PHP</h2>
                <div class="extensions-grid">
                    <?php foreach (array_slice($extensions, 0, 20) as $extension): ?>
                        <div class="extension-item"><?= $extension ?></div>
                    <?php endforeach; ?>
                    <?php if (count($extensions) > 20): ?>
                        <div class="extension-item">+<?= count($extensions) - 20 ?> autres</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="/info.php" class="btn btn-primary">
                📋 PHPInfo complète
            </a>
            <a href="/static/" class="btn btn-secondary">
                📁 Fichiers statiques
            </a>
            <button class="btn btn-secondary" onclick="window.location.reload()">
                🔄 Actualiser
            </button>
        </div>

        <div class="footer">
            <p>🔥 <strong>PHPNX</strong> - Environnement PHP portable et élégant</p>
            <p>Développé avec ❤️ par <a href="mailto:keifrejuste26@gmail.com">Kei Prince Frejuste</a></p>
            <p>
                <a href="https://github.com/frejuste26">GitHub</a> | 
                <a href="https://portfolio-edumanagers-projects.vercel.app/">Portfolio</a>
            </p>
            <p><em>"Comme le Phoenix, tout projet peut renaître de ses cendres."</em></p>
        </div>
    </div>

    <script>
        // Animation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Message console
        console.log(`
🔥 PHPNX - Le Phoenix s'élève !
===============================
PHP Version: <?= phpversion() ?>

Server: <?= $systemInfo['server_software'] ?>

Host: <?= $systemInfo['server_name'] ?>:<?= $systemInfo['server_port'] ?>

Extensions: <?= count($extensions) ?>

===============================
Développé par Kei Prince Frejuste
GitHub: https://github.com/frejuste26
        `);
    </script>
</body>
</html>
