# Ved Mitra - Vercel Deployment

## Frontend/API Deployment on Vercel

For Vercel deployment, we'll deploy the API routes and static assets.

### API Routes
- `/api/kundli/generate` - Kundli generation endpoint

### Static Assets
- CSS, JS, and image assets compiled by Vite

### Deployment Steps

1. Connect your GitHub repository to Vercel
2. Set build settings:
   - **Build Command**: `npm run build`
   - **Output Directory**: `public`
   - **Install Command**: `npm install`

3. Set environment variables in Vercel dashboard (same as Render)

4. For Laravel API routes, you might need serverless functions. Create `api/kundli/generate.php` in your project root.

### Note
Since this is a traditional Laravel app with Blade views, you might want to deploy the entire app on Render instead. Vercel works best with:
- Static sites
- Jamstack applications
- API-only backends

For full Laravel deployment, Render is recommended.