# Demo Deployment Guide for dangardoctor.pk/demo

## Files to Upload to public_html/website_3f612fe0/demo/

### 1. Upload ALL Laravel Files (except these):
- ❌ node_modules/ (don't upload)
- ❌ .git/ (don't upload)
- ❌ .env (create new one on server)
- ❌ storage/logs/* (will be created automatically)
- ❌ storage/framework/cache/* (will be created automatically)
- ❌ storage/framework/sessions/* (will be created automatically)
- ❌ storage/framework/views/* (will be created automatically)
- ❌ bootstrap/cache/* (will be created automatically)

### 2. Upload These Folders:
- ✅ app/
- ✅ bootstrap/
- ✅ config/
- ✅ database/
- ✅ public/
- ✅ resources/
- ✅ routes/
- ✅ storage/ (empty folders will be created)
- ✅ vendor/ (if you have it locally)

### 3. Upload These Files:
- ✅ artisan
- ✅ composer.json
- ✅ composer.lock
- ✅ package.json
- ✅ package-lock.json
- ✅ phpunit.xml
- ✅ vite.config.js
- ✅ README.md

## After Upload:

### 1. Create .env file in demo/ folder:
```
APP_NAME="Semen Admin System"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://dangardoctor.pk/demo

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@dangardoctor.pk"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

### 2. Set File Permissions:
- storage/ folder: 755
- bootstrap/cache/ folder: 755
- .env file: 644

### 3. Run Commands (via cPanel Terminal or SSH):
```bash
cd public_html/website_3f612fe0/demo
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Test:
Visit: https://dangardoctor.pk/demo
