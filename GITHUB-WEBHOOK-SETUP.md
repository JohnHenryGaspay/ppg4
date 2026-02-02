# Automated Deployment via GitHub Webhooks

Since SSH isn't available on VentraIP, we'll use **GitHub Webhooks** for automatic deployment. This is actually better - every time you push to GitHub, your site automatically updates!

## How It Works

1. You push code to GitHub
2. GitHub sends a webhook to your live server
3. Server automatically pulls latest code
4. Site updates instantly

## Setup (One-Time)

### Step 1: Upload Deploy Script to Live Server

Upload **[deploy.php](deploy.php)** to your live server root:
```
/home/pulsepro/public_html/deploy.php
```

**Via FTP:**
- Open FTP client
- Connect to 43.250.142.137 (port 21)
- Upload `deploy.php` to `/public_html/`

### Step 2: Make Git Repo on Server

Contact VentraIP support and ask them to:
1. Initialize Git repository at `/home/pulsepro/public_html`
2. Set remote: `https://github.com/YOUR_USERNAME/ppg4.git`
3. Set Git credentials for HTTPS access

**Or if you have cPanel Terminal access:**
```bash
cd /home/pulsepro/public_html
git init
git remote add origin https://github.com/YOUR_USERNAME/ppg4.git
git config user.email "deploy@pulsepropertygroup.com.au"
git config user.name "Deploy Bot"
git fetch origin main
git checkout main
```

### Step 3: Create GitHub Webhook

1. Go to your GitHub repository: https://github.com/YOUR_USERNAME/ppg4
2. Click **Settings** → **Webhooks** → **Add webhook**
3. Fill in:
   - **Payload URL:** `https://www.pulsepropertygroup.com.au/deploy.php`
   - **Content type:** `application/json`
   - **Secret:** Create a secure random string (copy this!)
   - **Events:** Select **Just the push event**
   - **Active:** ✓ Check this box
4. Click **Add webhook**

### Step 4: Set Server Environment Variable

On your live server, set the webhook secret so it matches GitHub:

**Via cPanel:**
- Look for **Environment Variables** or **PHP Configuration**
- Add: `GITHUB_WEBHOOK_SECRET=YOUR_SECRET_STRING`

**Or in `.htaccess`:**
```apache
SetEnv GITHUB_WEBHOOK_SECRET "your-secret-string"
```

**Or via command line (if you have terminal):**
```bash
echo 'GITHUB_WEBHOOK_SECRET="your-secret-string"' >> ~/.bashrc
```

---

## Daily Workflow

Now deployment is automatic! Just:

1. **Make changes locally**
2. **Commit & push to GitHub:**
   ```bash
   git add .
   git commit -m "Updated footer with social links"
   git push origin main
   ```
3. **Wait 5-10 seconds** ⏳
4. **Live site automatically updates!** 🚀

---

## Verify It's Working

### Test Push
```bash
git add README.md
git commit -m "Test deployment webhook"
git push origin main
```

### Check Server Logs
On live server (via Terminal/SSH):
```bash
tail -20 /home/pulsepro/public_html/deploy.log
```

Should show:
```
[2026-02-01 14:30:45] Deployment triggered by GitHub
[2026-02-01 14:30:46] Fetching from GitHub...
[2026-02-01 14:30:47] Installing Composer...
[2026-02-01 14:30:52] Deployment complete
```

### Check GitHub Webhook
1. Go to GitHub repository → **Settings** → **Webhooks**
2. Click your webhook
3. Scroll to **Recent Deliveries**
4. Should see green checkmarks ✓

---

## First Time Manual Setup

If webhook fails, you can manually sync the first time:

**On live server (via cPanel Terminal or SSH):**
```bash
cd /home/pulsepro/public_html
git fetch origin main
git reset --hard origin/main
composer install --no-dev --optimize-autoloader
rm -rf cache/wp-rocket/*
```

Then subsequent pushes will auto-deploy via webhook.

---

## Advantages of This Approach

✅ **No SSH needed** - Works with FTP-only hosting
✅ **Automatic** - Deploys on every push
✅ **Fast** - Updates in seconds
✅ **Reliable** - GitHub confirms delivery
✅ **Logged** - See deployment history
✅ **Secure** - Webhook signature verification

---

## Troubleshooting

### Webhook shows red X
1. Check that `deploy.php` is at `/home/pulsepro/public_html/deploy.php`
2. Verify it's readable (permissions 644)
3. Check webhook secret matches

### Deployment doesn't happen
1. Check GitHub webhook delivery: Settings → Webhooks → Recent Deliveries
2. Look at response code - should be 200
3. Check server logs: `tail /home/pulsepro/public_html/deploy.log`

### Permission denied errors
1. Make sure web server (www-data) can write to directory
2. Contact VentraIP to fix permissions

---

## Configuration

**Update GitHub repo URL in [deploy.php](deploy.php):**

Find this line and update:
```php
git reset --hard origin/main 2>&1 &&
```

Ensure it matches your actual GitHub repo.

---

## Next Steps

1. ✅ Upload [deploy.php](deploy.php) to live server via FTP
2. ✅ Contact VentraIP to initialize Git repo
3. ✅ Create GitHub webhook (Settings → Webhooks)
4. ✅ Test with a git push
5. ✅ Check deploy.log for confirmation

Let me know when you've uploaded the files and I'll help with the GitHub webhook setup!
