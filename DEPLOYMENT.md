# Ved Mitra Deployment

## Server Requirements

- PHP 8.2 or newer
- Composer 2
- MySQL 8 or PostgreSQL for production data
- Web server document root pointed to `public/`
- Writable `storage/` and `bootstrap/cache/`
- HTTPS enabled before live payments or Google login

## Environment

1. Copy `.env.production.example` to `.env` on the server.
2. Set `APP_URL` to the live HTTPS domain.
3. Generate a production key:

```bash
php artisan key:generate --force
```

4. Fill database, mail, Razorpay, Google OAuth, support email, and support phone values.
5. Keep `.env` out of git and deployment artifacts.

## Release Commands

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=EbookLibrarySeeder --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

This project currently renders frontend assets through CDN-loaded Tailwind, Font Awesome, Alpine, and remote media URLs, so no Vite build is required for the current Blade pages.

## Web Server

Point the domain to the Laravel `public/` directory. Do not expose the project root, `.env`, `storage/`, or `vendor/` directly.

## Queue And Scheduler

Run the queue worker if bookings, mail, or background jobs are enabled:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Add the scheduler:

```bash
* * * * * php /path/to/ved-mitra/artisan schedule:run >> /dev/null 2>&1
```

## Final Checks

```bash
php artisan test
php artisan route:list
curl -I https://your-domain.com
curl -I https://your-domain.com/sitemap.xml
```

Confirm:

- `APP_DEBUG=false`
- `APP_ENV=production`
- HTTPS works
- Razorpay keys are live keys
- Google redirect URI matches the deployed domain
- Mail transport sends successfully
- `storage/` and `bootstrap/cache/` are writable
