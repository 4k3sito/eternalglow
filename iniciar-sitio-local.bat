@echo off
setlocal
title Eternal Glow - sitio local (cierra esta ventana para detener el servidor web)

set "BIN=D:\eternalglow-local\bin"
set "MB=%BIN%\mariadb\bin"
set "PHP=%BIN%\php\php.exe"
set "ENV=%~dp0local-env"
set "WEB=%~dp0u355783479.eternalglow-com-mx.20260922005336\domains\eternalglow.com.mx\public_html"
set "MYINI=D:\eternalglow-local\mariadb-data\my.ini"
set "URL=http://localhost:8080/"

"%MB%\mariadb-admin.exe" --host=127.0.0.1 --port=3307 -uroot -proot ping >nul 2>&1
if not errorlevel 1 goto dbok
echo Iniciando MariaDB...
powershell -NoProfile -Command "Start-Process -FilePath '%MB%\mariadbd.exe' -ArgumentList '--defaults-file=\"%MYINI%\"' -WindowStyle Hidden"
for /L %%i in (1,1,30) do (
  "%MB%\mariadb-admin.exe" --host=127.0.0.1 --port=3307 -uroot -proot ping >nul 2>&1 && goto dbok
  ping -n 2 127.0.0.1 >nul
)
echo ERROR: MariaDB no arranco. Revisa D:\eternalglow-local\mariadb-error.log
pause
exit /b 1

:dbok
echo MariaDB listo (puerto 3307).

powershell -NoProfile -Command "if (Get-NetTCPConnection -LocalPort 8080 -State Listen -ErrorAction SilentlyContinue) { exit 1 }"
if errorlevel 1 (
  echo El servidor web ya esta corriendo en %URL%
  start "" "%URL%"
  exit /b 0
)

echo Servidor web en %URL%  ^(cierra esta ventana o pulsa Ctrl+C para detenerlo^)
start "" "%URL%"
"%PHP%" -c "%ENV%\php.ini" -S localhost:8080 -t "%WEB%" "%ENV%\router.php"
