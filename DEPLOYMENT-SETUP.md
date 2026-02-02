# Deployment Setup Guide

## Overview

This guide covers setting up automated Git-based deployment for Pulse Property Group from local development to live production server.

## Prerequisites

### On Your Local Machine
- Git installed and configured
- SSH client (built-in on macOS/Linux, Git Bash on Windows)
- Ability to generate SSH keys

### On Live Server
- Git installed
- Composer installed
- SSH access enabled (for Linux) or SSH.exe access (for Windows)
- Direct file system access or SSH
- PHP 8.0+ with required extensions
- WordPress with ACF Pro installed

---

## Step 1: Generate SSH Keys (One-Time Setup)

### On Your Local Machine

```bash
# Generate SSH key (if you don't have one)
ssh-keygen -t ed25519 -C "your_email@example.com"
# Accept default location (~/.ssh/id_ed25519)
# Set a strong passphrase
```

Get your public key:
```bash
# macOS/Linux
cat ~/.ssh/id_ed25519.pub

# Windows (Git Bash)
type %USERPROFILE%\.ssh\id_ed25519.pub
```

### On Live Server (via SSH or Direct Access)

Add your public key to the live server:
```bash
# Connect to server
ssh user@your_server_ip

# Create .ssh directory if needed
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Add your public key
echo "YOUR_PUBLIC_KEY_HERE" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

**Test SSH connection:**
```bash
ssh user@your_server_ip "pwd"
```

---

## Step 2: Configure Git on Live Server

### Connect to Live Server

```bash
ssh user@your_server_ip
cd /path/to/ppg4
```

### Initialize Git (if not already)

```bash
# Check if Git is initialized
git status

# If not, initialize:
git init
git remote add origin https://github.com/yourname/ppg4.git
git fetch origin main
git checkout main
```

### Configure Git User (for commit tracking)

```bash
git config user.email "deploy@pulsepropertygroup.com.au"
git config user.name "Pulse Deployment Bot"
```

---

## Step 3: Update Deployment Scripts

Edit the configuration section in **deploy.sh** or **deploy-win.ps1**:

### For Linux/macOS:

```bash
# Edit deploy.sh
nano deploy.sh

# Update these variables:
LIVE_SERVER="your.server.com"                    # Your domain or IP
LIVE_USER="cpanel_username"                      # SSH user
LIVE_PATH="/home/cpanelusername/public_html/ppg4"  # Full path on server
LIVE_DOMAIN="www.pulsepropertygroup.com.au"
GIT_REPO="https://github.com/yourname/ppg4.git"
```

### For Windows PowerShell:

```powershell
# Edit deploy-win.ps1
$LIVE_SERVER = "your.server.com"
$LIVE_USER = "windows_user"
$LIVE_PATH = "D:\Sites\ppg4"
$LIVE_DOMAIN = "www.pulsepropertygroup.com.au"
```

---

## Step 4: Set File Permissions (Linux/macOS)

Make scripts executable:

```bash
chmod +x deploy.sh
chmod +x Deploy-To-Live.ps1  # if using existing script
```

---

## Step 5: Test Deployment

### Dry Run (Recommended First Time)

For PowerShell:
```powershell
.\deploy-win.ps1 -DryRun
```

For Bash:
```bash
# Edit deploy.sh and add 'exit 0' after confirmation step to test
./deploy.sh
```

### Full Deployment

```bash
./deploy.sh
```

You'll be prompted to confirm. Review the details and type 'y' to proceed.

---

## Step 6: Automate Deployment (Optional)

### Linux/macOS - Using Cron

```bash
# Open crontab editor
crontab -e

# Add this line to deploy every day at 2 AM
0 2 * * * cd /home/user/ppg4 && ./deploy.sh >> deploy.log 2>&1
```

### Windows - Using Task Scheduler

**Create New Task:**
1. Open Task Scheduler
2. Create Task > General
3. Set Name: "PPG Deployment"
4. Trigger: Daily at 2:00 AM
5. Action: `powershell.exe`
6. Arguments: `-NoProfile -ExecutionPolicy Bypass -File "C:\xampp\htdocs\ppg4\deploy-win.ps1"`

---

## Step 7: Monitor Deployments

### Check Deployment Logs

```bash
# SSH to server and check logs
ssh user@your_server_ip
tail -f /path/to/ppg4/deploy.log

