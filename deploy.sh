#!/bin/bash

# Ved Mitra Deployment Script
# This script helps deploy the application to Render

echo "🚀 Ved Mitra Deployment Script"
echo "================================"

# Check if we're in the project directory
if [ ! -f "composer.json" ]; then
    echo "❌ Error: Please run this script from the project root directory"
    exit 1
fi

echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "📦 Installing Node dependencies..."
npm ci

echo "🔨 Building assets..."
npm run build

echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "🌱 Seeding database..."
php artisan db:seed --class=EbookLibrarySeeder --force

echo "🔗 Creating storage link..."
php artisan storage:link

echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps:"
echo "1. Push your code to GitHub"
echo "2. Connect your GitHub repo to Render"
echo "3. Set environment variables in Render dashboard"
echo "4. Deploy!"
echo ""
echo "🔗 Useful links:"
echo "- Render: https://render.com"
echo "- Laravel Deployment: https://laravel.com/docs/deployment"
echo "- Ved Mitra Docs: Check RENDER_DEPLOYMENT.md"