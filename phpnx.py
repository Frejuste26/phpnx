#!/usr/bin/env python3
"""
PHPNX - Le Phoenix s'élève !
Un environnement de développement PHP local, rapide, portable et élégant.
Auteur: Kei Prince Frejuste
"""

import os
import sys
import json
import time
import subprocess
import webbrowser
import signal
import threading
from pathlib import Path
from datetime import datetime

try:
    import psutil
    import colorama
    from colorama import Fore, Style, Back
except ImportError:
    print("❌ Dépendances manquantes. Installation en cours...")
    subprocess.check_call([sys.executable, "-m", "pip", "install", "psutil", "colorama"])
    import psutil
    import colorama
    from colorama import Fore, Style, Back

colorama.init()

class PhoenixLogger:
    """Système de logging avec style Phoenix"""
    
    def __init__(self, log_file="logs/phpnx.log"):
        self.log_file = Path(log_file)
        self.log_file.parent.mkdir(exist_ok=True)
    
    def log(self, message, level="INFO"):
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        log_entry = f"[{timestamp}] [{level}] {message}\n"
        
        # Écrire dans le fichier
        with open(self.log_file, "a", encoding="utf-8") as f:
            f.write(log_entry)
        
        # Afficher dans la console avec couleurs
        colors = {
            "INFO": Fore.CYAN,
            "SUCCESS": Fore.GREEN,
            "WARNING": Fore.YELLOW,
            "ERROR": Fore.RED,
            "PHOENIX": Fore.MAGENTA
        }
        
        color = colors.get(level, Fore.WHITE)
        print(f"{color}[{level}]{Style.RESET_ALL} {message}")

