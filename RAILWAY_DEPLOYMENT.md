# Railway Deployment Guide

## Quick Answer: Which MongoDB Extension for XAMPP?

**For XAMPP (most common): Download**
- **8.0 Thread Safe (TS) x64**

XAMPP uses **Thread Safe (TS)** and **64-bit (x64)** by default.

**To Confirm (Run this file):**
```
http://localhost/user-auth-system/check_php_version.php
```

---

## Railway Deployment Steps

### 1. Prerequisites
- GitHub account
- Railway account (sign up at railway.app)
- Your code pushed to GitHub

### 2. Setup Railway Project

1. **Login to Railway:**
   - Go to https://railway.app
   - Sign up/login with GitHub

2. **Create New Project:**
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Select your repository

3. **Railway will auto-detect PHP**

### 3. Add Environment Variables

In Railway dashboard → Variables tab, add:

**MySQL:**
```
DB_HOST=your-mysql-host
DB_PORT=3306
DB_USER=your-mysql-user
DB_PASS=your-mysql-password
DB_NAME=internship_task
```

**MongoDB:**
```
MONGO_URI=mongodb://username:password@host:port/
MONGO_DB_NAME=internship_task
```

**Redis:**
```
REDIS_HOST=your-redis-host
REDIS_PORT=6379
REDIS_PASS=your-redis-password
```

### 4. Add Services in Railway

#### A. MySQL Database
- Click "New" → "Database" → "MySQL"
- Railway will provide connection string
- Use those values for DB_* variables

#### B. MongoDB Database
- Click "New" → "Database" → "MongoDB"
- Railway will provide MONGO_URI
- Copy the connection string to MONGO_URI variable

#### C. Redis
- Click "New" → "Database" → "Redis"
- Railway will provide connection details
- Use for REDIS_* variables

### 5. Deploy

Railway will automatically:
- Detect PHP
- Install composer dependencies
- Deploy your app

### 6. Access Your App

After deployment:
- Railway gives you a URL like: `your-app.railway.app`
- Your app will be live at that URL

---

## Important Files for Railway

Railway uses these files (already created):
- ✅ `Dockerfile` - For PHP/MongoDB setup
- ✅ `composer.json` - Dependencies
- ✅ `.railwayignore` - Files to exclude (if created)

---

## Troubleshooting

### If MongoDB Error:
- Railway installs MongoDB extension automatically via Dockerfile
- Make sure MONGO_URI is set correctly

### If MySQL Error:
- Check DB_HOST, DB_USER, DB_PASS are correct
- Ensure database exists in Railway

### If Redis Error:
- Redis is optional (fallback will work)
- But set REDIS_* variables if you added Redis service

---

## Quick Deploy Checklist

- [ ] Code pushed to GitHub
- [ ] Railway project created
- [ ] MySQL service added (get connection details)
- [ ] MongoDB service added (get MONGO_URI)
- [ ] Redis service added (optional)
- [ ] Environment variables set
- [ ] Deployed successfully
- [ ] Test signup/login/profile
