-- Pulse Property Group - Database URL Update Script
-- Run this AFTER importing the database to update URLs for local development

USE ppg_local;

-- Step 1: Update core site URLs
UPDATE auc_options 
SET option_value = 'http://localhost/ppg4/www' 
WHERE option_name IN ('siteurl', 'home');

-- Step 2: Update post content URLs (for media and internal links)
UPDATE auc_posts 
SET post_content = REPLACE(post_content, 'https://www.pulsepropertygroup.com.au', 'http://localhost/ppg4/www')
WHERE post_content LIKE '%pulsepropertygroup.com.au%';

-- Step 3: Update post excerpt URLs
UPDATE auc_posts 
SET post_excerpt = REPLACE(post_excerpt, 'https://www.pulsepropertygroup.com.au', 'http://localhost/ppg4/www')
WHERE post_excerpt LIKE '%pulsepropertygroup.com.au%';

-- Step 4: Update post meta (custom fields, ACF fields, etc.)
UPDATE auc_postmeta 
SET meta_value = REPLACE(meta_value, 'https://www.pulsepropertygroup.com.au', 'http://localhost/ppg4/www')
WHERE meta_value LIKE '%pulsepropertygroup.com.au%';

-- Step 5: Update option values (theme settings, widgets, etc.)
UPDATE auc_options 
SET option_value = REPLACE(option_value, 'https://www.pulsepropertygroup.com.au', 'http://localhost/ppg4/www')
WHERE option_value LIKE '%pulsepropertygroup.com.au%'
AND option_name NOT IN ('siteurl', 'home');

-- Step 6: Update comment content URLs
UPDATE auc_comments 
SET comment_content = REPLACE(comment_content, 'https://www.pulsepropertygroup.com.au', 'http://localhost/ppg4/www')
WHERE comment_content LIKE '%pulsepropertygroup.com.au%';

-- Step 7: Update user meta
UPDATE auc_usermeta 
SET meta_value = REPLACE(meta_value, 'https://www.pulsepropertygroup.com.au', 'http://localhost/ppg4/www')
WHERE meta_value LIKE '%pulsepropertygroup.com.au%';

-- Step 8: Update term meta (if exists)
UPDATE auc_termmeta 
SET meta_value = REPLACE(meta_value, 'https://www.pulsepropertygroup.com.au', 'http://localhost/ppg4/www')
WHERE meta_value LIKE '%pulsepropertygroup.com.au%';

-- Verification queries
SELECT 'Site URL:', option_value FROM auc_options WHERE option_name = 'siteurl';
SELECT 'Home URL:', option_value FROM auc_options WHERE option_name = 'home';

-- Check for any remaining old URLs
SELECT COUNT(*) as 'Remaining old URLs in posts' 
FROM auc_posts 
WHERE post_content LIKE '%pulsepropertygroup.com.au%';

SELECT COUNT(*) as 'Remaining old URLs in postmeta' 
FROM auc_postmeta 
WHERE meta_value LIKE '%pulsepropertygroup.com.au%';

-- Display admin users (to help with login)
SELECT ID, user_login, user_email, user_registered 
FROM auc_users 
WHERE ID IN (
    SELECT user_id FROM auc_usermeta 
    WHERE meta_key = 'auc_capabilities' 
    AND meta_value LIKE '%administrator%'
)
LIMIT 5;

-- Success message
SELECT '✓ Database URLs updated successfully!' as 'Status';
SELECT 'Next: Visit http://localhost/ppg4/www/ to access your site' as 'Next Step';
