# Email Verification Signature Error Troubleshooting

## Problem
Email verification links show 403 "Invalid Signature" error even though the URL is correct:
`https://macrolog.online/email/verify/2/073d216871b618dffada07c9058e510db032bf6e?expires=1762250482&signature=...`

## Common Causes & Solutions

### 1. APP_KEY Mismatch (Most Common)
**Cause**: The APP_KEY on production differs from when the email was sent.

**Solution**:
```bash
# On production server, check if APP_KEY is set
php artisan tinker
config('app.key')  // Should return a base64 encoded key

# If APP_KEY changed recently, old verification emails will fail
# Users need to request NEW verification emails
```

### 2. Config Cache Issue
**Cause**: Configuration is cached with old values.

**Solution**:
```bash
# On production server
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan view:clear
```

### 3. Trailing Slash in APP_URL
**Cause**: APP_URL has trailing slash: `https://macrolog.online/`

**Solution**:
```env
# In production .env - NO trailing slash
APP_URL=https://macrolog.online
```

### 4. URL Rewriting/Redirects
**Cause**: Server is rewriting URLs (www vs non-www, http to https redirect)

**Check**:
- Email has: `https://macrolog.online/email/verify/...`
- Browser shows: `https://www.macrolog.online/email/verify/...` (notice www)
- Or any other URL transformation

**Solution**: Ensure APP_URL matches exactly what users see in browser.

### 5. TrustProxies Not Applied
**Cause**: Despite our fixes, proxies aren't being trusted correctly.

**Verify on production**:
```bash
php artisan tinker
# Run this to check detected scheme
request()->getScheme()  // Should return 'https'
url()->to('/')  // Should start with 'https://'
```

### 6. Session Domain Mismatch
**Cause**: SESSION_DOMAIN is set incorrectly.

**Solution**:
```env
# In production .env
SESSION_DOMAIN=.macrolog.online  # Note the leading dot for subdomains
# OR
SESSION_DOMAIN=null  # Let Laravel auto-detect
```

## Debugging on Production

### Step 1: Check Current Configuration
```bash
ssh your-production-server

# Check APP_URL
grep APP_URL /path/to/.env

# Check if config is cached
ls -la bootstrap/cache/config.php

# View current config
php artisan tinker
config('app.url')
config('app.key')
```

### Step 2: Check URL Generation
```bash
php artisan tinker
# Test URL generation
URL::signedRoute('verification.verify', ['id' => 1, 'hash' => 'test'])
# Should output HTTPS URL
```

### Step 3: Clear Everything and Recache
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan event:cache
sudo systemctl restart php8.2-fpm  # Adjust version as needed
```

### Step 4: Test with Fresh Email
After clearing caches:
1. Register a new test user
2. Check the verification email
3. Click the link immediately

## Quick Fix: Temporarily Disable Signature Validation

**ONLY FOR TESTING** - This helps identify if the issue is with signatures or something else:

Create: `app/Http/Middleware/SkipEmailVerificationSignature.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SkipEmailVerificationSignature
{
    public function handle(Request $request, Closure $next)
    {
        // TEMPORARY: Skip signature validation for debugging
        if ($request->is('email/verify/*')) {
            $request->merge(['signature' => hash_hmac('sha256', $request->url(), config('app.key'))]);
        }
        return $next($request);
    }
}
```

**WARNING**: Remove this after debugging!

## Recommended Solution

Since the URL is correct with HTTPS, the most likely issues are:

### Issue 1: Config Not Reloaded
```bash
# On production
php artisan config:clear
php artisan config:cache
sudo systemctl restart php8.2-fpm
```

### Issue 2: Old Emails with Old Signatures
Old verification emails won't work after configuration changes. Users must:
1. Go to `/email/verify` page
2. Click "Resend Verification Email"
3. Use the NEW link

### Issue 3: APP_KEY Environment Variable
```bash
# On production, verify APP_KEY is loaded
php artisan tinker
config('app.key')

# Should match .env file
grep APP_KEY .env
```

## Permanent Fix Checklist

- [ ] ✅ TrustProxies middleware configured (we did this)
- [ ] ✅ URL::forceScheme('https') in AppServiceProvider (we did this)
- [ ] Clear all caches on production
- [ ] Restart PHP-FPM/web server
- [ ] Test with NEW verification email (old ones won't work)
- [ ] Verify APP_URL has no trailing slash
- [ ] Verify APP_KEY hasn't changed
- [ ] Check for URL rewrites/redirects in web server config

## Still Not Working?

If signature validation still fails after all of the above, the issue might be with how Laravel is validating the signature. Contact me with:

1. Output of: `php artisan tinker` then `config('app.url')`
2. Exact URL from email
3. Any error logs from `storage/logs/laravel.log`
4. Web server configuration (Nginx/Apache)