class PHPNXServer:
    """Gestionnaire principal du serveur PHPNX"""
    
    def __init__(self):
        self.base_dir = Path(__file__).parent
        self.config_file = self.base_dir / "config" / "settings.json"
        self.logger = PhoenixLogger()
        self.nginx_process = None
        self.php_process = None
        self.config = self.load_config()
        
        # Créer les dossiers nécessaires
        self.create_directories()
    
    def create_directories(self):
        """Créer les dossiers nécessaires"""
        dirs = ["app", "static/css", "static/js", "logs", "config"]
        for dir_name in dirs:
            (self.base_dir / dir_name).mkdir(parents=True, exist_ok=True)
    
    def load_config(self):
        """Charger la configuration"""
        default_config = {
            "server": {
                "host": "localhost",
                "port": 80,
                "php_port": 9000
            },
            "paths": {
                "app_dir": "app",
                "static_dir": "static",
                "logs_dir": "logs"
            },
            "auto_open_browser": True,
            "phoenix_theme": True
        }
        
        try:
            if self.config_file.exists():
                with open(self.config_file, "r", encoding="utf-8") as f:
                    return json.load(f)
            else:
                # Créer le fichier de config par défaut
                self.config_file.parent.mkdir(exist_ok=True)
                with open(self.config_file, "w", encoding="utf-8") as f:
                    json.dump(default_config, f, indent=4)
                return default_config
        except Exception as e:
            self.logger.log(f"Erreur lors du chargement de la config: {e}", "ERROR")
            return default_config
    
    def check_dependencies(self):
        """Vérifier les dépendances (PHP, NGINX)"""
        php_path = self.base_dir / "php" / "php-cgi.exe"
        nginx_path = self.base_dir / "nginx" / "nginx.exe"
        
        missing = []
        
        if not php_path.exists():
            missing.append("PHP CGI (php-cgi.exe)")
        
        if not nginx_path.exists():
            missing.append("NGINX (nginx.exe)")
        
        if missing:
            self.logger.log("❌ Dépendances manquantes:", "ERROR")
            for dep in missing:
                print(f"   - {dep}")
            
            print(f"\n{Fore.YELLOW}📥 Guide d'installation:{Style.RESET_ALL}")
            print("1. Télécharger PHP depuis https://www.php.net/downloads")
            print("2. Télécharger NGINX depuis https://nginx.org/en/download.html")
            print("3. Extraire dans les dossiers php/ et nginx/")
            return False
        
        return True
    
    def kill_existing_processes(self):
        """Tuer les processus existants"""
        killed = []
        
        for proc in psutil.process_iter(['pid', 'name']):
            try:
                if proc.info['name'] in ['nginx.exe', 'php-cgi.exe']:
                    proc.terminate()
                    killed.append(proc.info['name'])
            except (psutil.NoSuchProcess, psutil.AccessDenied):
                pass
        
        if killed:
            self.logger.log(f"🧼 Processus nettoyés: {', '.join(killed)}", "INFO")
        
        time.sleep(1)
    
    def create_nginx_config(self):
        """Créer la configuration NGINX"""
        nginx_conf = f"""
worker_processes 1;
error_log logs/error.log;
pid logs/nginx.pid;

events {{
    worker_connections 1024;
}}

http {{
    include mime.types;
    default_type application/octet-stream;
    
    access_log logs/access.log;
    sendfile on;
    keepalive_timeout 65;
    
    server {{
        listen {self.config['server']['port']};
        server_name {self.config['server']['host']};
        
        root {self.base_dir}/app;
        index index.php index.html index.htm;
        
        # Fichiers statiques
        location /static/ {{
            alias {self.base_dir}/static/;
            expires 1d;
        }}
        
        # PHP
        location ~ \.php$ {{
            fastcgi_pass 127.0.0.1:{self.config['server']['php_port']};
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }}
        
        # Sécurité
        location ~ /\. {{
            deny all;
        }}
    }}
}}
"""
        
        nginx_conf_path = self.base_dir / "nginx" / "conf" / "nginx.conf"
        nginx_conf_path.parent.mkdir(parents=True, exist_ok=True)
        
        with open(nginx_conf_path, "w", encoding="utf-8") as f:
            f.write(nginx_conf)
        
        self.logger.log("⚙️ Configuration NGINX créée", "SUCCESS")
    
    def create_default_php_files(self):
        """Créer les fichiers PHP par défaut"""
        index_php = """<?php
echo "<h1>🔥 PHPNX - Le Phoenix s'élève !</h1>";
echo "<p>Votre serveur PHP est prêt !</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Serveur: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<hr>";
echo "<p><a href='info.php'>Voir les informations PHP</a></p>";
?>"""
        
        info_php = """<?php
phpinfo();
?>"""
        
        # Créer index.php
        index_file = self.base_dir / "app" / "index.php"
        if not index_file.exists():
            with open(index_file, "w", encoding="utf-8") as f:
                f.write(index_php)
        
        # Créer info.php
        info_file = self.base_dir / "app" / "info.php"
        if not info_file.exists():
            with open(info_file, "w", encoding="utf-8") as f:
                f.write(info_php)
        
        self.logger.log("📄 Fichiers PHP par défaut créés", "SUCCESS")
    
    def start_php_cgi(self):
        """Démarrer PHP CGI"""
        php_path = self.base_dir / "php" / "php-cgi.exe"
        cmd = [
            str(php_path),
            "-b", f"127.0.0.1:{self.config['server']['php_port']}"
        ]
        
        try:
            self.php_process = subprocess.Popen(
                cmd,
                stdout=subprocess.PIPE,
                stderr=subprocess.PIPE,
                cwd=str(self.base_dir)
            )
            
            time.sleep(2)  # Attendre le démarrage
            
            if self.php_process.poll() is None:
                self.logger.log(f"🐘 PHP CGI démarré sur le port {self.config['server']['php_port']}", "SUCCESS")
                return True
            else:
                self.logger.log("❌ Échec du démarrage PHP CGI", "ERROR")
                return False
                
        except Exception as e:
            self.logger.log(f"❌ Erreur PHP CGI: {e}", "ERROR")
            return False
    
    def start_nginx(self):
        """Démarrer NGINX"""
        nginx_path = self.base_dir / "nginx" / "nginx.exe"
        
        try:
            self.nginx_process = subprocess.Popen(
                [str(nginx_path)],
                stdout=subprocess.PIPE,
                stderr=subprocess.PIPE,
                cwd=str(self.base_dir / "nginx")
            )
            
            time.sleep(2)  # Attendre le démarrage
            
            if self.nginx_process.poll() is None:
                self.logger.log(f"🌐 NGINX démarré sur http://{self.config['server']['host']}:{self.config['server']['port']}", "SUCCESS")
                return True
            else:
                self.logger.log("❌ Échec du démarrage NGINX", "ERROR")
                return False
                
        except Exception as e:
            self.logger.log(f"❌ Erreur NGINX: {e}", "ERROR")
            return False
    
    def start_servers(self):
        """Démarrer les serveurs"""
        self.logger.log("🔥 Le Phoenix s'élève...", "PHOENIX")
        
        if not self.check_dependencies():
            return False
        
        self.kill_existing_processes()
        self.create_nginx_config()
        self.create_default_php_files()
        
        # Démarrer PHP CGI
        if not self.start_php_cgi():
            return False
        
        # Démarrer NGINX
        if not self.start_nginx():
            self.stop_servers()
            return False
        
        # Ouvrir le navigateur
        if self.config['auto_open_browser']:
            url = f"http://{self.config['server']['host']}:{self.config['server']['port']}"
            threading.Timer(2, lambda: webbrowser.open(url)).start()
        
        self.logger.log("🎉 Serveurs démarrés avec succès!", "SUCCESS")
        return True
    
    def stop_servers(self):
        """Arrêter les serveurs"""
        self.logger.log("🛑 Arrêt des serveurs...", "INFO")
        
        # Arrêter NGINX
        if self.nginx_process:
            self.nginx_process.terminate()
            self.nginx_process = None
        
        # Arrêter PHP CGI
        if self.php_process:
            self.php_process.terminate()
            self.php_process = None
        
        # Nettoyer les processus restants
        self.kill_existing_processes()
        
        self.logger.log("✅ Serveurs arrêtés", "SUCCESS")
    
    def restart_servers(self):
        """Redémarrer les serveurs"""
        self.logger.log("🔄 Redémarrage des serveurs...", "INFO")
        self.stop_servers()
        time.sleep(2)
        return self.start_servers()
    
    def show_status(self):
        """Afficher le statut des serveurs"""
        print(f"\n{Fore.MAGENTA}🔥 PHPNX - Statut des serveurs{Style.RESET_ALL}")
        print("=" * 40)
        
        # Vérifier PHP CGI
        php_running = any(proc.info['name'] == 'php-cgi.exe' for proc in psutil.process_iter(['name']))
        php_status = f"{Fore.GREEN}✅ Actif{Style.RESET_ALL}" if php_running else f"{Fore.RED}❌ Inactif{Style.RESET_ALL}"
        print(f"PHP CGI: {php_status}")
        
        # Vérifier NGINX
        nginx_running = any(proc.info['name'] == 'nginx.exe' for proc in psutil.process_iter(['name']))
        nginx_status = f"{Fore.GREEN}✅ Actif{Style.RESET_ALL}" if nginx_running else f"{Fore.RED}❌ Inactif{Style.RESET_ALL}"
        print(f"NGINX: {nginx_status}")
        
        if php_running and nginx_running:
            print(f"\n🌐 Serveur accessible sur: {Fore.CYAN}http://{self.config['server']['host']}:{self.config['server']['port']}{Style.RESET_ALL}")

