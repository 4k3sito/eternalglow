@echo off
setlocal
set "MB=D:\eternalglow-local\bin\mariadb\bin"

echo Deteniendo servidor web (puerto 8080)...
powershell -NoProfile -Command "Get-NetTCPConnection -LocalPort 8080 -State Listen -ErrorAction SilentlyContinue | ForEach-Object { Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue }"

echo Deteniendo MariaDB (puerto 3307)...
"%MB%\mariadb-admin.exe" --host=127.0.0.1 --port=3307 -uroot -proot shutdown >nul 2>&1

echo Listo.
ping -n 3 127.0.0.1 >nul