# Or locally after deployment
cat deploy.log
```

### Common Deployment Tasks

**Clear WP Rocket Cache Remotely:**
```bash
ssh user@your_server_ip "rm -rf /path/to/ppg4/cache/wp-rocket/*"
```

**Pull Latest Database to Local:**
```bash
wp migratedb pull www.pulsepropertygroup.com.au
```

**Check Git Status on Live:**
```bash
ssh user@your_server_ip "cd /path/to/ppg4 && git status"
```

---

## Deployment Workflow

### Before Each Deployment

1. **Commit your changes locally:**
   ```bash
   git add .
   git commit -m "Feature: Update footer with social links"
   git push origin main
   ```

2. **Verify Git log:**
   ```bash
   git log --oneline -5
   ```

3. **Check for uncommitted files:**
   ```bash
   git status
   ```

### During Deployment

1. **Run deployment script:**
   ```bash
   ./deploy.sh
   ```

2. **Review deployment details** (server, branch, commit)

3. **Confirm deployment** (type 'y')

4. **Wait for completion** (typically 30-60 seconds)

### After Deployment

1. **Test live site:** https://www.pulsepropertygroup.com.au
2. **Check console for errors:** F12 → Console tab
3. **Test key pages:** About, Development, Properties, Contact
4. **Monitor debug.log:**
   ```bash
   ssh user@your_server_ip "tail -f /path/to/ppg4/app/debug.log"
   ```

---

## Troubleshooting

### SSH Connection Fails
```bash
# Test SSH connection
ssh -v user@your_server_ip
# Add -v for verbose output to diagnose issues

# Check if SSH key permissions are correct
ls -la ~/.ssh/
# Should show: -rw------- (600) for id_ed25519
```

### Composer Install Fails
```bash
# SSH to server and install manually
ssh user@your_server_ip
cd /path/to/ppg4
composer install --no-dev --optimize-autoloader --no-interaction
```

### Site Shows Blank Page After Deployment
```bash
# Check error logs
ssh user@your_server_ip "tail -50 /path/to/ppg4/app/debug.log"

# Check PHP errors (if not using debug.log)
ssh user@your_server_ip "tail -50 /var/log/php-errors.log"
```

### Cache Issues
```bash
# Clear all caches from script location
rm -rf cache/wp-rocket/*
rm -rf app/debug.log
# Then SSH to server and clear there too
```

---

## Rolling Back a Deployment

If something goes wrong:

```bash
# SSH to live server
ssh user@your_server_ip
cd /path/to/ppg4

# Check recent commits
git log --oneline -10

# Revert to previous version
git reset --hard HEAD~1
git push -f origin main

# Or checkout specific commit
git checkout abc1234
```

**Note:** Only force-push if you're confident or have backups!

---

## Database Sync Strategy

For the initial launch, sync database from local to live:

**Option 1: WP Migrate DB Pro (GUI)**
- WordPress Admin > Tools > Migrate DB
- Select "Pull" to bring live database to local (safer)
- Or "Push" to send local to live

**Option 2: Command Line (WP-CLI)**
```bash
# From local machine
wp migratedb push www.pulsepropertygroup.com.au \
  --search-replace="http://localhost/ppg4=https://www.pulsepropertygroup.com.au"
```

**Option 3: Manual Export/Import**
```bash
# Local export
wp db export ~/ppg4-prod.sql

# Transfer to live server
scp ~/ppg4-prod.sql user@server:/tmp/

# SSH and import
ssh user@server
wp db import /tmp/ppg4-prod.sql
```

---

## Next Steps

1. **Update deploy script** with your server details
2. **Test SSH connection** to verify access
3. **Run one manual deployment** with `-DryRun` flag
4. **Monitor first live deployment** closely
5. **Set up automated deployments** (optional)

---

## Support Commands

Keep these handy:

```bash
# Local development
git status              # Check what's changed
git log --oneline -5   # See recent commits
git diff               # See exact changes

# SSH to server
ssh user@server_ip

# On live server
git status             # Check Git status
git log --oneline -5   # See deployed commits
tail -50 app/debug.log # Check errors
pwd                    # Confirm current path
ls -la                 # List files with permissions

# WordPress debug
wp option get home     # Check WordPress URL
wp user list           # Check admin user
wp transient delete-all # Clear transients
```

---

**Questions?** Run `./deploy.sh` and it will guide you through the process with confirmations at each step.
