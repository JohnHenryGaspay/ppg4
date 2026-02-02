#!/bin/bash

###############################################################################
# VentraIP Initial Setup & Connection Test
# Run this FIRST before deploying
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

LIVE_SERVER="43.250.142.137"
LIVE_USER="pulsepro"
LIVE_PATH="/home/pulsepro/public_html"

echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}VentraIP Setup & Connection Test${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""

# Step 1: Check SSH key
echo -e "${YELLOW}[1/6] Checking for SSH key...${NC}"
if [ ! -f ~/.ssh/id_ed25519 ] && [ ! -f ~/.ssh/id_rsa ]; then
    echo -e "${YELLOW}⚠ No SSH key found. Generating one...${NC}"
    ssh-keygen -t ed25519 -C "deploy@pulsepropertygroup.com.au"
    echo -e "${GREEN}✓ SSH key generated${NC}"
    echo ""
    echo -e "${YELLOW}ACTION REQUIRED:${NC}"
    echo "Copy this public key to VentraIP:"
    echo ""
    cat ~/.ssh/id_ed25519.pub
    echo ""
    echo "Add it to VentraIP cPanel → SSH Access → Manage SSH Keys → Import Key"
    echo ""
    read -p "Press Enter after adding the key to VentraIP..."
else
    echo -e "${GREEN}✓ SSH key exists${NC}"
fi

# Step 2: Test SSH connection
echo ""
echo -e "${YELLOW}[2/6] Testing SSH connection to VentraIP...${NC}"
if ssh -o ConnectTimeout=10 -o BatchMode=yes "$LIVE_USER@$LIVE_SERVER" "echo 'Connected'" 2>/dev/null; then
    echo -e "${GREEN}✓ SSH connection successful${NC}"
else
    echo -e "${RED}✗ SSH connection failed${NC}"
    echo ""
    echo "Troubleshooting:"
    echo "1. Make sure you added your SSH public key to VentraIP cPanel"
    echo "2. Check if SSH is enabled in cPanel → Security → SSH Access"
    echo "3. Try manual connection: ssh $LIVE_USER@$LIVE_SERVER"
    echo ""
    echo "Your public key (copy and add to VentraIP):"
    if [ -f ~/.ssh/id_ed25519.pub ]; then
        cat ~/.ssh/id_ed25519.pub
    elif [ -f ~/.ssh/id_rsa.pub ]; then
        cat ~/.ssh/id_rsa.pub
    fi
    exit 1
fi

# Step 3: Check live server path
echo ""
echo -e "${YELLOW}[3/6] Checking live server directory...${NC}"
REMOTE_CHECK=$(ssh "$LIVE_USER@$LIVE_SERVER" "ls -la $LIVE_PATH 2>/dev/null && echo 'EXISTS' || echo 'NOT_FOUND'")
if echo "$REMOTE_CHECK" | grep -q "EXISTS"; then
    echo -e "${GREEN}✓ Directory exists: $LIVE_PATH${NC}"
else
    echo -e "${YELLOW}⚠ Directory not found: $LIVE_PATH${NC}"
    echo "Creating directory..."
    ssh "$LIVE_USER@$LIVE_SERVER" "mkdir -p $LIVE_PATH"
    echo -e "${GREEN}✓ Directory created${NC}"
fi

# Step 4: Check Git on server
echo ""
echo -e "${YELLOW}[4/6] Checking Git installation on server...${NC}"
GIT_VERSION=$(ssh "$LIVE_USER@$LIVE_SERVER" "git --version 2>/dev/null || echo 'NOT_INSTALLED'")
if echo "$GIT_VERSION" | grep -q "git version"; then
    echo -e "${GREEN}✓ Git installed: $GIT_VERSION${NC}"
else
    echo -e "${RED}✗ Git not installed${NC}"
    echo "Contact VentraIP support to install Git, or install via SSH if you have permissions"
    exit 1
fi

# Step 5: Check Composer
echo ""
echo -e "${YELLOW}[5/6] Checking Composer installation...${NC}"
COMPOSER_CHECK=$(ssh "$LIVE_USER@$LIVE_SERVER" "composer --version 2>/dev/null && echo 'INSTALLED' || echo 'NOT_INSTALLED'")
if echo "$COMPOSER_CHECK" | grep -q "INSTALLED"; then
    echo -e "${GREEN}✓ Composer installed${NC}"
else
    echo -e "${YELLOW}⚠ Composer not found. Installing...${NC}"
    ssh "$LIVE_USER@$LIVE_SERVER" << 'INSTALL_COMPOSER'
cd /home/pulsepro
curl -sS https://getcomposer.org/installer | php
mkdir -p bin
mv composer.phar bin/composer
chmod +x bin/composer
echo 'export PATH=$PATH:$HOME/bin' >> ~/.bashrc
INSTALL_COMPOSER
    echo -e "${GREEN}✓ Composer installed${NC}"
fi

# Step 6: Initialize Git repository
echo ""
echo -e "${YELLOW}[6/6] Setting up Git repository on live server...${NC}"
echo ""
echo "What is your GitHub repository URL?"
echo "Format: https://github.com/username/ppg4.git"
read -p "GitHub repo URL: " GIT_REPO

if [ -z "$GIT_REPO" ]; then
    echo -e "${RED}✗ No repo URL provided${NC}"
    exit 1
fi

ssh "$LIVE_USER@$LIVE_SERVER" << SETUPGIT
set -e
cd $LIVE_PATH

# Check if already a git repo
if [ ! -d .git ]; then
    echo "Initializing Git repository..."
    git init
    git remote add origin $GIT_REPO
    git config user.email "deploy@pulsepropertygroup.com.au"
    git config user.name "VentraIP Deploy"
else
    echo "Git repository already exists"
    git remote set-url origin $GIT_REPO 2>/dev/null || git remote add origin $GIT_REPO
fi

# Fetch from remote
echo "Fetching from GitHub..."
git fetch origin main

# Checkout main branch
echo "Checking out main branch..."
git checkout main 2>/dev/null || git checkout -b main origin/main

echo "✓ Git setup complete"
SETUPGIT

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✓ SETUP COMPLETE${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Your deployment is ready!"
echo ""
echo "Next steps:"
echo "  1. Verify your local changes are committed to GitHub"
echo "  2. Run deployment: ./deploy.sh"
echo ""
echo "Connection details:"
echo "  Server: $LIVE_SERVER"
echo "  User: $LIVE_USER"
echo "  Path: $LIVE_PATH"
echo ""
