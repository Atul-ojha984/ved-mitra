# Ved Mitra Deployment Script for Windows
# Run this script before deploying to Render

Write-Host "🚀 Ved Mitra Deployment Script" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Green

# Check if we're in the project directory
if (!(Test-Path "composer.json")) {
    Write-Host "❌ Error: Please run this script from the project root directory" -ForegroundColor Red
    exit 1
}

Write-Host "📦 Installing Composer dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader

Write-Host "📦 Installing Node dependencies..." -ForegroundColor Yellow
npm ci

Write-Host "🔨 Building assets..." -ForegroundColor Yellow
npm run build

Write-Host "🗄️  Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force

Write-Host "🌱 Seeding database..." -ForegroundColor Yellow
php artisan db:seed --class=EbookLibrarySeeder --force

Write-Host "🔗 Creating storage link..." -ForegroundColor Yellow
php artisan storage:link

Write-Host "⚡ Optimizing application..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "✅ Deployment preparation complete!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Cyan
Write-Host "1. Push your code to GitHub" -ForegroundColor White
Write-Host "2. Connect your GitHub repo to Render" -ForegroundColor White
Write-Host "3. Set environment variables in Render dashboard" -ForegroundColor White
Write-Host "4. Deploy!" -ForegroundColor White
Write-Host ""
Write-Host "🔗 Useful links:" -ForegroundColor Cyan
Write-Host "- Render: https://render.com" -ForegroundColor White
Write-Host "- Laravel Deployment: https://laravel.com/docs/deployment" -ForegroundColor White
Write-Host "- Ved Mitra Docs: Check RENDER_DEPLOYMENT.md" -ForegroundColor White