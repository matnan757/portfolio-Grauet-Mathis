@echo off
rem Lance tous les sites PHP du portfolio en parallele puis ouvre le portfolio.
rem Double-cliquer sur ce fichier suffit. Chaque site a sa propre fenetre reduite.
rem Avant de demarrer un site, tout serveur deja bloque sur son port est ferme,
rem pour repartir proprement meme apres un plantage.
setlocal enabledelayedexpansion
cd /d "%~dp0"

set PHP=C:\php\php.exe
if not exist "%PHP%" (
    echo PHP introuvable : %PHP%
    echo Installez PHP ou corrigez le chemin dans ce fichier.
    pause
    exit /b 1
)

call :demarrer 8000 "documentation des projets\stage 1"          "Site du stage"
call :demarrer 8003 "documentation des projets\projets clavardage" "Site Clavardon"
call :demarrer 8002 "documentation des projets\projet neptune"    "Site Neptune"

timeout /t 2 /nobreak >nul
start "" "page du portfolio\acceuil.html"
echo.
echo Sites lances. Fermez une fenetre PHP pour arreter le site correspondant.
exit /b 0

:demarrer
rem %1 = port, %2 = dossier du site, %3 = nom affiche
set "PORT=%~1"
set "DOSSIER=%~2"
set "NOM=%~3"

rem Verifie que le dossier existe
if not exist "%DOSSIER%\index.php" (
    echo [%NOM%] ATTENTION : dossier introuvable -^> %DOSSIER%
    exit /b 0
)

rem Ferme tout processus deja a l'ecoute sur ce port (serveur fantome, ancien lancement)
for /f "tokens=5" %%p in ('netstat -ano ^| findstr /r /c:"TCP .*:%PORT% .*LISTENING"') do (
    echo [%NOM%] fermeture de l'ancien serveur (PID %%p) sur le port %PORT%
    taskkill /F /PID %%p >nul 2>&1
)

echo [%NOM%] demarrage sur http://127.0.0.1:%PORT%
start "%NOM% (PHP port %PORT%)" /min "%PHP%" -S 127.0.0.1:%PORT% -t "%DOSSIER%"
exit /b 0
