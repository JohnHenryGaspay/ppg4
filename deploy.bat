@echo off
REM Quick deployment shortcut for PPG4
REM To use: deploy.bat <ftp-host> <ftp-user> <ftp-password> [remote-path]
REM Example: deploy.bat ftp.ventrap.com username password /public_html

if "%1"=="" (
    echo.
    echo PPG4 Deployment Script
    echo Usage: deploy.bat ^<ftp-host^> ^<ftp-user^> ^<ftp-password^> [remote-path]
    echo.
    echo Example:
    echo   deploy.bat ftp.ventrap.com myusername mypassword /public_html
    echo.
    echo Optional: Add -dryrun flag at the end to test without uploading
    echo.
    exit /b 1
)

setlocal enabledelayedexpansion
set FtpHost=%1
set FtpUser=%2
set FtpPass=%3
set RemotePath=%4
if "%RemotePath%"=="" set RemotePath=/public_html
set DryRunFlag=%5

echo.
echo Starting PPG4 Deployment...
echo.

if "%DryRunFlag%"=="-dryrun" (
    powershell -ExecutionPolicy Bypass -File ".\Deploy-To-Live.ps1" ^
        -FtpHost "%FtpHost%" ^
        -FtpUser "%FtpUser%" ^
        -FtpPass "%FtpPass%" ^
        -RemotePath "%RemotePath%" ^
        -DryRun
) else (
    powershell -ExecutionPolicy Bypass -File ".\Deploy-To-Live.ps1" ^
        -FtpHost "%FtpHost%" ^
        -FtpUser "%FtpUser%" ^
        -FtpPass "%FtpPass%" ^
        -RemotePath "%RemotePath%"
)

endlocal
