@echo off
REM Set the title of the command prompt window
title Starting Development Servers

REM Change directory to your project folder
cd /d "D:\RunProgram\wamp64\www\Sarana\siemreap_gear"

REM Announce what we're doing
echo.
echo =======================================================
echo.
echo   Starting NPM Dev Server and Artisan Queue Worker...
echo.
echo =======================================================
echo.

@REM REM Start npm run dev in a new window
@REM echo Starting: npm run dev
@REM start "NPM Dev Server" cmd /k "npm run dev"


REM Start php php artisan schedule:work in another new window
echo Starting: php artisan schedule:work
start "Artisan Suchedule Worker" cmd /k "php artisan schedule:work"

REM Start php artisan queue:work in another new window
echo Starting: php artisan queue:work
start "Artisan Queue Worker" cmd /k "php artisan queue:work"

echo.
echo All processes have been started in new windows.
echo This window will now close.
timeout /t 3 /nobreak >nul
exit