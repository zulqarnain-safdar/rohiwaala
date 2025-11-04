#!/bin/bash

# RohiWala.pk Platform - Production Build Script
# Run this script before deploying to production

echo "🚀 Starting production build process..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the Laravel project root directory"
    exit 1
fi

# Install/Update PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install/Update Node dependencies
echo "📦 Installing Node dependencies..."
npm ci

# Build assets for production
echo "🔨 Building assets for production..."
npm run build

# Generate application key if not exists
if [ ! -f ".env" ]; then
    echo "⚠️  Warning: .env file not found. Please create it from .env.example"
    echo "📝 Copying .env.example to .env..."
    cp .env.example .env
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Clear and cache configuration
echo "🧹 Clearing and caching configuration..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache configuration for production
echo "⚡ Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "✅ Production build completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Commit your changes: git add . && git commit -m 'Production build'"
echo "2. Push to GitHub: git push origin main"
echo "3. Deploy to Bluehost following the DEPLOYMENT.md guide"
echo ""
echo "🎉 Your application is ready for deployment!"
