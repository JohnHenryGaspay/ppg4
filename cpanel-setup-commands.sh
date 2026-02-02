# VentraIP cPanel Terminal Setup Commands
# Copy and paste these commands into cPanel Terminal
# NOTE: This will REPLACE the fresh WordPress install with your custom setup

# ============================================
# STEP 1: Backup and Clear Fresh Install
# ============================================
cd /home/pulsepro
mv public_html public_html_backup_$(date +%Y%m%d)
mkdir public_html
cd public_html

# ============================================
# STEP 2: Initialize Git Repository
# ============================================
git init

# ============================================
# STEP 3: Add GitHub Remote
# ============================================
git remote add origin https://github.com/JohnHenryGaspay/ppg4.git

# ============================================
# STEP 4: Configure Git User
# ============================================
git config user.email "deploy@pulsepropertygroup.com.au"
git config user.name "VentraIP Deploy Bot"

# ============================================
# STEP 5: Fetch from GitHub
# ============================================
git fetch origin main

# ============================================
# STEP 6: Checkout Main Branch
# ============================================
git checkout main

# ============================================
# STEP 7: Set Webhook Secret
# ============================================
echo 'export GITHUB_WEBHOOK_SECRET="SHprzGxWDbT092uO7V8yXgULwtN6sEhF"' >> ~/.bashrc
source ~/.bashrc

# ============================================
# STEP 8: Create .env file with Live Database
# ============================================
cat > .env << 'ENVFILE'
WP_ENV=production

DB_NAME=pulsepro_ogiy1
DB_USER=pulsepro_ogiy1
DB_PASSWORD=F.8H2jQnJQZ4sXzLL9230
DB_HOST=localhost
DB_PREFIX=qqar_

WP_HOME=https://www.pulsepropertygroup.com.au
WP_SITEURL=https://www.pulsepropertygroup.com.au/wp

AUTH_KEY='RwRHqT84hbsIvTDVlYxHlQmGrrd4dI5yl8WxJ1sUmzJ3KqxxyEqdZ8vAkh8TPv7e'
SECURE_AUTH_KEY='W2OHBjDMraFIPsVHMsK2oEcp4dHKzUhuO8jCuqv2r8UC6SjCjSHVd6mHFLTlrqDO'
LOGGED_IN_KEY='EERLRdEoMN1xeZQzS9Qfj2THoPvyITyiMkIZG8COn6SVXu4AGBfCInoLtyg7O3AL'
NONCE_KEY='S1DEXHePBeM0bWQYqzZCKrEBr2n8t9I2HAnbraR3aTxfFeDQQGmJ4vGeV8adB1KD'
AUTH_SALT='1q50qposfQw6k6LWQcXnong9nyfzaBWmSvj4MqlfEabkLr2Zkx8mqvAE2xlYDc44'
SECURE_AUTH_SALT='bOTFUbyB3VmsRYnU7xLSOR50ky41mK8vXkM0R0gmsGSiZzwLdcW8EKXoaUqsREhr'
LOGGED_IN_SALT='h26FhMjOQ71t6jI3UZAaQjvULWjED7ZxLtld3Y00Rwm5tTs43g7iDkz4yuCRArCO'
NONCE_SALT='K6Rbki6kzvpyynZvMRZFTD7ZNjRUylz2nw60loM0zYKJPRvxesWEvI38ZwHyL39V'

WP_DEBUG=false
WP_DEBUG_DISPLAY=false
WP_DEBUG_LOG=true
ENVFILE

# ============================================
# STEP 9: Verify Setup
# ============================================
echo "==================================="
echo "Setup Complete!"
echo "==================================="
git status
git log --oneline -5
echo ""
echo "Database: pulsepro_ogiy1"
echo "Webhook Secret: SHprzGxWDbT092uO7V8yXgULwtN6sEhF"
echo "Ready for GitHub webhook deployment!"
