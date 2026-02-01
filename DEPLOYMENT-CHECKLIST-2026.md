# 🚀 Pre-Deployment Checklist - February 2026

## Critical Fixes Applied (MUST VERIFY BEFORE DEPLOYMENT)

### ✅ Image Rendering Fix (Resolved 500 Error)
- [x] Fixed `Timber\Image::src()` null return type violation
- [x] File: `app/themes/pulse-property/src/JuiceBox/Core/Image.php`
- **Action Required:** None - fix is in place

### ✅ React App Loading Fix (Property Archive Pages)
- [x] Fixed script enqueuing timing issue
- [x] File: `app/themes/pulse-property/functions.php`
- [x] Moved `jb_property_app` script from page template to WordPress hook
- [x] Removed problematic `polyfills` dependency
- **Action Required:** None - fix is in place

### ✅ Google Maps Marker Initialization Fix (Single Property Pages)
- [x] Fixed `LatLng` constructor bug in source file
- [x] File: `themes/pulse-property/js/src/single-property.js` (line 119)
- [x] Fixed compiled bundle
- [x] File: `themes/pulse-property/dist/js/bundle.js` (index 25058)
- **Action Required:** None - both source and compiled versions fixed

---

## Pre-Deployment Quality Assurance

### 🔍 Testing Checklist

#### Homepage & Navigation
- [ ] Homepage loads without errors
- [ ] Header transparent on scroll (desktop)
- [ ] Mobile hamburger menu works
- [ ] Footer social links present and correct

#### Property Listing Pages (Archive Pages)
- [ ] `/properties/` loads
- [ ] `/buy/` displays rental properties with React map
- [ ] `/rent/` displays rental properties with React map
- [ ] `/sold/` displays sold properties with React map
- [ ] `/commercial/` displays commercial properties with React map
- [ ] Search/filter autocomplete works
- [ ] Property listing grid renders correctly

#### Single Property Pages
- [ ] Single property page loads without 500 error
- [ ] Google Maps displays with marker at correct location
- [ ] Property gallery carousel works
- [ ] Property information displays correctly
- [ ] Expandable content section toggles

#### Content Pages
- [ ] About page loads and displays all sections
- [ ] Contact page loads with form
- [ ] Blog/news pages load (if applicable)
- [ ] All custom post types render

#### Contact & Forms
- [ ] Contact form submits without errors
- [ ] Gravity Forms (if used) load correctly
- [ ] reCAPTCHA validation works (if enabled)

#### SEO & Meta
- [ ] Page titles display correctly
- [ ] Meta descriptions present
- [ ] Open Graph tags present
- [ ] Canonical tags correct

---

## Browser Console Check

**Action:** Open each page and press `F12` → Console tab

- [ ] No JavaScript errors
- [ ] No 404 errors for assets (JS, CSS, images)
- [ ] No "This page didn't load Google Maps correctly" warning
- [ ] No deprecation warnings

**Expected warnings (OK to see):**
- Font warnings (performance related - non-critical)

---

## Site Performance Check

- [ ] Homepage loads in < 3 seconds
- [ ] Property pages load in < 4 seconds
- [ ] Images load correctly and display
- [ ] CSS styling applied correctly
- [ ] Videos/media play (if applicable)

---

## Database & Content Check

- [ ] All properties display with correct information
- [ ] Images/thumbnails show for all properties
- [ ] Property details (price, bed/bath, address) accurate
- [ ] No missing content blocks
- [ ] ACF custom fields display correctly

---

## Security Pre-Check

- [ ] `wp-config.php` has LIVE database credentials
- [ ] `.env` file exists with production settings
- [ ] `WP_DEBUG` set to `false`
- [ ] `WP_DEBUG_LOG` disabled
- [ ] Database backups created (local and server-side)
- [ ] SSL certificate active and valid
- [ ] HTTPS redirects configured

---

## File Deployment Checklist

### ✅ Files to Deploy
- [x] `themes/pulse-property/` - all files
- [x] `app/themes/pulse-property/src/` - PHP classes
- [x] `app/themes/pulse-property/functions.php` - function registrations
- [x] `themes/pulse-property/js/src/single-property.js` - JavaScript source
- [x] `themes/pulse-property/dist/js/bundle.js` - **COMPILED BUNDLE** ⚠️ **INCLUDES GOOGLE MAPS FIX**
- [x] `vendor/` - Composer dependencies (run `composer install --no-dev` on server)
- [x] All plugin files (ACF Pro, etc.)

