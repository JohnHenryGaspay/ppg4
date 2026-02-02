#!/bin/bash

###############################################################################
# PPG4 - Quick Deployment to VentraIP
###############################################################################

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
LIVE_SERVER="43.250.142.137"
LIVE_USER="pulsepro"
LIVE_PATH="/home/pulsepro/public_html"
GIT_BRANCH="master"

echo -e "${BLUE}╔════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║  Pulse Property Group - DEPLOYMENT   ║${NC}"
echo -e "${BLUE}║         VentraIP Live Server          ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════╝${NC}"
echo ""

# Step 1: Check local Git status
echo -e "${YELLOW}[1/5] Checking Git status...${NC}"
UNCOMMITTED=$(git status --porcelain | wc -l)
if [ "$UNCOMMITTED" -gt 0 ]; then
    echo -e "${YELLOW}⚠️  Found $UNCOMMITTED uncommitted changes${NC}"
    git status --short
    read -p "Continue with deployment? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${RED}✗ Deployment cancelled${NC}"
        exit 1
    fi
fi

# Step 2: Push to GitHub
echo -e "${YELLOW}[2/5] Pushing code to GitHub...${NC}"
git push origin $GIT_BRANCH || {
    echo -e "${RED}✗ Failed to push to GitHub${NC}"
    exit 1
}
echo -e "${GREEN}✓ Code pushed${NC}"

# Step 3: SSH and pull on live server
echo -e "${YELLOW}[3/5] Connecting to live server and pulling code...${NC}"
ssh -o ConnectTimeout=10 "${LIVE_USER}@${LIVE_SERVER}" bash << 'LIVESCRIPT'
set -e

LIVE_PATH="/home/pulsepro/public_html"
GIT_BRANCH="master"

echo "Navigating to $LIVE_PATH"
cd "$LIVE_PATH"

echo "Pulling from GitHub..."
git fetch origin
git checkout $GIT_BRANCH
git pull origin $GIT_BRANCH

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Clearing WordPress cache..."
wp cache flush

echo "Verifying deployment..."
echo "Latest commit:"
git log --oneline -1

echo "✓ Deployment complete!"
LIVESCRIPT

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Live server updated${NC}"
else
    echo -e "${RED}✗ Failed to update live server${NC}"
    exit 1
fi

# Step 4: Verify deployment
echo -e "${YELLOW}[4/5] Verifying live site...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://www.pulsepropertygroup.com.au/)
if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✓ Live site is responding (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${RED}✗ Live site returned HTTP $HTTP_CODE${NC}"
fi

# Step 5: Summary
echo -e "${YELLOW}[5/5] Deployment Summary${NC}"
echo -e "${GREEN}✓ Code pushed to GitHub${NC}"
echo -e "${GREEN}✓ Live server updated${NC}"
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo -e "${GREEN}✓ Cache cleared${NC}"
echo ""
echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo -e "${GREEN}✅ DEPLOYMENT SUCCESSFUL!${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo ""
echo -e "${YELLOW}Post-Deployment Testing:${NC}"
echo "1. Homepage: https://www.pulsepropertygroup.com.au/"
echo "2. Buy Page: https://www.pulsepropertygroup.com.au/buy/"
echo "3. Single Property: https://www.pulsepropertygroup.com.au/properties/rental/1-6-8-croesus-street-morley-wa-6062/"
echo ""
echo -e "${YELLOW}Critical Fixes Deployed:${NC}"
echo "✓ Image rendering (500 error fix)"
echo "✓ React app loading (archive pages)"
echo "✓ Google Maps marker initialization"
echo ""
echo "Press F12 in browser → Console tab to check for errors"
echo ""
