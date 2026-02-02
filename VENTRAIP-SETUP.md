# VentraIP Deployment Configuration

## VentraIP Hosting Details

VentraIP uses cPanel, typically with:
- SSH access via: `ssh username@your-domain.com` or `username@123.456.789.123`
- Home directory: `/home/cpanelaccount/`
- Public HTML: `/home/cpanelaccount/public_html/`
- Available: Git, Composer, WP-CLI, PHP SSH access

## Configuration Steps for VentraIP

### 1. Find Your VentraIP Details

Login to VentraIP control panel:
1. Go to **cPanel**
2. Look for **SSH/Shell Access** or **Account Information**
3. Find:
   - **cPanel Username** (e.g., `ppg12345`)
   - **Server IP or hostname** (e.g., `123.456.789.012` or `vps-12345.ventraip.com.au`)
   - **Home directory** (usually `/home/cpanelaccount/`)

### 2. Update Deployment Script

Edit `deploy.sh` and update these lines (around line 10-15):

```bash
# Configuration - UPDATE THESE VALUES FOR VENTRAIP
LIVE_SERVER="vps-12345.ventraip.com.au"              # OR your IP address
LIVE_USER="cpanel_username"                          # Your cPanel username
LIVE_PATH="/home/cpanel_username/public_html/ppg4"   # Should be in public_html
LIVE_DOMAIN="www.pulsepropertygroup.com.au"
```

### 3. Generate SSH Keys

On your local machine:

```bash
# Generate key
ssh-keygen -t ed25519 -C "your_email@example.com"

# Copy public key
cat ~/.ssh/id_ed25519.pub
```

### 4. Add SSH Key to VentraIP

**Via cPanel (Easiest):**
1. Login to cPanel → **SSH/Shell Access** or **SSH Keys**
2. Click **Manage SSH Keys**
3. **Import** your public key
4. Set as **Authorized** or **Default**

**Or via command line:**
```bash
# SSH to VentraIP
ssh cpanelaccount@vps-12345.ventraip.com.au

# Create .ssh directory if needed
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Add your public key (paste the key you copied above)
echo "ssh-ed25519 AAAA..." >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

### 5. Test SSH Connection

```bash
ssh cpanelaccount@vps-12345.ventraip.com.au "pwd"
```

Should show: `/home/cpanelaccount`

### 6. Setup Git on VentraIP Server

Connect to your VentraIP server:

```bash
ssh cpanelaccount@vps-12345.ventraip.com.au
cd public_html/ppg4
```

Initialize Git (if not already done):

```bash
git init
git remote add origin https://github.com/yourname/ppg4.git
git fetch origin main
git checkout main
```

Configure Git user:

```bash
git config user.email "deploy@pulsepropertygroup.com.au"
git config user.name "VentraIP Deploy Bot"
```

### 7. Make Deployment Script Executable

```bash
chmod +x deploy.sh
```

### 8. Test First Deployment

```bash
./deploy.sh
```

The script will:
- Check your local Git status
- Confirm deployment details
- SSH to VentraIP and pull code
- Install Composer dependencies
- Clear caches
- Verify site is online

---

## VentraIP-Specific Notes

### Composer Installation

If Composer isn't available on VentraIP, install it:

```bash
ssh cpanelaccount@vps-12345.ventraip.com.au
cd public_html/ppg4
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

### WP-CLI Installation (Optional)

For easier WordPress management:

```bash
ssh cpanelaccount@vps-12345.ventraip.com.au
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

### PHP Version

Check which PHP version VentraIP is using:

```bash
ssh cpanelaccount@vps-12345.ventraip.com.au "php -v"
```

Should be **PHP 8.0+**. If not, change it in cPanel → **MultiPHP Manager**.

### WP Rocket Cache on VentraIP

Location on server:
```
/home/cpanelaccount/public_html/ppg4/cache/wp-rocket/
/home/cpanelaccount/public_html/ppg4/wp-rocket-config/
```

The deployment script clears both automatically.

---

## Complete VentraIP Deploy Config

Here's your complete updated `deploy.sh` configuration section:

```bash
# Configuration for VentraIP - UPDATE THESE VALUES
LIVE_SERVER="vps-12345.ventraip.com.au"              # Your VentraIP hostname
LIVE_USER="ppg_user"                                  # Your cPanel username
LIVE_PATH="/home/ppg_user/public_html/ppg4"          # Full path on server
LIVE_DOMAIN="www.pulsepropertygroup.com.au"
GIT_BRANCH="main"
GIT_REPO="https://github.com/yourname/ppg4.git"
```

---

## VentraIP Deployment Checklist

- [ ] Get VentraIP server hostname/IP and cPanel username
- [ ] Generate SSH key locally (`ssh-keygen`)
- [ ] Add SSH key to VentraIP cPanel
- [ ] Test SSH connection works
- [ ] Update `deploy.sh` with VentraIP details
- [ ] SSH to server and setup Git
- [ ] Verify Composer is installed
- [ ] Verify PHP version is 8.0+
- [ ] Make `deploy.sh` executable (`chmod +x deploy.sh`)
- [ ] Test deployment with `./deploy.sh`

---

## Troubleshooting VentraIP Issues

### SSH Connection Refused
```bash
# Check if SSH is enabled in cPanel
# SSH/Shell Access → Unrestricted or Restricted Shells
# May need to use your domain instead of IP:
ssh cpanelaccount@your-domain.com
```

### Permission Denied (publickey)
```bash
# SSH key not added correctly
# Verify in cPanel: SSH Keys > Manage SSH Keys > Is it Authorized?
# Or check ~/.ssh/authorized_keys on server
ssh cpanelaccount@vps.ventraip.com.au "cat ~/.ssh/authorized_keys"
```

### Composer Not Found
```bash
# Check if installed
ssh cpanelaccount@vps.ventraip.com.au "composer --version"

# If not, install:
ssh cpanelaccount@vps.ventraip.com.au
cd public_html/ppg4
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

### Git Not Found
```bash
# VentraIP usually includes Git, but if not:
ssh cpanelaccount@vps.ventraip.com.au "git --version"

# Most VentraIP plans have it available
```

---

## Need Help?

Once you provide:
1. **VentraIP hostname/IP**
2. **cPanel username**
3. **Path where site is installed** (usually `/home/username/public_html/ppg4`)

I can fully configure the deployment script for you.
