# Troubleshooting Guide

## Step-by-Step Debugging

### Step 1: Check Database Connection
1. Open: `http://localhost/user-auth-system/api/check_database.php`
2. This will show:
   - MySQL connection status
   - Whether the `users` table exists
   - All registered users
   - Password hash format
   - Redis connection
   - MongoDB connection

### Step 2: Verify User Registration
1. Go to the database check page (above)
2. Check if your registered email appears in the "Registered Users" table
3. If NOT, registration failed - check registration errors

### Step 3: Test Login Directly
1. Use the test form on `check_database.php`
2. Enter your email and password
3. This will show exactly what's happening during login

### Step 4: Common Issues & Solutions

#### Issue: "No users found in database"
**Solution:**
- Registration might have failed silently
- Check browser console for errors (F12)
- Try registering again
- Check `api/register.php` file exists

#### Issue: "Invalid credentials" but user exists
**Possible Causes:**
1. **Wrong password** - Verify you're using the exact password from registration
2. **Password hash mismatch** - Check if password is being hashed correctly
3. **Email case sensitivity** - Try exact same email (lowercase recommended)

#### Issue: "Database connection failed"
**Solutions:**
1. Make sure MySQL is running in XAMPP
2. Check `config/mysql.php` - verify database name is `internship_task`
3. Import `database/mysql_schema.sql` in phpMyAdmin

#### Issue: "Redis connection failed"
**Solutions:**
1. Make sure Redis server is running
2. Install PHP Redis extension:
   ```bash
   # For Windows, download from: https://pecl.php.net/package/redis
   # Or use XAMPP pre-compiled extensions
   ```
3. Check if Redis is running: `redis-cli ping` (should return PONG)

#### Issue: "Users table does NOT exist"
**Solution:**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select or create database `internship_task`
3. Import file: `database/mysql_schema.sql`
4. Or manually run:
   ```sql
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(100) NOT NULL,
       email VARCHAR(150) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

### Step 5: Check Browser Console
1. Press F12 in your browser
2. Go to "Console" tab
3. Try to login
4. Look for any JavaScript errors
5. Check "Network" tab for API responses

### Step 6: Check PHP Error Logs
- XAMPP PHP error log location: `C:\xampp\php\logs\php_error_log`
- Apache error log: `C:\xampp\apache\logs\error.log`

## Quick Test Checklist

- [ ] MySQL is running in XAMPP
- [ ] Database `internship_task` exists
- [ ] Table `users` exists
- [ ] Redis server is running
- [ ] User is registered (check via check_database.php)
- [ ] Using correct email and password (exact match)
- [ ] Browser console shows no errors
- [ ] API endpoint is accessible

## Testing Registration Flow

1. Go to: `http://localhost/user-auth-system/public/signup.html`
2. Fill in:
   - Username: `testuser`
   - Email: `test@test.com`
   - Password: `test123`
3. Click Register
4. Should see "Registration successful!" alert
5. Check database: `http://localhost/user-auth-system/api/check_database.php`
6. Should see `test@test.com` in the users list

## Testing Login Flow

1. Go to: `http://localhost/user-auth-system/public/login.html`
2. Enter:
   - Email: `test@test.com`
   - Password: `test123`
3. Click Login
4. Should redirect to profile page
5. If fails, check error message and use test_login.php