### ⚠️ Files NOT to Deploy (already on server)
- [ ] `wp-config.php` - use server's version
- [ ] `.env` - use server's version
- [ ] `app/uploads/` - preserve existing media library
- [ ] Any custom database records

### 📋 Optional Files to Deploy
- [ ] `.htaccess` - if permalink structure changed
- [ ] `robots.txt` - SEO configuration
- [ ] `sitemap.xml` - if auto-generated

---

## Deployment Steps

### Step 1: Final Git Commit
```powershell
# Commit all fixes
git add .
git commit -m "Deploy: Fix Image rendering, React app loading, and Google Maps marker initialization"
git push origin main
```

### Step 2: Database Backup
```powershell
# Export local database (if needed)
wp db export local-database-$(Get-Date -Format "yyyyMMdd-HHmmss").sql
```

### Step 3: Deploy Code
```powershell
# Using deployment script
.\deploy-win.ps1
# OR
.\deploy.sh
```

### Step 4: Server-Side Composer Install
```bash
# SSH to server
ssh pulsepro@43.250.142.137

# Navigate to site root
cd /home/pulsepro/public_html

# Install dependencies (no dev dependencies on production)
composer install --no-dev --optimize-autoloader

# Clear cache
wp cache flush
```

### Step 5: Verify Deployment
```bash
# Check file permissions
ls -la themes/pulse-property/
ls -la app/themes/pulse-property/src/

# Check for errors
wp debug-log list --max=10
```

---

## Post-Deployment Verification

### 🔍 Critical Pages to Test (Live Site)

**Homepage:**
```
https://www.pulsepropertygroup.com.au/
```
- [ ] Page loads
- [ ] Header/footer correct
- [ ] Images display
- [ ] Search component works

**Buy Properties Archive:**
```
https://www.pulsepropertygroup.com.au/buy/
```
- [ ] React map initializes
- [ ] Property list displays
- [ ] No JavaScript errors

**Single Property (Rental):**
```
https://www.pulsepropertygroup.com.au/properties/rental/1-6-8-croesus-street-morley-wa-6062/
```
- [ ] ✅ **Google Maps displays correctly**
- [ ] ✅ **Marker positioned at property location**
- [ ] Gallery carousel works
- [ ] Property info displays
- [ ] No console errors

**Single Property (Buy/Sold/Commercial):**
- [ ] Test at least one from each type
- [ ] Maps display correctly
- [ ] All details accurate

---

## Rollback Plan (If Issues)

### Quick Rollback
```bash
# SSH to server
ssh pulsepro@43.250.142.137

# Show recent commits
git log --oneline -5

# Revert to previous version
git revert HEAD --no-edit
```

### Database Rollback
```bash
# Import previous backup via phpMyAdmin or WP-CLI
wp db import ~/backup-database.sql
```

---

## Contact & Support

### Emergency Contacts
- **Server Admin:** VentraIP Support (help@ventraip.com.au)
- **Domain:** GoDaddy/VentraIP
- **GitHub:** https://github.com/JohnHenryGaspay/ppg4

### Monitoring After Deployment
- [ ] Monitor error logs for 24 hours
- [ ] Check Google Maps functionality reports
- [ ] Monitor website performance/speed
- [ ] Check for user-reported issues

---

## Sign-Off

**Developer:** _____________________  
**Date:** _____________________  
**Notes:** _______________________________________________________________

---

## Key Files Modified in This Session

1. **Image Class Fix**
   - Path: `app/themes/pulse-property/src/JuiceBox/Core/Image.php`
   - Change: Added `src($size = 'full'): string` override

2. **Script Enqueuing Fix**
   - Path: `app/themes/pulse-property/functions.php`
   - Change: Moved `jb_property_app` script to `wp_enqueue_scripts` hook

3. **Single Property Map Fix (Source)**
   - Path: `themes/pulse-property/js/src/single-property.js` (Line 119)
   - Change: `new google.maps.LatLng(position)` → `new google.maps.LatLng(lat, lng)`

4. **Single Property Map Fix (Compiled)**
   - Path: `themes/pulse-property/dist/js/bundle.js` (Index 25058)
   - Change: `new google.maps.LatLng(t)` → `new google.maps.LatLng(t.lat, t.lng)`

---

**Last Updated:** February 1, 2026
**Status:** Ready for Deployment ✅
