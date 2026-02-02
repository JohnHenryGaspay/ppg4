# VentraIP Deployment - Quick Reference

## Your Configuration

```
Server IP:       43.250.142.137
Username:        pulsepro
Live Path:       /home/pulsepro/public_html
Domain:          www.pulsepropertygroup.com.au
Git Branch:      main
```

## Initial Setup (One-Time)

### Windows PowerShell:
```powershell
.\setup.ps1
```

### Git Bash (Windows) or Linux/Mac:
```bash
chmod +x setup.sh
./setup.sh
```

This will:
1. ✓ Generate SSH key (if needed)
2. ✓ Test connection to VentraIP
3. ✓ Check server directory
4. ✓ Install Git & Composer on server
5. ✓ Initialize Git repository
6. ✓ Confirm ready for deployment

---

## Daily Deployment

### Windows PowerShell:
```powershell
.\deploy-win.ps1
```

### Git Bash (Windows) or Linux/Mac:
```bash
./deploy.sh
```

---

## Pre-Deployment Checklist

Before running deployment:

- [ ] Commit all local changes to Git
- [ ] Push to GitHub: `git push origin main`
- [ ] Test locally that site works
- [ ] Check for errors in debug.log

```bash
# Check what will be deployed
git status
git log --oneline -5
```

---

## Deployment Process

1. Script checks for uncommitted changes
2. Confirms deployment details with you
3. SSH to VentraIP server
4. Pulls latest code from GitHub
5. Installs Composer dependencies
6. Clears WP Rocket cache
7. Verifies site is online

**Takes 30-60 seconds**

---

## After Deployment

**Test these pages:**
- Homepage: https://www.pulsepropertygroup.com.au/
- About: https://www.pulsepropertygroup.com.au/about/
- Properties: https://www.pulsepropertygroup.com.au/properties/
- Contact: https://www.pulsepropertygroup.com.au/contact/

**Check browser console:** Press F12 → Console tab (look for errors)

---

## Common Commands

### SSH to Live Server
```bash
ssh pulsepro@43.250.142.137
```

### Check Live Server Status
```bash
ssh pulsepro@43.250.142.137 "cd /home/pulsepro/public_html && git status"
```

### Clear Cache Manually
```bash
ssh pulsepro@43.250.142.137 "rm -rf /home/pulsepro/public_html/cache/wp-rocket/*"
```

### View Live Server Logs
```bash
ssh pulsepro@43.250.142.137 "tail -50 /home/pulsepro/public_html/app/debug.log"
```

### Check Live Git Commits
```bash
ssh pulsepro@43.250.142.137 "cd /home/pulsepro/public_html && git log --oneline -10"
```

---

## Troubleshooting

### SSH Connection Issues

**Problem:** "Connection refused" or "Permission denied"

**Solution:**
```bash
# Display your public key
cat ~/.ssh/id_ed25519.pub  # Linux/Mac/Git Bash
type %USERPROFILE%\.ssh\id_ed25519.pub  # Windows CMD

# Add to VentraIP cPanel → SSH Access → Manage Keys
```

### Site Shows Blank Page

**Check error logs:**
```bash
ssh pulsepro@43.250.142.137 "tail -100 /home/pulsepro/public_html/app/debug.log"
```

### Deployment Failed

**Manual pull on server:**
```bash
ssh pulsepro@43.250.142.137
cd /home/pulsepro/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
```

### Cache Not Clearing

**Manual cache clear:**
```bash
ssh pulsepro@43.250.142.137 "rm -rf /home/pulsepro/public_html/cache/wp-rocket/*"
ssh pulsepro@43.250.142.137 "rm -rf /home/pulsepro/public_html/wp-rocket-config/*"
```

---

## Emergency Rollback

If deployment breaks the site:

```bash
# SSH to server
ssh pulsepro@43.250.142.137
cd /home/pulsepro/public_html

# View recent commits
git log --oneline -10

# Rollback to previous commit (replace abc1234 with actual commit hash)
git reset --hard abc1234

# Clear cache
rm -rf cache/wp-rocket/*
```

---

## Database Sync

**Pull live database to local:**
```bash
# Via WP-CLI (if installed)
wp migratedb pull www.pulsepropertygroup.com.au

# Or via WordPress admin
# Tools → Migrate DB → Pull
```

**Push local database to live:**
```bash
# Via WP-CLI (CAREFUL!)
wp migratedb push www.pulsepropertygroup.com.au

# Or via WordPress admin
# Tools → Migrate DB → Push
```

---

## File Locations

**Local (Development):**
- Theme: `C:\xampp\htdocs\ppg4\app\themes\pulse-property\`
- Debug log: `C:\xampp\htdocs\ppg4\app\debug.log`
- Scripts: `C:\xampp\htdocs\ppg4\*.sh`, `*.ps1`

**Live (VentraIP):**
- Theme: `/home/pulsepro/public_html/app/themes/pulse-property/`
- Debug log: `/home/pulsepro/public_html/app/debug.log`
- Cache: `/home/pulsepro/public_html/cache/wp-rocket/`

---

## Support Contacts

**VentraIP Support:**
- Website: https://ventraip.com.au/support/
- Phone: 1300 895 995
- Email: support@ventraip.com.au

**GitHub Repository:**
- Update in deploy.sh line 18
- Format: `https://github.com/username/ppg4.git`

---

## Quick Workflow

1. **Make changes locally** → Test on XAMPP
2. **Commit to Git:**
   ```bash
   git add .
   git commit -m "Description of changes"
   git push origin main
   ```
3. **Deploy:**
   ```bash
   ./deploy.sh  # or .\deploy-win.ps1
   ```
4. **Test live site** → Check key pages
5. **Monitor logs** for errors

---

**Need help?** Run `.\setup.ps1` to verify configuration or test connection.
