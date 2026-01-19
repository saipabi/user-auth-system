# Setup Instructions

## Prerequisites

1. **XAMPP** (for Apache, MySQL, PHP)
2. **Redis Server** - Download and install from https://redis.io/download
3. **MongoDB** - Download and install from https://www.mongodb.com/try/download/community
4. **Composer** - Download from https://getcomposer.org/

## Installation Steps

### 1. Start Required Services

```bash
# Start XAMPP (Apache + MySQL)
# Start Redis Server
redis-server

# Start MongoDB (Windows)
mongod

# Or if MongoDB is installed as service, it should be running automatically
```

### 2. Install PHP Dependencies

```bash
cd C:\xampp\htdocs\user-auth-system
composer install
```

This will install the MongoDB PHP driver.

### 3. Setup MySQL Database

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Import the file: `database/mysql_schema.sql`
   - Or manually execute the SQL statements to create the database and table

### 4. Verify MongoDB

MongoDB should be running on `localhost:27017` (default port)

### 5. Verify Redis

Redis should be running on `127.0.0.1:6379` (default port)

### 6. Access the Application

- **Signup**: http://localhost/user-auth-system/public/signup.html
- **Login**: http://localhost/user-auth-system/public/login.html
- **Profile**: http://localhost/user-auth-system/public/profile.html (requires login)

## Testing Flow

1. **Register a new user** at signup.html
2. **Login** with the registered credentials
3. **View/Update profile** on the profile page
4. **Logout** and verify you need to login again

## Troubleshooting

### MongoDB Connection Error
- Ensure MongoDB service is running
- Check if port 27017 is accessible

### Redis Connection Error
- Ensure Redis server is running
- Install PHP Redis extension: `pecl install redis`
- Or download from https://pecl.php.net/package/redis

### Composer Not Found
- Install Composer globally
- Or use: `php composer.phar install` (if composer.phar is in the project)

### MySQL Connection Error
- Update credentials in `config/mysql.php` if needed
- Ensure MySQL is running in XAMPP

