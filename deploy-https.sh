#!/bin/bash

###############################################################################
# Pulse Property Group - Git HTTPS Deployment Script
# Uses GitHub HTTPS (no SSH key needed)
# Usage: ./deploy-https.sh
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration - UPDATE THESE
LIVE_SERVER="43.250.142.137"
LIVE_USER="pulsepro"
LIVE_PATH="/home/pulsepro/public_html"
LIVE_DOMAIN="www.pulsepropertygroup.com.au"
GIT_REPO="https://github.com/JohnHenryGaspay/ppg4.git"
GIT_BRANCH="main"

# GitHub credentials (set via environment or prompt)
GITHUB_USER=""
GITHUB_TOKEN=""

LOCAL_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}Pulse Property Group - HTTPS Deployment${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""

# Step 1: Check local Git status
echo -e "${YELLOW}[1/6] Checking local Git status...${NC}"
if ! git -C "$LOCAL_PATH" status > /dev/null 2>&1; then
    echo -e "${RED}✗ Not a Git repository${NC}"
    exit 1
fi

UNCOMMITTED=$(git -C "$LOCAL_PATH" status --porcelain | wc -l)
if [ "$UNCOMMITTED" -gt 0 ]; then
    echo -e "${YELLOW}⚠ WARNING: You have uncommitted changes:${NC}"
    git -C "$LOCAL_PATH" status --short
    read -p "Continue anyway? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${RED}✗ Deployment cancelled${NC}"
        exit 1
    fi
fi

CURRENT_BRANCH=$(git -C "$LOCAL_PATH" rev-parse --abbrev-ref HEAD)
LATEST_COMMIT=$(git -C "$LOCAL_PATH" rev-parse --short HEAD)
echo -e "${GREEN}✓ Current branch: $CURRENT_BRANCH (${LATEST_COMMIT})${NC}"

# Step 2: Push to GitHub
echo -e "${YELLOW}[2/6] Pushing to GitHub...${NC}"
git -C "$LOCAL_PATH" push origin "$CURRENT_BRANCH"
echo -e "${GREEN}✓ Code pushed to GitHub${NC}"

# Step 3: Confirm deployment
echo ""
echo -e "${YELLOW}Deployment Details:${NC}"
echo "  Server: $LIVE_SERVER"
echo "  Path: $LIVE_PATH"
echo "  Branch: $CURRENT_BRANCH"
echo "  Commit: $LATEST_COMMIT"
echo ""
read -p "Proceed with deployment? (y/N) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}✗ Deployment cancelled${NC}"
    exit 1
fi

# Step 4: Deploy via FTP or manual curl command
echo -e "${YELLOW}[3/6] Deploying to live server...${NC}"

# Create deployment script
DEPLOY_SCRIPT="/tmp/deploy-ppg.sh"
cat > "$DEPLOY_SCRIPT" << 'DEPLOYCODE'
#!/bin/bash
set -e

LIVE_PATH="$1"
GIT_REPO="$2"
GIT_BRANCH="$3"
GITHUB_USER="$4"
GITHUB_TOKEN="$5"

echo "Navigating to: $LIVE_PATH"
cd "$LIVE_PATH"

# Configure Git credentials
git config --global credential.helper store
echo "https://$GITHUB_USER:$GITHUB_TOKEN@github.com" >> ~/.git-credentials

# Initialize or update Git repo
if [ ! -d .git ]; then
    echo "Cloning repository..."
    git clone -b "$GIT_BRANCH" "$GIT_REPO" .
else
    echo "Pulling latest changes from $GIT_BRANCH..."
    git fetch origin
    git checkout "$GIT_BRANCH"
    git pull origin "$GIT_BRANCH"
fi

# Install/update Composer
if command -v composer &> /dev/null; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Clear caches
echo "Clearing caches..."
rm -rf cache/wp-rocket/* 2>/dev/null || true
rm -rf wp-rocket-config/* 2>/dev/null || true
rm -f app/debug.log 2>/dev/null || true

echo "✓ Deployment complete"
DEPLOYCODE

echo -e "${YELLOW}Since SSH isn't available, manual steps:${NC}"
echo ""
echo "1. SSH to server (or contact VentraIP support):"
echo "   ssh pulsepro@$LIVE_SERVER"
echo ""
echo "2. Run these commands:"
echo ""
echo "   cd $LIVE_PATH"
echo "   git init"
echo "   git remote add origin $GIT_REPO"
echo "   git config user.email 'deploy@pulsepropertygroup.com.au'"
echo "   git config user.name 'Deploy Bot'"
echo "   git fetch origin $GIT_BRANCH"
echo "   git checkout $GIT_BRANCH"
echo "   git pull origin $GIT_BRANCH"
echo "   composer install --no-dev --optimize-autoloader"
echo ""
echo "   OR if Git is already set up:"
echo "   cd $LIVE_PATH && git pull origin $GIT_BRANCH && composer install --no-dev --optimize-autoloader"
echo ""

# Step 5: Summary
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✓ CODE PUSHED TO GITHUB${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Next steps:"
echo "1. Contact VentraIP support to enable SSH or Terminal access"
echo "2. Or manually run Git commands on server via Terminal/SSH"
echo "3. Test live site: https://$LIVE_DOMAIN"
echo ""
