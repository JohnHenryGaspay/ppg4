# 🚀 Go Live Checklist - Pulse Property Group

## ✅ Pre-Deployment

- [x] Database credentials confirmed: `pulsepro_ogiy1`
- [x] Live site is fresh install (safe to replace)
- [x] GitHub repo ready: https://github.com/JohnHenryGaspay/ppg4.git
- [x] Webhook secret generated: `SHprzGxWDbT092uO7V8yXgULwtN6sEhF`
- [x] Upload folder excluded from Git
- [ ] Local database exported
- [ ] deploy.php uploaded via FTP

---

## 📦 Step 1: Export Local Database

```bash
# Run in PowerShell (from C:\xampp\htdocs\ppg4)
wp db export local-database-$(Get-Date -Format "yyyyMMdd").sql
```

**File saved:** `local-database-YYYYMMDD.sql`

---

## 📤 Step 2: Upload Files via FTP

**Connect to FTP:**
- Host: `43.250.142.137`
- Port: `21`
- User: `pulsepro`
- Password: (your FTP password)

**Upload:**
1. `deploy.php` → `/public_html/deploy.php`
2. `local-database-YYYYMMDD.sql` → `/home/pulsepro/` (for import)

---

## 💻 Step 3: Run cPanel Terminal Commands

**Login:** cPanel → Terminal

**Copy/paste entire script:**

```bash
# Backup fresh install
cd /home/pulsepro
mv public_html public_html_backup_$(date +%Y%m%d)
mkdir public_html
cd public_html

# Initialize Git
git init
git remote add origin https://github.com/JohnHenryGaspay/ppg4.git
git config user.email "deploy@pulsepropertygroup.com.au"
git config user.name "VentraIP Deploy Bot"

# Pull code
git fetch origin main
git checkout main

# Install Composer
composer install --no-dev --optimize-autoloader

# Set webhook secret
echo 'export GITHUB_WEBHOOK_SECRET="SHprzGxWDbT092uO7V8yXgULwtN6sEhF"' >> ~/.bashrc
source ~/.bashrc

# Create .env file
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
ENVFILE

# Set permissions
chmod -R 755 app/themes app/plugins
chmod -R 777 app/uploads
chmod 644 deploy.php

echo "✓ Code deployment complete!"
```

---

## 🗄️ Step 4: Import Database

**Via cPanel phpMyAdmin:**
1. cPanel → phpMyAdmin
2. Select database: `pulsepro_ogiy1`
3. Click **Import** tab
4. Choose file: `local-database-YYYYMMDD.sql`
5. Click **Go**
6. Wait for success message

**Or via WP-CLI (in Terminal):**
```bash
cd /home/pulsepro/public_html
wp db import ~/local-database-YYYYMMDD.sql
```

---

## 🔗 Step 5: Update Database URLs

**Run in cPanel Terminal:**

```bash
cd /home/pulsepro/public_html

# Replace localhost URLs with live domain
wp search-replace 'http://localhost/ppg4' 'https://www.pulsepropertygroup.com.au' --all-tables

# If you used different local URL:
wp search-replace 'http://ppg4.local' 'https://www.pulsepropertygroup.com.au' --all-tables
```

---

## 📤 Step 6: Upload Images/Uploads Folder

**Via FTP:**
- From: `C:\xampp\htdocs\ppg4\app\uploads\`
- To: `/home/pulsepro/public_html/app/uploads/`

Upload all contents (images, files, etc.)

---

## 🎣 Step 7: Create GitHub Webhook

1. Go to: https://github.com/JohnHenryGaspay/ppg4/settings/hooks
2. Click **Add webhook**
3. Settings:
   - **Payload URL:** `https://www.pulsepropertygroup.com.au/deploy.php`
   - **Content type:** `application/json`
   - **Secret:** `SHprzGxWDbT092uO7V8yXgULwtN6sEhF`
   - **SSL verification:** Enable
   - **Events:** Just the push event ✓
   - **Active:** ✓
4. Click **Add webhook**

---

## ✅ Step 8: Test Live Site

### Manual Check:
- [ ] Visit: https://www.pulsepropertygroup.com.au
- [ ] Homepage loads correctly
- [ ] About page displays
- [ ] Footer shows social links
- [ ] Properties/developments visible
- [ ] Contact page works
- [ ] Images load correctly
- [ ] No PHP errors

### Check Logs:
```bash
# In cPanel Terminal
tail -50 /home/pulsepro/public_html/app/debug.log
```

---

## 🧪 Step 9: Test Webhook Deployment

**From your local machine:**

```powershell
# Make small test change
echo "# Deployment test" >> README.md
git add README.md
git commit -m "Test: Webhook deployment"
git push origin main
```

**Wait 10 seconds**, then check GitHub webhook:
- Go to: https://github.com/JohnHenryGaspay/ppg4/settings/hooks
- Click your webhook
- Check **Recent Deliveries**
- Should show green ✓ with 200 response

**Verify on server:**
```bash
# In cPanel Terminal
tail -20 /home/pulsepro/public_html/deploy.log
```

---

## 🎉 Step 10: Go Live!

**Final checks:**
- [ ] SSL certificate active (https)
- [ ] All pages accessible
- [ ] Search functionality works
- [ ] Contact forms working
- [ ] ACF fields displaying
- [ ] Menus rendering correctly
- [ ] Webhook deployment tested
- [ ] Backup created

**Daily workflow now:**
```bash
# Make changes locally
# Test on localhost
git add .
git commit -m "Your changes"
git push origin main
# Live site updates automatically! 🚀
```

---

## 🆘 Troubleshooting

### White screen / 500 error
```bash
tail -50 /home/pulsepro/public_html/app/debug.log
```

### Database connection error
- Check `.env` file has correct credentials
- Verify database `pulsepro_ogiy1` exists

### Images not loading
- Re-upload `app/uploads/` folder via FTP
- Check permissions: `chmod -R 777 app/uploads`

### Webhook fails
- Check `deploy.php` is in root: `/home/pulsepro/public_html/deploy.php`
- Verify secret matches in GitHub
- Check Recent Deliveries in GitHub webhook settings

---

## 📞 Support

**VentraIP:** https://ventraip.com.au/support/
**GitHub Repo:** https://github.com/JohnHenryGaspay/ppg4

---

**Ready? Start with Step 1! 🚀**
