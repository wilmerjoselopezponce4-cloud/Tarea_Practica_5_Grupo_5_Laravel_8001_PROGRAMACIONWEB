@echo off
setlocal EnableExtensions
cd /d "%~dp0"

rem Usar PHP de Laragon antes que otras instalaciones locales.
set "PHPCMD="
for /d %%P in ("%~dp0..\..\bin\php\php-*") do (
    if exist "%%~fP\php.exe" set "PHPCMD=%%~fP\php.exe"
)
if not defined PHPCMD (
    for %%P in (php.exe) do set "PHPCMD=%%~$PATH:P"
)
if not defined PHPCMD (
    echo No se encontro PHP. Abre la terminal de Laragon.
    pause
    exit /b 1
)
if not exist "vendor\autoload.php" (
    echo Faltan dependencias. Ejecuta: composer install
    pause
    exit /b 1
)
if not exist ".env" (
    echo Falta .env. Ejecuta: copy .env.example .env
    echo Despues: php artisan key:generate
    pause
    exit /b 1
)

echo Iniciando Luma Biblioteca en http://127.0.0.1:8001
"%PHPCMD%" artisan serve --host=127.0.0.1 --port=8001
pause
