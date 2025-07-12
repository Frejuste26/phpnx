import subprocess
import os
import time
import webbrowser
import sys

# === CONFIGURATION ===
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
PHP_PATH = os.path.join(BASE_DIR, "php")
NGINX_PATH = os.path.join(BASE_DIR, "nginx")
APP_PATH = os.path.join(BASE_DIR, "app")
NGINX_CONF = os.path.join(NGINX_PATH, "conf", "nginx.conf")
NGINX_LOGS = os.path.join(NGINX_PATH, "logs")
ICON_PATH = os.path.join(BASE_DIR, "phpnx.ico")

def log(msg, level="INFO"):
    colors = {"INFO": "\033[92m", "ERROR": "\033[91m"}
    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    print(f"{colors.get(level, '')}[{timestamp}] [{level}] {msg}\033[0m")

def run(command, cwd=None):
    try:
        result = subprocess.run(command, shell=True, cwd=cwd, check=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
        return True
    except subprocess.CalledProcessError as e:
        log(f"Erreur lors de l'exécution de '{command}': {e.stderr}", "ERROR")
        return False

def stop_servers():
    log("🛑 Arrêt de PHP et NGINX...")
    run("taskkill /f /im php-cgi.exe")
    run("taskkill /f /im nginx.exe")
    log("✅ Tous les processus arrêtés.")

def start_servers():
    print("\n╔════════════════════════════════════╗")
    print("║ 🔥 Lancement de PHPNX (PHP + NGINX) ║")
    print("╚════════════════════════════════════╝")

    # Vérifications
    if not os.path.isfile(os.path.join(PHP_PATH, "php-cgi.exe")):
        log("php-cgi.exe introuvable dans C:\\phpnx\\php", "ERROR")
        return
    if not os.path.isfile(os.path.join(NGINX_PATH, "nginx.exe")):
        log("nginx.exe introuvable dans C:\\phpnx\\nginx", "ERROR")
        return
    if not os.path.isfile(NGINX_CONF):
        log("nginx.conf manquant dans C:\\phpnx\\nginx\\conf", "ERROR")
        return
    if not os.path.isfile(os.path.join(APP_PATH, "index.php")):
        log("index.php introuvable dans C:\\phpnx\\app", "ERROR")
        return
    if not os.path.isfile(ICON_PATH):
        log("phpnx.ico introuvable dans C:\\phpnx", "ERROR")
    os.makedirs(NGINX_LOGS, exist_ok=True)

    # Vérifier la configuration NGINX
    if not run(os.path.join(NGINX_PATH, "nginx.exe") + " -t -c " + NGINX_CONF, cwd=NGINX_PATH):
        log("Erreur dans la configuration NGINX. Vérifiez nginx.conf.", "ERROR")
        return

    # Arrêter les serveurs existants
    stop_servers()
    time.sleep(1)

    # Lancer PHP FastCGI
    try:
        php_process = subprocess.Popen(
            [os.path.join(PHP_PATH, "php-cgi.exe"), "-b", "127.0.0.1:9000"],
            cwd=PHP_PATH,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            creationflags=subprocess.CREATE_NO_WINDOW
        )
        time.sleep(1)
        if php_process.poll() is not None:
            stdout, stderr = php_process.communicate(timeout=5)
            log(f"Échec du démarrage de PHP FastCGI : {stderr.decode()}", "ERROR")
            return
        log("✅ PHP FastCGI lancé sur 127.0.0.1:9000")
    except Exception as e:
        log(f"Erreur lors du lancement de PHP : {e}", "ERROR")
        return

    # Lancer NGINX
    try:
        nginx_process = subprocess.Popen(
            [os.path.join(NGINX_PATH, "nginx.exe"), "-c", NGINX_CONF],
            cwd=NGINX_PATH,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            creationflags=subprocess.CREATE_NO_WINDOW
        )
        time.sleep(1)
        if nginx_process.poll() is not None:
            stdout, stderr = nginx_process.communicate(timeout=5)
            log(f"Échec du démarrage de NGINX : {stderr.decode()}", "ERROR")
            return
        log("✅ NGINX lancé")
    except Exception as e:
        log(f"Erreur lors du lancement de NGINX : {e}", "ERROR")
        return

    # Ouvrir le navigateur
    webbrowser.open("http://localhost")
    log("🌍 Navigateur ouvert sur http://localhost")

def restart_servers():
    print("\n╔═══════════════════════════════╗")
    print("║ 🔄 Redémarrage de PHPNX...    ║")
    print("╚═══════════════════════════════╝")
    stop_servers()
    time.sleep(1)
    start_servers()

def interactive_menu():
    print("\n╔═══════════════════════════════════════════╗")
    print("║ ✨ Bienvenue dans PHPNX - Phoenix Dev     ║")
    print("╚═══════════════════════════════════════════╝")
    print("│ [D] ▶️  Démarrer PHP + NGINX             │")
    print("│ [A] ⛔  Arrêter PHP + NGINX              │")
    print("│ [R] 🔄  Redémarrer PHP + NGINX           │")
    print("│ [Q] ❌  Quitter                          │")
    print("╚═══════════════════════════════════════════╝")
    choice = input("\nVotre choix : ").strip().upper()
    print("\n" + "═" * 45)
    actions = {
        "D": start_servers,
        "A": stop_servers,
        "R": restart_servers,
        "Q": lambda: sys.exit(0)
    }
    actions.get(choice, lambda: log("❌ Choix invalide", "ERROR"))()
    interactive_menu()

def main():
    log(f"Répertoire courant : {os.getcwd()}", "INFO")
    if len(sys.argv) == 1:
        interactive_menu()
    else:
        actions = {
            "start": start_servers,
            "stop": stop_servers,
            "restart": restart_servers
        }
        actions.get(sys.argv[1].lower(), lambda: log("Commande inconnue", "ERROR"))()

if __name__ == "__main__":
    main()