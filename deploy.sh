#!/bin/bash

# Post-deployment script for cloud hosting
echo "Running post-deployment tasks..."

# Run migrations
php artisan migrate --force

# Seed database if needed
php artisan db:seed --force

# Cache config
php artisan config:cache
php artisan route:cache

# Set permissions
chmod -R 775 storage bootstrap/cache

echo "Deployment tasks completed!"