def show_banner():
    """Afficher la bannière Phoenix"""
    banner = f"""
{Fore.MAGENTA}
    ████████╗██╗  ██╗██████╗ ███╗   ██╗██╗  ██╗
    ██╔═══██║██║  ██║██╔══██╗████╗  ██║╚██╗██╔╝
    ██║   ██║███████║██████╔╝██╔██╗ ██║ ╚███╔╝ 
    ██║   ██║██╔══██║██╔═══╝ ██║╚██╗██║ ██╔██╗ 
    ╚██████╔╝██║  ██║██║     ██║ ╚████║██╔╝ ██╗
     ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝  ╚═══╝╚═╝  ╚═╝
{Style.RESET_ALL}
{Fore.YELLOW}Le Phoenix s'élève ! 🐦‍🔥{Style.RESET_ALL}
{Fore.CYAN}Environnement PHP portable et élégant{Style.RESET_ALL}
"""
    print(banner)

def show_menu():
    """Afficher le menu interactif"""
    print(f"\n{Fore.CYAN}🎯 Menu PHPNX{Style.RESET_ALL}")
    print("=" * 20)
    print("1. 🚀 Démarrer les serveurs")
    print("2. 🛑 Arrêter les serveurs")
    print("3. 🔄 Redémarrer les serveurs")
    print("4. 📊 Statut des serveurs")
    print("5. 🌐 Ouvrir dans le navigateur")
    print("6. 🚪 Quitter")
    print("=" * 20)

def main():
    """Fonction principale"""
    show_banner()
    
    server = PHPNXServer()
    
    # Gestion des signaux pour un arrêt propre
    def signal_handler(sig, frame):
        print(f"\n{Fore.YELLOW}🔥 Arrêt du Phoenix...{Style.RESET_ALL}")
        server.stop_servers()
        sys.exit(0)
    
    signal.signal(signal.SIGINT, signal_handler)
    
    # Si des arguments sont passés
    if len(sys.argv) > 1:
        command = sys.argv[1].lower()
        
        if command == "start":
            server.start_servers()
        elif command == "stop":
            server.stop_servers()
        elif command == "restart":
            server.restart_servers()
        elif command == "status":
            server.show_status()
        else:
            print(f"{Fore.RED}❌ Commande inconnue: {command}{Style.RESET_ALL}")
            print("Commandes disponibles: start, stop, restart, status")
        
        return
    
    # Mode interactif
    while True:
        show_menu()
        
        try:
            choice = input(f"\n{Fore.YELLOW}Choisissez une option (1-6): {Style.RESET_ALL}").strip()
            
            if choice == "1":
                server.start_servers()
            elif choice == "2":
                server.stop_servers()
            elif choice == "3":
                server.restart_servers()
            elif choice == "4":
                server.show_status()
            elif choice == "5":
                url = f"http://{server.config['server']['host']}:{server.config['server']['port']}"
                webbrowser.open(url)
                print(f"🌐 Ouverture de {url}")
            elif choice == "6":
                print(f"{Fore.YELLOW}🔥 Le Phoenix retourne aux cendres... À bientôt !{Style.RESET_ALL}")
                server.stop_servers()
                break
            else:
                print(f"{Fore.RED}❌ Option invalide. Choisissez entre 1 et 6.{Style.RESET_ALL}")
                
        except KeyboardInterrupt:
            print(f"\n{Fore.YELLOW}🔥 Arrêt du Phoenix...{Style.RESET_ALL}")
            server.stop_servers()
            break
        except Exception as e:
            print(f"{Fore.RED}❌ Erreur: {e}{Style.RESET_ALL}")

if __name__ == "__main__":
    main()
