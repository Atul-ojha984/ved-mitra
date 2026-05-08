# Ved Mitra - Render Deployment

## Backend Deployment on Render

This Laravel application is configured for deployment on Render with PostgreSQL database.

### Environment Variables

Set these in your Render service environment:

```
APP_NAME=Ved Mitra
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://your-render-app.onrender.com

DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_PORT=5432
DB_DATABASE=your-database-name
DB_USERNAME=your-db-username
DB_PASSWORD=your-db-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Ved Mitra"

RAZORPAY_KEY_ID=your-razorpay-key-id
RAZORPAY_KEY_SECRET=your-razorpay-key-secret

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret

SUPPORT_EMAIL=support@yourdomain.com
SUPPORT_PHONE=+91 98765 43210
```

### Build Command
```
composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

### Start Command
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Database Setup
1. Create a PostgreSQL database on Render
2. Run migrations: `php artisan migrate --force`
3. Seed data: `php artisan db:seed --class=EbookLibrarySeeder --force`