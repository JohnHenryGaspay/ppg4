# Pulse Property Group - PowerShell Deployment Script (for Windows servers)
# Usage: .\deploy-win.ps1
# Requires: SSH access to live server via SSH or local network access

param(
    [string]$Environment = "production",
    [switch]$SkipGitCheck = $false,
    [switch]$DryRun = $false
)

$ErrorActionPreference = "Stop"

# Configuration - UPDATE THESE VALUES
$LIVE_SERVER = "your_server_ip_or_domain"
$LIVE_USER = "your_ssh_user"
$LIVE_PATH = "D:\Sites\ppg4"  # Windows path on live server
$LIVE_DOMAIN = "www.pulsepropertygroup.com.au"
$GIT_BRANCH = "main"
$LOCAL_PATH = Split-Path -Parent $MyInvocation.MyCommand.Path

# Functions
function Write-Header {
    param([string]$Text)
    Write-Host ""
    Write-Host "======================================" -ForegroundColor Yellow
    Write-Host $Text -ForegroundColor Yellow
    Write-Host "======================================" -ForegroundColor Yellow
    Write-Host ""
}

function Write-Success {
    param([string]$Text)
    Write-Host "✓ $Text" -ForegroundColor Green
}

function Write-Error-Custom {
    param([string]$Text)
    Write-Host "✗ $Text" -ForegroundColor Red
}

function Write-Warning-Custom {
    param([string]$Text)
    Write-Host "⚠ $Text" -ForegroundColor Yellow
}

# Main deployment flow
Write-Header "Pulse Property Group - Windows Deployment"

# Step 1: Git Status
if (-not $SkipGitCheck) {
    Write-Host "[1/6] Checking Git status..." -ForegroundColor Yellow
    Push-Location $LOCAL_PATH
    
    if (-not (Test-Path ".git")) {
        Write-Error-Custom "Not a Git repository"
        exit 1
    }
    
    $Status = git status --porcelain
    if ($Status) {
        Write-Warning-Custom "Uncommitted changes found:"
        Write-Host $Status
        $Confirm = Read-Host "Continue anyway? (y/N)"
        if ($Confirm -ne 'y') {
            Write-Error-Custom "Deployment cancelled"
            exit 1
        }
    }
    
    $Branch = git rev-parse --abbrev-ref HEAD
    $Commit = (git rev-parse --short HEAD)
    Write-Success "Branch: $Branch ($Commit)"
    
    Pop-Location
}

# Step 2: Confirmation
Write-Host "[2/6] Deployment Configuration:" -ForegroundColor Yellow
Write-Host "  Server: $LIVE_SERVER"
Write-Host "  Path: $LIVE_PATH"
Write-Host "  Branch: $GIT_BRANCH"
Write-Host "  Environment: $Environment"

if ($DryRun) {
    Write-Warning-Custom "DRY RUN MODE - No changes will be made"
}

Write-Host ""
$Confirm = Read-Host "Proceed with deployment? (y/N)"
if ($Confirm -ne 'y') {
    Write-Error-Custom "Deployment cancelled"
    exit 1
}

# Step 3: Remote Deployment via SSH
Write-Host "[3/6] Deploying to live server..." -ForegroundColor Yellow

$DeployCommands = @"
Set-Location '$LIVE_PATH'
Write-Host 'Pulling from Git ($GIT_BRANCH)...'
git pull origin $GIT_BRANCH

Write-Host 'Installing Composer dependencies...'
composer install --no-dev --optimize-autoloader

Write-Host 'Clearing WP Rocket cache...'
Remove-Item 'wp-rocket-config\*' -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem -Path 'cache\wp-rocket' -Recurse -ErrorAction SilentlyContinue | Remove-Item -Recurse -Force

Write-Host 'Success!'
"@

if ($DryRun) {
    Write-Warning-Custom "DRY RUN: SSH commands would be:"
    Write-Host $DeployCommands
} else {
    # For Windows SSH, use SSH.exe or PuTTY
    ssh "$LIVE_USER@$LIVE_SERVER" powershell.exe -NoProfile -Command $DeployCommands
    Write-Success "Code deployed to live server"
}

# Step 4: Clear caches
Write-Host "[4/6] Clearing caches..." -ForegroundColor Yellow
if (-not $DryRun) {
    Remove-Item "$LOCAL_PATH\cache\wp-rocket\*" -Recurse -Force -ErrorAction SilentlyContinue
    Remove-Item "$LOCAL_PATH\app\debug.log" -Force -ErrorAction SilentlyContinue
}
Write-Success "Caches cleared"

# Step 5: Verify
Write-Host "[5/6] Verifying deployment..." -ForegroundColor Yellow
if (-not $DryRun) {
    try {
        $Response = Invoke-WebRequest -Uri "https://$LIVE_DOMAIN" -UseBasicParsing -TimeoutSec 10
        Write-Success "Site responding: HTTP $($Response.StatusCode)"
    } catch {
        Write-Warning-Custom "Could not verify site: $_"
    }
}

# Step 6: Summary
Write-Host "[6/6] Deployment Summary" -ForegroundColor Yellow
Write-Success "Deployment complete!"
Write-Host ""
Write-Host "Next steps:"
Write-Host "  1. Test live site: https://$LIVE_DOMAIN"
Write-Host "  2. Check browser console for errors"
Write-Host "  3. Monitor server logs for issues"
Write-Host ""

Write-Header "DEPLOYMENT COMPLETE"
