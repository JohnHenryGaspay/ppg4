#!/usr/bin/env pwsh
# Quick deployment script for Pulse Property Group
# Commits local changes, pushes to GitHub, and provides instructions for live server

Write-Host "`n=== Pulse Property Group - Quick Deploy ===" -ForegroundColor Cyan

# Check if we're in the right directory
if (!(Test-Path "wp-config.php")) {
    Write-Host "Error: Not in project root directory" -ForegroundColor Red
    exit 1
}

# Get commit message
$commitMessage = Read-Host "`nEnter commit message (or press Enter for default)"
if ([string]::IsNullOrWhiteSpace($commitMessage)) {
    $commitMessage = "Update: $(Get-Date -Format 'yyyy-MM-dd HH:mm')"
}

Write-Host "`n1. Checking Git status..." -ForegroundColor Yellow
git status --short

Write-Host "`n2. Adding all changes..." -ForegroundColor Yellow
git add .

Write-Host "`n3. Committing changes..." -ForegroundColor Yellow
git commit -m "$commitMessage"

Write-Host "`n4. Pushing to GitHub..." -ForegroundColor Yellow
git push origin master

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n✓ Successfully pushed to GitHub!" -ForegroundColor Green
    
    Write-Host "`n=== Next Steps for Live Server ===" -ForegroundColor Cyan
    Write-Host "1. Go to cPanel Terminal: https://cp.ventraip.com.au/" -ForegroundColor White
    Write-Host "2. Run these commands:" -ForegroundColor White
    Write-Host "   cd /home/pulsepro/public_html" -ForegroundColor Gray
    Write-Host "   git pull origin master" -ForegroundColor Gray
    Write-Host "`n✓ Deployment complete!" -ForegroundColor Green
} else {
    Write-Host "`n✗ Push failed. Check errors above." -ForegroundColor Red
    exit 1
}
