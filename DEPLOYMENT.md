# Real-Time Live Poll Platform

## 🚀 Quick Demo

**Live URL**: TBD (Deploy to get URL)  
**Demo Login**: `demo@demo.com` / `demo123`  
**Admin Login**: `admin@example.com` / `password`

---

## 📦 Deployment Instructions

### Option 1: Railway (Recommended - Free & Easy)

1. **Prerequisites**
   ```bash
   - GitHub account
   - Railway account (sign up at railway.app)
   ```

2. **Push to GitHub**
   ```bash
   cd polling-app
   git init
   git add .
   git commit -m "Initial commit - Polling Platform"
   git branch -M main
   git remote add origin https://github.com/YOUR_USERNAME/polling-app.git
   git push -u origin main
   ```

3. **Deploy on Railway**
   - Go to [railway.app](https://railway.app)
   - Click "New Project" → "Deploy from GitHub repo"
   - Select `polling-app` repository
   - Railway will auto-detect Laravel

4. **Add MySQL Database**
   - In your project dashboard, click "+ New"
   - Select "Database" → "Add MySQL"
   - Railway will automatically set environment variables

5. **Set Environment Variables**
   - Click on your web service
   - Go to "Variables" tab
   - Add these:
     ```
     APP_KEY=base64:97I6cp4rdbYPuzsv/Qe3O0bDQiwNKHLFn7fKmwgyuX8=
     APP_ENV=production
     APP_DEBUG=false
     APP_URL=https://YOUR-APP.up.railway.app
     ```
   - Railway auto-sets: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, DB_PORT

6. **Run Migrations**
   - In Railway dashboard, open your service
   - Click "Settings" → "Deploy"
   - Or use Railway CLI:
     ```bash
     railway run php artisan migrate --seed --force
     ```

7. **Get Your Live URL**
   - Go to "Settings" → "Domains"
   - Copy the provided railway.app domain
   - Share with assessors!

---

### Option 2: Heroku

1. **Install Heroku CLI**
   ```bash
   # Download from heroku.com/cli
   heroku login
   ```

2. **Create Heroku App**
   ```bash
   cd polling-app
   heroku create your-polling-app
   ```

3. **Add MySQL (ClearDB)**
   ```bash
   heroku addons:create cleardb:ignite
   ```

4. **Set Environment**
   ```bash
   heroku config:set APP_KEY=base64:97I6cp4rdbYPuzsv/Qe3O0bDQiwNKHLFn7fKmwgyuX8=
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   ```

5. **Deploy**
   ```bash
   git push heroku main
   heroku run php artisan migrate --seed --force
   ```

6. **Open App**
   ```bash
   heroku open
   ```

---

### Option 3: Shared Hosting (cPanel)

1. **Upload Files**
   - Upload all files via FTP to `public_html` or subdirectory
   - Move content of `public/` to web root
   - Update `public/index.php` to point to correct paths

2. **Create MySQL Database**
   - In cPanel → MySQL Databases
   - Create database `polling_app`
   - Create user and grant all privileges

3. **Update .env**
   ```env
   DB_HOST=localhost
   DB_DATABASE=your_cpanel_polling_app
   DB_USERNAME=your_cpanel_user
   DB_PASSWORD=your_cpanel_password
   ```

4. **Run Migrations via SSH or phpMyAdmin**
   ```bash
   php artisan migrate --seed --force
   ```

---

## 🔑 Login Credentials for Assessors

### Demo User (Quick Testing)
- **Email**: `demo@demo.com`
- **Password**: `demo123`

### Admin User (Full Access)
- **Email**: `admin@example.com`
- **Password**: `password`

---

## ✅ Features to Demonstrate

1. **Authentication** - Login with demo credentials
2. **Poll Selection** - Click any poll (AJAX, no page reload)
3. **Voting** - Click option to vote
4. **IP Restriction** - Try voting again (blocked with message)
5. **Real-Time Results** - Open in 2 tabs, results sync every 1 second
6. **Admin Panel** - Release IP to allow re-voting
7. **Vote History** - Click "History" to see audit trail

---

## 🛠️ Technical Stack

- **Backend**: Laravel 11 + Core PHP (voting logic)
- **Frontend**: Bootstrap 5 + jQuery + AJAX
- **Database**: MySQL 8.4
- **Real-time**: AJAX polling (1-second interval)

---

## 📁 Project Structure

```
polling-app/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── PollController.php
│   │   ├── VoteController.php
│   │   └── AdminController.php
│   └── Models/
│       ├── Poll.php
│       ├── PollOption.php
│       ├── Vote.php
│       └── VoteHistory.php
├── core/                    # Core PHP voting logic
│   ├── db_helper.php
│   ├── vote_logic.php
│   └── ip_handler.php
├── public/
│   ├── css/style.css
│   └── js/app.js           # AJAX real-time updates
└── resources/views/
    ├── auth/login.blade.php
    └── polls/index.blade.php
```

---

## 🎯 PRD Compliance

✅ Laravel for routing, auth, views  
✅ Core PHP for voting logic  
✅ MySQL database  
✅ AJAX for all interactions  
✅ Real-time updates (~1 second)  
✅ IP-based voting restriction  
✅ Vote release & audit history  
✅ No page reloads

**Full compliance report**: See `prd_compliance.md`

---

## 📞 Support

**Issues?** Check:
1. Database connection (environment variables set correctly)
2. Migrations run (`php artisan migrate:fresh --seed`)
3. Storage permissions (`chmod -R 775 storage bootstrap/cache`)

---

## 📝 Submission Checklist

- [ ] Code pushed to GitHub
- [ ] App deployed to hosting platform
- [ ] Database migrated and seeded
- [ ] Live URL accessible
- [ ] Demo login tested
- [ ] All 4 modules working

**Submit**: Live URL + GitHub repo link
