# RohiWala.pk Platform - Bluehost Deployment Guide

## Prerequisites
- GitHub repository with your code
- Bluehost hosting account with cPanel access
- PHP 8.2+ support on Bluehost
- MySQL database access
- SSH access (if available) or File Manager access

## Step 1: Prepare Your Local Environment

### 1.1 Build Assets for Production
```bash
# Install dependencies
npm install

# Build assets for production
npm run build

# Install PHP dependencies
composer install --optimize-autoloader --no-dev
```

### 1.2 Generate Application Key
```bash
php artisan key:generate
```

### 1.3 Commit and Push to GitHub
```bash
git add .
git commit -m "Prepare for production deployment"
git push origin main
```

## Step 2: Bluehost Server Setup

### 2.1 Access Your Bluehost Account
1. Log into your Bluehost cPanel
2. Navigate to File Manager
3. Go to your domain's public_html folder

### 2.2 Clone Repository (if SSH available)
```bash
cd /home/yourusername/public_html
git clone https://github.com/yourusername/your-repo-name.git
cd your-repo-name
```

### 2.3 Upload Files (if using File Manager)
1. Download your project as ZIP from GitHub
2. Extract and upload all files to public_html
3. Make sure all files are uploaded correctly

## Step 3: Database Setup

### 3.1 Create MySQL Database
1. In cPanel, go to "MySQL Databases"
2. Create a new database (e.g., `yourdomain_rohiwala`)
3. Create a database user with full privileges
4. Note down database name, username, and password

### 3.2 Configure Environment
1. Copy `.env.example` to `.env`
2. Update the following values in `.env`:

```env
APP_NAME="RohiWala.pk Platform"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Generate a new application key
APP_KEY=base64:your_generated_key_here
```

## Step 4: Install Dependencies and Configure

### 4.1 Install PHP Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 4.2 Install Node Dependencies and Build
```bash
npm install
npm run build
```

### 4.3 Set Permissions
```bash
# Set proper permissions for storage and bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R yourusername:yourusername storage
chown -R yourusername:yourusername bootstrap/cache
```

## Step 5: Laravel Configuration

### 5.1 Generate Application Key
```bash
php artisan key:generate
```

### 5.2 Run Migrations
```bash
php artisan migrate --force
```

### 5.3 Create Storage Link
```bash
php artisan storage:link
```

### 5.4 Clear and Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 6: Web Server Configuration

### 6.1 Update .htaccess (if needed)
Create or update `.htaccess` in the root directory:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 6.2 Update Document Root (if needed)
If your Laravel app is in a subdirectory, update the document root in cPanel to point to the `public` folder.

## Step 7: Final Steps

### 7.1 Test Your Application
1. Visit your domain in a browser
2. Check if the application loads correctly
3. Test key functionality

### 7.2 Set Up Cron Jobs (if needed)
In cPanel, go to "Cron Jobs" and add:
```bash
* * * * * cd /home/yourusername/public_html && php artisan schedule:run >> /dev/null 2>&1
```

### 7.3 SSL Certificate
1. Enable SSL in cPanel
2. Update APP_URL to use HTTPS in your `.env` file

## Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   - Check file permissions
   - Verify .env configuration
   - Check error logs in cPanel

2. **Database Connection Error**
   - Verify database credentials
   - Check if database exists
   - Ensure MySQL service is running

3. **Asset Loading Issues**
   - Run `npm run build` again
   - Check if public/build directory exists
   - Verify file permissions

4. **Storage Link Issues**
   - Run `php artisan storage:link`
   - Check if storage/app/public exists

### Useful Commands:
```bash
# Check Laravel configuration
php artisan config:show

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check application status
php artisan about
```

## Security Considerations

1. **Never commit .env file** - It's already in .gitignore
2. **Set proper file permissions** - 644 for files, 755 for directories
3. **Use HTTPS** - Update APP_URL to use HTTPS
4. **Regular updates** - Keep Laravel and dependencies updated
5. **Backup regularly** - Set up automated backups

## Maintenance

### Regular Tasks:
1. Monitor error logs
2. Update dependencies
3. Backup database and files
4. Check application performance

### Updates:
1. Pull latest changes from GitHub
2. Run `composer install` and `npm run build`
3. Run migrations if needed: `php artisan migrate`
4. Clear caches: `php artisan config:cache`
