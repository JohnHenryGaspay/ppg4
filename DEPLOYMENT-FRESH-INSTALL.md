# Fresh Install Deployment - Quick Guide

## Your Situation
✅ Fresh WordPress on live server (safe to replace)
✅ Custom Bedrock setup locally
✅ Need to deploy everything to VentraIP

---

## 📦 Quick Setup (3 Steps)

### Step 1: Run in cPanel Terminal

```bash
# Backup fresh install and setup Git
cd /home/pulsepro
mv public_html public_html_backup
mkdir public_html && cd public_html

git init
git remote add origin https://github.com/JohnHenryGaspay/ppg4.git
git config user.email "deploy@pulsepropertygroup.com.au"
git config user.name "Deploy Bot"
git fetch origin main
git checkout main

composer install --no-dev --optimize-autoloader

echo 'export GITHUB_WEBHOOK_SECRET="SHprzGxWDbT092uO7V8yXgULwtN6sEhF"' >> ~/.bashrc
source ~/.bashrc

chmod -R 755 app/themes app/plugins
chmod -R 777 app/uploads
```

### Step 2: Upload Database

**Export local database first:**
```bash
wp db export local-database.sql
```

**Import to live (cPanel → phpMyAdmin):**
1. Select database
2. Import → Choose `local-database.sql`
3. Go

**Update URLs:**
```bash
cd /home/pulsepro/public_html
wp search-replace 'http://localhost/ppg4' 'https://www.pulsepropertygroup.com.au'
```

### Step 3: Update wp-config.php

Edit live server wp-config.php with database credentials:
```php
define('DB_NAME', 'your_cpanel_db_name');
define('DB_USER', 'your_cpanel_db_user');
define('DB_PASSWORD', 'your_db_password');
define('WP_ENV', 'production');
```

---

## 🔗 GitHub Webhook (Auto-Deploy)

After initial setup, create webhook:
- URL: `https://www.pulsepropertygroup.com.au/deploy.php`
- Secret: `SHprzGxWDbT092uO7V8yXgULwtN6sEhF`

Then every `git push` auto-deploys! 🚀

---

## ✅ Done!

**Test:** https://www.pulsepropertygroup.com.au

**Daily workflow:**
```bash
git add .
git commit -m "Changes"
git push origin main
# Site updates automatically!
```
