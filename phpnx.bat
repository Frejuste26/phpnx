@echo off
chcp 65001 >nul
title 🔥 PHPNX - Le Phoenix s'élève !

:: Définir les couleurs
set "RED=[31m"
set "GREEN=[32m"
set "YELLOW=[33m"
set "BLUE=[34m"
set "MAGENTA=[35m"
set "CYAN=[36m"
set "WHITE=[37m"
set "RESET=[0m"

:: Bannière Phoenix
echo.
echo %MAGENTA%    ████████╗██╗  ██╗██████╗ ███╗   ██╗██╗  ██╗
echo     ██╔═══██║██║  ██║██╔══██╗████╗  ██║╚██╗██╔╝
echo     ██║   ██║███████║██████╔╝██╔██╗ ██║ ╚███╔╝ 
echo     ██║   ██║██╔══██║██╔═══╝ ██║╚██╗██║ ██╔██╗ 
echo     ╚██████╔╝██║  ██║██║     ██║ ╚████║██╔╝ ██╗
echo      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝  ╚═══╝╚═╝  ╚═╝%RESET%
echo.
echo %YELLOW%🔥 Le Phoenix s'élève ! 🐦‍🔥%RESET%
echo %CYAN%Environnement PHP portable et élégant%RESET%
echo %WHITE%Développé par Kei Prince Frejuste%RESET%
echo.

:: Vérifier Python
echo %CYAN%[INFO]%RESET% Vérification de Python...
python --version >nul 2>&1
if errorlevel 1 (
    echo %RED%[ERREUR]%RESET% Python n'est pas installé ou non trouvé dans le PATH
    echo %YELLOW%Veuillez installer Python 3.8+ depuis https://python.org%RESET%
    echo.
    pause
    exit /b 1
)

:: Créer l'environnement virtuel si nécessaire
if not exist ".env" (
    echo %CYAN%[INFO]%RESET% Création de l'environnement virtuel Python...
    python -m venv .env
    if errorlevel 1 (
        echo %RED%[ERREUR]%RESET% Impossible de créer l'environnement virtuel
        pause
        exit /b 1
    )
)

:: Activer l'environnement virtuel
echo %CYAN%[INFO]%RESET% Activation de l'environnement virtuel...
call .env\Scripts\activate.bat

:: Installer les dépendances
if exist "requirements.txt" (
    echo %CYAN%[INFO]%RESET% Installation des dépendances...
    pip install -r requirements.txt --quiet
    if errorlevel 1 (
        echo %YELLOW%[ATTENTION]%RESET% Certaines dépendances n'ont pas pu être installées
        echo %CYAN%[INFO]%RESET% Tentative d'installation manuelle...
        pip install psutil colorama --quiet
    )
)

:: Vérifier les dépendances critiques
echo %CYAN%[INFO]%RESET% Vérification des dépendances critiques...

:: Vérifier PHP
if not exist "php\php-cgi.exe" (
    echo %RED%[ERREUR]%RESET% PHP-CGI non trouvé dans le dossier php/
    echo %YELLOW%Guide d'installation:%RESET%
    echo 1. Télécharger PHP depuis https://www.php.net/downloads
    echo 2. Extraire dans le dossier php/
    echo 3. Vérifier que php-cgi.exe est présent
    echo.
    pause
    exit /b 1
)

:: Vérifier NGINX
if not exist "nginx\nginx.exe" (
    echo %RED%[ERREUR]%RESET% NGINX non trouvé dans le dossier nginx/
    echo %YELLOW%Guide d'installation:%RESET%
    echo 1. Télécharger NGINX depuis https://nginx.org/en/download.html
    echo 2. Extraire dans le dossier nginx/
    echo 3. Vérifier que nginx.exe est présent
    echo.
    pause
    exit /b 1
)

:: Lancer PHPNX
echo %GREEN%[SUCCESS]%RESET% Toutes les dépendances sont présentes
echo %MAGENTA%🚀 Lancement de PHPNX...%RESET%
echo.

:: Démarrer le script Python
python phpnx.py

:: Nettoyage en cas d'arrêt
echo.
echo %YELLOW%🔥 Le Phoenix retourne aux cendres...%RESET%
echo %CYAN%Nettoyage des processus...%RESET%

:: Tuer les processus PHP et NGINX si ils existent encore
taskkill /F /IM php-cgi.exe >nul 2>&1
taskkill /F /IM nginx.exe >nul 2>&1

echo %GREEN%✅ Nettoyage terminé%RESET%
echo.
pause
