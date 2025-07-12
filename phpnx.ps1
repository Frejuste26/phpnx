# ─── CONFIG ──────────────────────────────
$envPath = ".env"
$activator = "$envPath\Scripts\Activate.ps1"
$pythonScript = "server.py"
$logo = @"
╔══════════════════════════════════════════════╗
║  ██████╗ ██╗  ██╗██████╗ ███╗   ██╗██╗  ██╗  ║
║  ██╔══██╗██║  ██║██╔══██╗████╗  ██║╚██╗██╔╝  ║
║  ██████╔╝███████║██████╔╝██╔██╗ ██║ ╚███╔╝   ║
║  ██╔═══╝ ██╔══██║██╔═══╝ ██║╚██╗██║ ██╔██╗   ║
║  ██║     ██║  ██║██║     ██║ ╚████║██╔╝ ██╗  ║
║  ╚═╝     ╚═╝  ╚═╝╚═╝     ╚═╝  ╚═══╝╚═╝  ╚═╝  ║
╚══════════════════════════════════════════════╝
"@

# ─── AFFICHAGE DE L'EN-TÊTE ─────────────
Write-Host "`n$logo`n" -ForegroundColor DarkYellow
Write-Host "║ 🔥 Lancement de PHPNX avec venv PowerShell ║" -ForegroundColor DarkYellow
Write-Host "╚══════════════════════════════════════════════╝`n" -ForegroundColor DarkYellow

# ─── CHECK PYTHON ────────────────────────
Write-Host "║ Vérification de Python..." -NoNewline -ForegroundColor Cyan
if (-not (Get-Command python -ErrorAction SilentlyContinue)) {
    Write-Host "`n╚══════════════════════════════════════════════╝"
    Write-Host "[ERREUR] ❌ Python n'est pas installé ou non accessible dans le PATH." -ForegroundColor Red
    Write-Host "👉 Veuillez installer Python et l'ajouter au PATH." -ForegroundColor Yellow
    Write-Host "`nAppuyez sur une touche pour quitter..." -ForegroundColor Gray
    $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
    exit 1
}
Write-Host " ✅ OK" -ForegroundColor Green

# ─── CHECK SCRIPT PYTHON ─────────────────
Write-Host "║ Vérification de $pythonScript..." -NoNewline -ForegroundColor Cyan
if (-not (Test-Path $pythonScript)) {
    Write-Host "`n╚══════════════════════════════════════════════╝"
    Write-Host "[ERREUR] ❌ Le script $pythonScript est introuvable." -ForegroundColor Red
    Write-Host "👉 Assurez-vous que $pythonScript existe dans le répertoire courant." -ForegroundColor Yellow
    Write-Host "`nAppuyez sur une touche pour quitter..." -ForegroundColor Gray
    $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
    exit 1
}
Write-Host " ✅ OK" -ForegroundColor Green

# ─── ENV SETUP ───────────────────────────
Write-Host "║ Vérification de l'environnement virtuel..." -NoNewline -ForegroundColor Cyan
if (-not (Test-Path $activator)) {
    Write-Host "`n║ [INFO] Environnement virtuel introuvable. Création en cours..." -ForegroundColor Yellow
    python -m venv $envPath
    if (-not (Test-Path $activator)) {
        Write-Host "╚══════════════════════════════════════════════╝"
        Write-Host "[ERREUR] ❌ Échec de la création de l'environnement virtuel." -ForegroundColor Red
        Write-Host "👉 Vérifiez les permissions ou l'installation de Python." -ForegroundColor Yellow
        Write-Host "`nAppuyez sur une touche pour quitter..." -ForegroundColor Gray
        $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
        exit 1
    }
    Write-Host "║ [OK] Environnement virtuel créé : $envPath" -ForegroundColor Green
}
Write-Host " ✅ OK" -ForegroundColor Green

# ─── ACTIVER ET LANCER ───────────────────
Write-Host "║ Activation de l'environnement virtuel..." -NoNewline -ForegroundColor Cyan
try {
    & $activator
    Write-Host " ✅ OK" -ForegroundColor Green
} catch {
    Write-Host "`n╚══════════════════════════════════════════════╝"
    Write-Host "[ERREUR] ❌ Échec de l'activation de l'environnement virtuel." -ForegroundColor Red
    Write-Host "👉 Erreur : $_" -ForegroundColor Yellow
    Write-Host "`nAppuyez sur une touche pour quitter..." -ForegroundColor Gray
    $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
    exit 1
}

Write-Host "╚══════════════════════════════════════════════╝"
Write-Host "[INFO] Lancement de $pythonScript..." -ForegroundColor Cyan
try {
    python $pythonScript
    if ($LASTEXITCODE -ne 0) {
        throw "Échec de l'exécution de $pythonScript"
    }
    Write-Host "`n[OK] Exécution de $pythonScript terminée avec succès." -ForegroundColor Green
} catch {
    Write-Host "[ERREUR] ❌ Échec de l'exécution de $pythonScript." -ForegroundColor Red
    Write-Host "👉 Erreur : $_" -ForegroundColor Yellow
}
Write-Host "`nAppuyez sur une touche pour quitter..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")