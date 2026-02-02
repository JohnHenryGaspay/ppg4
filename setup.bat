@echo off
REM Pulse Property Group - Setup Helper
REM This batch file runs the PowerShell setup script

echo ====================================
echo Pulse Property Group - Setup Helper
echo ====================================
echo.

REM Check if running as administrator
net session >nul 2>&1
if %errorLevel% == 0 (
    echo Running as Administrator
) else (
    echo NOTE: Not running as administrator
    echo Some operations may require elevated permissions
)

echo.
echo Running PowerShell setup script...
echo.

REM Run the PowerShell script
powershell.exe -ExecutionPolicy Bypass -File "%~dp0setup.ps1"

echo.
echo ====================================
echo Setup script completed!
echo.
echo For detailed instructions, see:
echo   SETUP_INSTRUCTIONS.md
echo.
echo Press any key to exit...
pause >nul
