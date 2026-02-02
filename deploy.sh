#!/bin/bash

###############################################################################
# Pulse Property Group - Automated Deployment Script
# Usage: ./deploy.sh
# Requires: SSH access, Git, Composer on live server
###############################################################################

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration - VENTRAIP PRODUCTION
LIVE_SERVER="43.250.142.137"
LIVE_USER="pulsepro"
LIVE_PATH="/home/pulsepro/public_html"  # Path on live server
LIVE_DOMAIN="www.pulsepropertygroup.com.au"
GIT_BRANCH="main"
GIT_REPO="https://github.com/JohnHenryGaspay/ppg4.git"

# Local paths
LOCAL_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}Pulse Property Group - Deployment${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""

# Step 1: Verify local Git status
echo -e "${YELLOW}[1/7] Checking local Git status...${NC}"
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

# Step 2: Get latest commits
echo -e "${YELLOW}[2/7] Fetching latest from Git...${NC}"
git -C "$LOCAL_PATH" fetch origin
echo -e "${GREEN}✓ Git fetch complete${NC}"

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

# Step 4: Deploy to live server
echo -e "${YELLOW}[3/7] Deploying code to live server...${NC}"
ssh "$LIVE_USER@$LIVE_SERVER" << 'LIVESCRIPT'
set -e

LIVE_PATH="$1"
GIT_BRANCH="$2"
LIVE_DOMAIN="$3"

echo "Navigating to: $LIVE_PATH"
cd "$LIVE_PATH"

# Pull latest changes
echo "Pulling from Git ($GIT_BRANCH)..."
git pull origin "$GIT_BRANCH"

# Install/update Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Clear WP Rocket cache
echo "Clearing WP Rocket cache..."
rm -rf wp-rocket-config/*
find . -path ./cache/wp-rocket -type d -exec rm -rf {} + 2>/dev/null || true

# Clear Transients (optional - via WP CLI if available)
if command -v wp &> /dev/null; then
    echo "Clearing WordPress transients..."
    wp transient delete-all --allow-root || true
fi

echo "✓ Deployment complete"
LIVESCRIPT "$LIVE_PATH" "$GIT_BRANCH" "$LIVE_DOMAIN"

echo -e "${GREEN}✓ Code deployed to live server${NC}"

# Step 5: Clear local caches
echo -e "${YELLOW}[4/7] Clearing local caches...${NC}"
rm -rf "$LOCAL_PATH/cache/wp-rocket/"* 2>/dev/null || true
rm -rf "$LOCAL_PATH/app/debug.log" 2>/dev/null || true
echo -e "${GREEN}✓ Local caches cleared${NC}"

# Step 6: Database sync (optional)
echo -e "${YELLOW}[5/7] Database sync${NC}"
echo "To sync database from live to local:"
echo "  Option 1: Use WP Migrate DB Pro GUI in WordPress admin"
echo "  Option 2: Use command (setup separately):"
echo "    wp migratedb pull $LIVE_DOMAIN"
echo ""

# Step 7: Summary
echo -e "${YELLOW}[6/7] Verifying deployment...${NC}"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "https://$LIVE_DOMAIN")
if [ "$RESPONSE" == "200" ] || [ "$RESPONSE" == "301" ] || [ "$RESPONSE" == "302" ]; then
    echo -e "${GREEN}✓ Site responding correctly (HTTP $RESPONSE)${NC}"
else
    echo -e "${YELLOW}⚠ Site returned HTTP $RESPONSE (check if correct)${NC}"
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✓ DEPLOYMENT SUCCESSFUL${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Next steps:"
echo "  1. Test live site: https://$LIVE_DOMAIN"
echo "  2. Check browser console for errors"
echo "  3. Test key pages and functionality"
echo ""
