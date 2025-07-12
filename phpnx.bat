@echo off
setlocal EnableDelayedExpansion

REM === Configuration des couleurs ===
set GREEN=[92m
set RED=[91m
set YELLOW=[93m
set RESET=[0m

REM === Dossier de l'environnement virtuel ===
set ENV_PATH=.env
set ACTIVATOR=%ENV_PATH%\Scripts\activate.bat
set PYTHON_SCRIPT=server.py

REM === Forcer le répertoire de travail ===
cd /d C:\phpnx

REM === Affichage de l'en-tête ===
echo.
echo %GREEN%╔══════════════════════════════════════════════╗%RESET%
echo %GREEN%║ 🔥 Lancement de PHPNX avec environnement venv ║%RESET%
echo %GREEN%╚══════════════════════════════════════════════╝%RESET%
echo.

REM === Vérification de Python ===
where python >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo %RED%[ERREUR] ❌ Python n'est pas installé ou non accessible dans le PATH.%RESET%
    echo %YELLOW%👉 Veuillez installer Python et l'ajouter au PATH.%RESET%
    echo.
    pause
    exit /b 1
)

REM === Vérification de la version de Python ===
python --version | findstr /R "3\.[7-9]" >nul
if %ERRORLEVEL% neq 0 (
    echo %RED%[ERREUR] ❌ Python 3.7+ requis. Version actuelle non compatible.%RESET%
    echo.
    pause
    exit /b 1
)

REM === Vérification de l'environnement virtuel ===
if exist "%ACTIVATOR%" (
    echo %GREEN%[OK] Environnement Python détecté : %ENV_PATH%%RESET%
    call "%ACTIVATOR%"
    echo %GREEN%[INFO] Environnement virtuel activé.%RESET%
) else (
    echo %RED%[ERREUR] ❌ Aucun environnement virtuel trouvé à %ENV_PATH%%RESET%
    echo %YELLOW%👉 Création d'un nouvel environnement virtuel...%RESET%
    python -m venv %ENV_PATH%
    if !ERRORLEVEL! neq 0 (
        echo %RED%[ERREUR] ❌ Échec de la création de l'environnement virtuel.%RESET%
        echo.
        pause
        exit /b 1
    )
    echo %GREEN%[OK] Environnement virtuel créé : %ENV_PATH%%RESET%
    call "%ACTIVATOR%"
    echo %GREEN%[INFO] Environnement virtuel activé.%RESET%
)

REM === Vérification du script Python ===
if not exist "%PYTHON_SCRIPT%" (
    echo %RED%[ERREUR] ❌ Le script %PYTHON_SCRIPT% est introuvable.%RESET%
    echo %YELLOW%👉 Assurez-vous que %PYTHON_SCRIPT% existe dans le répertoire courant.%RESET%
    echo.
    pause
    exit /b 1
)

REM === Lancement du script Python ===
echo %GREEN%[INFO] Lancement de %PYTHON_SCRIPT%...%RESET%
python "%PYTHON_SCRIPT%"
if %ERRORLEVEL% neq 0 (
    echo %RED%[ERREUR] ❌ Échec de l'exécution de %PYTHON_SCRIPT%.%RESET%
    echo.
    pause
    exit /b 1
)

echo %GREEN%[OK] Exécution terminée.%RESET%