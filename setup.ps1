# VentraIP Setup Script for Windows
# Run this FIRST before deploying

$ErrorActionPreference = "Continue"

$LIVE_SERVER = "43.250.142.137"
$LIVE_USER = "pulsepro"
$LIVE_PATH = "/home/pulsepro/public_html"

function Write-Header {
    param([string]$Text)
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Yellow
    Write-Host $Text -ForegroundColor Yellow
    Write-Host "========================================" -ForegroundColor Yellow
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

Write-Header "VentraIP Setup & Connection Test"

# Step 1: Check for SSH key
Write-Host "[1/6] Checking for SSH key..." -ForegroundColor Yellow
$SSHKeyPath = "$env:USERPROFILE\.ssh\id_ed25519"
$SSHKeyPathRSA = "$env:USERPROFILE\.ssh\id_rsa"

if (Test-Path $SSHKeyPath) {
    Write-Success "SSH key found (ed25519)"
} elseif (Test-Path $SSHKeyPathRSA) {
    Write-Success "SSH key found (RSA)"
} else {
    Write-Warning-Custom "SSH key not found at standard location"
    Write-Host "Make sure your SSH key is at: $SSHKeyPathRSA or $SSHKeyPath"
}

# Step 2: Test SSH connection
Write-Host ""
Write-Host "[2/6] Testing SSH connection to VentraIP..." -ForegroundColor Yellow

try {
    $TestResult = ssh -o ConnectTimeout=10 -o BatchMode=yes "$LIVE_USER@$LIVE_SERVER" "echo 'Connected'" 2>&1
    if ($TestResult -match "Connected") {
        Write-Success "SSH connection successful"
    } else {
        throw "Connection failed"
    }
} catch {
    Write-Error-Custom "SSH connection failed"
    Write-Host ""
    Write-Host "Troubleshooting:" -ForegroundColor Yellow
    Write-Host "1. Make sure you added your SSH public key to VentraIP cPanel"
    Write-Host "2. Check if SSH is enabled in cPanel → Security → SSH Access"
    Write-Host "3. Try manual connection: ssh $LIVE_USER@$LIVE_SERVER"
    Write-Host ""
    Write-Host "Your public key (copy and add to VentraIP):" -ForegroundColor Yellow
    if (Test-Path "$SSHKeyPath.pub") {
        Get-Content "$SSHKeyPath.pub"
    } elseif (Test-Path "$SSHKeyPathRSA.pub") {
        Get-Content "$SSHKeyPathRSA.pub"
    }
    exit 1
}

# Step 3: Check live server directory
Write-Host ""
Write-Host "[3/6] Checking live server directory..." -ForegroundColor Yellow
$RemoteCheck = ssh "$LIVE_USER@$LIVE_SERVER" "ls -la $LIVE_PATH 2>/dev/null && echo 'EXISTS' || echo 'NOT_FOUND'"
if ($RemoteCheck -match "EXISTS") {
    Write-Success "Directory exists: $LIVE_PATH"
} else {
    Write-Warning-Custom "Directory not found: $LIVE_PATH"
    Write-Host "Creating directory..."
    ssh "$LIVE_USER@$LIVE_SERVER" "mkdir -p $LIVE_PATH"
    Write-Success "Directory created"
}

# Step 4: Check Git
Write-Host ""
Write-Host "[4/6] Checking Git installation on server..." -ForegroundColor Yellow
$GitVersion = ssh "$LIVE_USER@$LIVE_SERVER" "git --version 2>/dev/null || echo 'NOT_INSTALLED'"
if ($GitVersion -match "git version") {
    Write-Success "Git installed: $GitVersion"
} else {
    Write-Error-Custom "Git not installed"
    Write-Host "Contact VentraIP support to install Git"
    exit 1
}

# Step 5: Check Composer
Write-Host ""
Write-Host "[5/6] Checking Composer installation..." -ForegroundColor Yellow
$ComposerCheck = ssh "$LIVE_USER@$LIVE_SERVER" "composer --version 2>/dev/null && echo 'INSTALLED' || echo 'NOT_INSTALLED'"
if ($ComposerCheck -match "INSTALLED") {
    Write-Success "Composer installed"
} else {
    Write-Warning-Custom "Composer not found. Installing..."
    
    $InstallComposer = @'
cd /home/pulsepro
curl -sS https://getcomposer.org/installer | php
mkdir -p bin
mv composer.phar bin/composer
chmod +x bin/composer
echo 'export PATH=$PATH:$HOME/bin' >> ~/.bashrc
'@
    
    ssh "$LIVE_USER@$LIVE_SERVER" $InstallComposer
    Write-Success "Composer installed"
}

# Step 6: Initialize Git repository
Write-Host ""
Write-Host "[6/6] Setting up Git repository on live server..." -ForegroundColor Yellow
Write-Host ""
$GIT_REPO = Read-Host "What is your GitHub repository URL? (e.g., https://github.com/username/ppg4.git)"

if ([string]::IsNullOrWhiteSpace($GIT_REPO)) {
    Write-Error-Custom "No repo URL provided"
    exit 1
}

$SetupGit = @"
set -e
cd $LIVE_PATH

# Check if already a git repo
if [ ! -d .git ]; then
    echo 'Initializing Git repository...'
    git init
    git remote add origin $GIT_REPO
    git config user.email 'deploy@pulsepropertygroup.com.au'
    git config user.name 'VentraIP Deploy'
else
    echo 'Git repository already exists'
    git remote set-url origin $GIT_REPO 2>/dev/null || git remote add origin $GIT_REPO
fi

# Fetch from remote
echo 'Fetching from GitHub...'
git fetch origin main

# Checkout main branch
echo 'Checking out main branch...'
git checkout main 2>/dev/null || git checkout -b main origin/main

echo '✓ Git setup complete'
"@

ssh "$LIVE_USER@$LIVE_SERVER" $SetupGit

Write-Header "SETUP COMPLETE"
Write-Success "Your deployment is ready!"
Write-Host ""
Write-Host "Next steps:"
Write-Host "  1. Verify your local changes are committed to GitHub"
Write-Host "  2. Run deployment: .\deploy-win.ps1"
Write-Host ""
Write-Host "Connection details:"
Write-Host "  Server: $LIVE_SERVER"
Write-Host "  User: $LIVE_USER"
Write-Host "  Path: $LIVE_PATH"
Write-Host ""

# Summary
Write-Host "`n=== Setup Summary ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor White
Write-Host "1. Download WordPress core to: www\wp\" -ForegroundColor White
Write-Host "2. Run 'composer install' in the project root" -ForegroundColor White
Write-Host "3. Create database 'ppg_local' in phpMyAdmin" -ForegroundColor White
Write-Host "4. Import database from: mwp_db\127.0.0.1-ppg_live.sql" -ForegroundColor White
Write-Host "5. Update URLs in database (see SETUP_INSTRUCTIONS.md)" -ForegroundColor White
Write-Host "6. Start Apache and MySQL in XAMPP" -ForegroundColor White
Write-Host "7. Visit: http://localhost/ppg4/www/" -ForegroundColor White
Write-Host ""
Write-Host "For detailed instructions, see: SETUP_INSTRUCTIONS.md" -ForegroundColor Cyan
Write-Host ""
