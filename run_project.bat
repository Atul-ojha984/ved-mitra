@echo off
echo ========================================================
echo Setting up Ved Mitra Project
echo ========================================================

echo Fixing folder permissions (stripping copied OneDrive read-only attributes)...
attrib -R -S bootstrap\cache /S /D
attrib -R -S storage /S /D
attrib -R -S bootstrap\cache\*.* /S /D
attrib -R -S storage\*.* /S /D

echo Creating SQLite database if it doesn't exist...
if not exist database\database.sqlite type nul > database\database.sqlite

echo Running Composer Install to ensure all dependencies are ready...
call composer install

echo Installing Laravel Breeze with Livewire...
call composer require laravel/breeze --dev
call php artisan breeze:install livewire --no-interaction

echo Generating App Key...
call php artisan key:generate

echo Running Database Migrations...
call php artisan migrate --force

echo.
echo ========================================================
echo Setup Complete! Starting Development Server...
echo ========================================================
echo The application will be available at http://127.0.0.1:8000
echo Leave this window open to keep the server running.
echo.

call php artisan serve

pause
