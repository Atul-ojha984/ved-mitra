# Ved Mitra - Complete Deployment Guide

A comprehensive spiritual platform for booking pandits, kundli generation, festival information, and e-books.

## 🚀 Quick Deployment

### Option 1: Render (Recommended - Full Stack)
1. **Fork/Clone** this repository to GitHub
2. **Connect to Render**: Go to [render.com](https://render.com) and create a new Web Service
3. **Connect GitHub**: Link your repository
4. **Configure Build**:
   - **Runtime**: Docker
   - **Build Command**: `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
5. **Add PostgreSQL**: Create a PostgreSQL database in Render
6. **Set Environment Variables**: Copy from `.env.production.example`
7. **Deploy**: Render will build and deploy automatically

### Option 2: Vercel (API Only)
1. **Connect to Vercel**: Go to [vercel.com](https://vercel.com)
2. **Import Project**: Connect your GitHub repository
3. **Configure Build**:
   - **Framework Preset**: Other
   - **Build Command**: `npm run build`
   - **Output Directory**: `public`
4. **Set Environment Variables**: Add production variables
5. **Deploy**

## 📋 Environment Variables

### Required for Production
```bash
APP_NAME="Ved Mitra"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (PostgreSQL on Render)
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host.render.com
DB_PORT=5432
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password

# Payment (Razorpay)
RAZORPAY_KEY_ID=your-key-id
RAZORPAY_KEY_SECRET=your-key-secret

# Google OAuth
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret

# Support
SUPPORT_EMAIL=support@yourdomain.com
SUPPORT_PHONE="+91 98765 43210"
```

## 🛠️ Local Development

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed --class=EbookLibrarySeeder

# Build assets
npm run build

# Start development server
php artisan serve
```

## 📁 Project Structure

```
├── app/                    # Laravel application code
├── config/                 # Configuration files
├── database/               # Migrations and seeders
├── public/                 # Public assets
├── resources/              # Views, CSS, JS
├── routes/                 # Route definitions
├── storage/                # File storage
├── tests/                  # Test files
├── Dockerfile              # Docker configuration
├── render.yaml            # Render deployment config
├── vercel.json            # Vercel deployment config
└── deploy.ps1            # Deployment script
```

## 🔧 Features

- ✅ Pandit booking system
- ✅ Kundli generation
- ✅ Festival calendar
- ✅ E-book library with Hindi texts
- ✅ Payment integration (Razorpay)
- ✅ Google OAuth login
- ✅ Responsive design
- ✅ SEO optimized

## 📞 Support

For deployment issues:
1. Check the logs in Render/Vercel dashboard
2. Verify environment variables are set correctly
3. Ensure database is accessible
4. Check file permissions for storage directories

## 🔒 Security Notes

- Never commit `.env` files
- Use HTTPS in production
- Keep dependencies updated
- Monitor logs for security issues
- Use strong passwords for all services

---

**Happy Deploying! 🕉️**