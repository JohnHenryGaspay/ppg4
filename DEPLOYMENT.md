# PPG4 Deployment Guide

## Overview
This project uses Git for version control and a PowerShell deployment script to push changes to the live Ventrap server via FTP.

## Setup Instructions

### 1. Initialize Git Repository
Already done! Your repository has been initialized with:
- Repository name: `ppg4`
- User: PPG4 Development
- Email: dev@pulsepropertygroup.com

### 2. Add Files to Git
```powershell
cd C:\xampp\htdocs\ppg4
git add .
git commit -m "Initial commit: PPG4 project setup"
```

### 3. Configure Your Live Server Credentials
You'll need your Ventrap FTP credentials ready:
- FTP Host: `your-ventrap-server.com` (get from Ventrap panel)
- FTP Username: (from Ventrap control panel)
- FTP Password: (from Ventrap control panel)
- Remote Path: `/public_html` (or your installation path)

## Deployment Workflow

### Before Deploying
1. **Commit your changes to Git:**
   ```powershell
   git add .
   git commit -m "Describe your changes here"
   ```

2. **Test locally** - Make sure everything works on XAMPP before deploying

### How to Deploy

#### Option A: Dry Run (Test without uploading)
```powershell
.\Deploy-To-Live.ps1 `
  -FtpHost "your-ventrap-server.com" `
  -FtpUser "your_ftp_username" `
  -FtpPass "your_ftp_password" `
  -RemotePath "/public_html" `
  -DryRun
```

This will show you what files would be uploaded without actually uploading them.

#### Option B: Live Deployment
```powershell
.\Deploy-To-Live.ps1 `
  -FtpHost "your-ventrap-server.com" `
  -FtpUser "your_ftp_username" `
  -FtpPass "your_ftp_password" `
  -RemotePath "/public_html"
```

### Files Excluded from Deployment
The `.deployignore` file controls what gets deployed. By default, it excludes:
- `wp-config.php` (live server has its own)
- `.env` and environment files
- `vendor/` directory (install dependencies on live via SSH or manually)
- `.git/` repository files
- Log files and temporary files
- ngrok executable
- Database backups

**Important:** You must manually upload or ensure these files exist on live:
- `wp-config.php` - Configure with live database credentials
- `composer.lock` - So you can run `composer install` on live
- `.htaccess` - If you have WordPress permalinks configured

## Git Workflow

### Making Changes
```powershell
# Make changes to files...

# Check status
git status

# Stage changes
git add .

# Commit with descriptive message
git commit -m "Fix: Home page image rendering"

# Deploy to live
.\Deploy-To-Live.ps1 -FtpHost "..." -FtpUser "..." -FtpPass "..."
```

### Viewing Commit History
```powershell
git log --oneline
```

### Reverting Changes
```powershell
# Undo last commit (keep changes locally)
git reset HEAD~1

# Undo changes to a specific file
git checkout -- <filename>
```

## Deployment Log
Each deployment creates/updates `deployment.log` with timestamps and status of each file uploaded. Check this file for deployment details and troubleshooting.

## Security Tips
1. **Never commit credentials** - FTP passwords should never go in Git
2. **Use .gitignore** - It's already set up to ignore sensitive files
3. **Keep wp-config.php separate** - Live and local configs are different
4. **Test on staging** - Test changes on XAMPP before deploying to live

## Troubleshooting

### "FTP connection failed"
- Verify FTP credentials from Ventrap control panel
- Check if FTP port is 21 (default) or something else
- Ensure your IP isn't blocked by Ventrap firewall

### "Files not uploading"
- Check that remote path exists on server (usually `/public_html`)
- Verify you have write permissions on Ventrap account
- Check `deployment.log` for specific file errors

### "Site broken after deployment"
- Check live server's error logs (via Ventrap control panel)
- Verify `wp-config.php` exists on live with correct DB credentials
- Clear WordPress cache if available
- Roll back by uploading previous version of files

## Next Steps

1. **Create a GitHub repository** (optional but recommended):
   ```powershell
   # Initialize on GitHub, then:
   git remote add origin https://github.com/yourusername/ppg4.git
   git branch -M main
   git push -u origin main
   ```

2. **Set up backup strategy**:
   - Regular backups of live database via Ventrap panel
   - Keep local SQL backup updated before major changes

3. **Document your custom code**:
   - Add comments to custom theme functions
   - Update this guide with site-specific deployment notes
