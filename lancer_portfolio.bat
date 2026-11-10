@echo off
rem Lance le portfolio et les trois sites PHP avec UN seul serveur local,
rem exactement comme en ligne. Double-cliquer suffit.
rem Le portfolio est a la racine ; les sites sont dans leurs sous-dossiers.
setlocal enabledelayedexpansion
cd /d "%~dp0"

set PHP=C:\php\php.exe
set PORT=8080
if not exist "%PHP%" (
    echo PHP introuvable : %PHP%
    echo Installez PHP ou corrigez le chemin dans ce fichier.
    pause
    exit /b 1
)

rem Ferme tout serveur deja bloque sur le port (ancien lancement, plantage).
for /f "tokens=5" %%p in ('netstat -ano ^| findstr /r /c:"TCP .*:%PORT% .*LISTENING"') do (
    echo Fermeture de l'ancien serveur (PID %%p) sur le port %PORT%
    taskkill /F /PID %%p >nul 2>&1
)

echo Demarrage du serveur sur http://127.0.0.1:%PORT%
start "Portfolio (PHP port %PORT%)" /min "%PHP%" -S 127.0.0.1:%PORT% -t "%~dp0."

timeout /t 2 /nobreak >nul
start "" "http://127.0.0.1:%PORT%/"
echo.
echo Portfolio lance. Fermez la fenetre PHP pour tout arreter.
exit /b 0
