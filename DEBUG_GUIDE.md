# Complete Debugging Guide

## 🔍 **How to Debug Your Signup/Login/Profile Flow**

### **Step 1: Test Complete Flow**

1. **Open Flow Test Page:**
   ```
   http://localhost/user-auth-system/api/test_flow.php
   ```
   This page shows the status of all services and has links to test each step.

---

### **Step 2: Test Registration**

1. **Go to Signup Page:**
   ```
   http://localhost/user-auth-system/public/signup.html
   ```

2. **Open Browser Console (Press F12)**
   - Go to "Console" tab
   - This will show any JavaScript errors

3. **Fill in the form:**
   - Username: `testuser`
   - Email: `test@test.com`
   - Password: `test123`

4. **Click Register**

5. **What to Check:**
   - ✓ Alert should show "Registration successful!"
   - ✓ Should redirect to login.html after 1 second
   - ✓ Check Console for: "Registration response: SUCCESS"
   - ✓ If it doesn't redirect, check the console error

---

### **Step 3: Verify Data is Saved**

1. **Check Database:**
   ```
   http://localhost/user-auth-system/api/check_database.php
   ```

2. **Look for:**
   - ✓ Your email should appear in "Registered Users" table
   - ✓ If NOT there, registration failed silently

---

### **Step 4: Test Login**

1. **Go to Login Page:**
   ```
   http://localhost/user-auth-system/public/login.html
   ```

2. **Enter credentials:**
   - Email: `test@test.com`
   - Password: `test123`

3. **Click Login**

4. **What to Check:**
   - ✓ Should show "Login successful!" alert
   - ✓ Should redirect to profile.html
   - ✓ Check Console: Should see token in response
   - ✓ Check localStorage: Press F12 → Console → Type: `localStorage.getItem('token')`
     - Should show a token string

---

### **Step 5: Test Profile**

1. **After Login, you should be on Profile page**

2. **Fill in Profile:**
   - Age: `25`
   - Date of Birth: Select a date
   - Contact: `1234567890`

3. **Click "Save Profile"**

4. **What to Check:**
   - ✓ Should show "Profile saved successfully!" alert
   - ✓ Data should remain in fields (not disappear)

5. **Refresh the page:**
   - ✓ Your saved data should still be there

---

### **Step 6: Test Logout**

1. **Click "Logout" button**

2. **What to Check:**
   - ✓ Should show confirmation dialog
   - ✓ Should redirect to login.html
   - ✓ Token should be removed from localStorage

---

## 🐛 **Common Issues & Fixes**

### **Issue 1: Registration doesn't redirect**

**Symptoms:**
- Alert shows "Registration successful!"
- But page doesn't change

**Debug Steps:**
1. Open Console (F12)
2. Check for error: `Uncaught TypeError` or similar
3. Check Network tab → Find `register.php` request
4. Look at Response - should be exactly `SUCCESS` (no extra text)

**Fix:**
- Already fixed! Response is now trimmed
- If still not working, check browser console for exact error

---

### **Issue 2: Login always fails**

**Symptoms:**
- "Invalid credentials" even with correct password

**Debug Steps:**
1. Check database: `http://localhost/user-auth-system/api/check_database.php`
2. Verify your email exists
3. Use test_login.php form on check_database.php
4. Check Console → Network tab → login.php response

**Common Causes:**
- Wrong password (typos, spaces)
- Email case mismatch
- Password hash mismatch

---

### **Issue 3: Profile doesn't save**

**Symptoms:**
- Click Save but nothing happens
- No success message

**Debug Steps:**
1. Check Console for errors
2. Check Network tab → profile_update.php
3. Verify MongoDB is running
4. Check Redis is running (needed for token)

---

### **Issue 4: "Database connection failed"**

**Solutions:**
1. Start MySQL in XAMPP Control Panel
2. Check `config/mysql.php` - database name should be `internship_task`
3. Import `database/mysql_schema.sql` in phpMyAdmin

---

### **Issue 5: "Redis connection failed"**

**Solutions:**
1. Start Redis server
2. Check if Redis PHP extension is installed
3. Test: `redis-cli ping` (should return PONG)

---

## 🔧 **Quick Debug Checklist**

Before reporting issues, check:

- [ ] MySQL is running in XAMPP
- [ ] Redis server is running
- [ ] MongoDB is running (optional for profile)
- [ ] Database `internship_task` exists
- [ ] Table `users` exists
- [ ] Browser console shows no JavaScript errors
- [ ] Network tab shows API responses
- [ ] Using correct URLs (with `/public/` in path)

---

## 📊 **What Each File Does**

### **Frontend Files:**
- `public/signup.html` + `public/js/signup.js` → Registration
- `public/login.html` + `public/js/login.js` → Login
- `public/profile.html` + `public/js/profile.js` → Profile & Logout

### **Backend Files:**
- `api/register.php` → Saves user to MySQL
- `api/login.php` → Validates credentials, creates token in Redis
- `api/profile_get.php` → Gets profile from MongoDB
- `api/profile_update.php` → Saves profile to MongoDB

### **Config Files:**
- `config/mysql.php` → MySQL connection
- `config/redis.php` → Redis connection
- `config/mongo.php` → MongoDB connection

---

## 🎯 **Testing URLs**

```
Home:        http://localhost/user-auth-system/
Signup:      http://localhost/user-auth-system/public/signup.html
Login:       http://localhost/user-auth-system/public/login.html
Profile:     http://localhost/user-auth-system/public/profile.html
Database:    http://localhost/user-auth-system/api/check_database.php
Flow Test:   http://localhost/user-auth-system/api/test_flow.php
```

---

## 💡 **Pro Tips**

1. **Always check browser console (F12)** - Most errors are shown there
2. **Use Network tab** - See what API responses you're getting
3. **Check localStorage** - After login, verify token exists
4. **Test one step at a time** - Don't test everything at once
5. **Use test_flow.php** - Quick way to check all connections

---

If you still have issues after following this guide, provide:
1. Screenshot of browser console
2. Screenshot of Network tab (showing API response)
3. What exact error message you see
