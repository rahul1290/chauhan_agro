# GoDaddy Shared Hosting Deployment Guide
## Chauhan Agro Industries (chauhanagroindustries.com) - Multilingual Business Website & Admin CMS

This guide provides instructions for deploying the **Chauhan Agro Industries** (`chauhanagroindustries.com`) Laravel application to **GoDaddy Linux Shared Hosting (cPanel)** or compatible PHP 8.x shared hosting environments.

---

### 1. Hosting Requirements Checklist

| Requirement | Minimum Supported | Recommended on GoDaddy |
| :--- | :--- | :--- |
| **PHP Version** | `PHP 8.0` | `PHP 8.1` or `PHP 8.2` (Selected in cPanel MultiPHP Manager) |
| **PHP Extensions** | `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`, `curl`, `zip` | Enabled via MultiPHP INI Editor / PHP Selector |
| **Database** | `MySQL 5.7+` or `MariaDB 10.3+` | Standard cPanel MySQL Database |
| **Web Server** | Apache 2.4+ with `mod_rewrite` enabled | Default on GoDaddy Linux Shared Hosting |
| **Background Daemons** | None required (Runs on synchronous/database queue) | Compatible with GoDaddy resource caps |

---

### 2. Recommended Directory Architecture (Secure Layout)

In GoDaddy cPanel, the public web root is `public_html`. To prevent sensitive files (`.env`, `storage/logs`, application source code) from being accessible over the web, use the two-folder structure:

```
/home/your_cpanel_username/
├── agrovision_core/              <-- Laravel core files (outside web root)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env                      <-- Secure, inaccessible via browser
│   └── artisan
│
└── public_html/                  <-- Web root (only files from Laravel's 'public/' folder)
    ├── assets/
    │   ├── css/
    │   ├── images/
    │   └── vendor/
    ├── storage -> /home/your_cpanel_username/agrovision_core/storage/app/public
    ├── index.php                 <-- Modified bootstrap loader
    ├── robots.txt
    └── .htaccess                 <-- URL rewriting rules
```

---

### 3. Step-by-Step Deployment Procedure

#### Step 3.1: Prepare Application Archive
On your local machine, run optimization commands:
```powershell
php artisan config:clear
php artisan route:clear
php artisan view:clear
```
Zip the application files, **excluding** `/node_modules`, `.git`, and local cache files.

#### Step 3.2: Upload & Extract Files in cPanel File Manager
1. Log into your **GoDaddy cPanel**.
2. Open **File Manager**.
3. In your home directory (`/home/username/`), create a folder named `agrovision_core`.
4. Upload your zip file to `/home/username/agrovision_core/` and extract it.
5. Move the contents of `agrovision_core/public/` directly into `public_html/`.

#### Step 3.3: Update `public_html/index.php`
Edit `public_html/index.php` to point to the new location of `vendor/` and `bootstrap/`:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check If The Application Is Under Maintenance
if (file_exists($maintenance = __DIR__.'/../agrovision_core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register The Auto Loader
require __DIR__.'/../agrovision_core/vendor/autoload.php';

// Run The Application
$app = require_once __DIR__.'/../agrovision_core/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

#### Step 3.4: Configure File Permissions
In cPanel File Manager, ensure the following permissions:
- `agrovision_core/storage/` -> **775** (or **755**)
- `agrovision_core/storage/framework/` (and subfolders: `cache`, `sessions`, `views`) -> **775**
- `agrovision_core/storage/logs/` -> **775**
- `agrovision_core/bootstrap/cache/` -> **775**

#### Step 3.5: Create MySQL Database & User
1. In cPanel, navigate to **MySQL Databases**.
2. Create a new database: e.g., `username_agrovision`.
3. Create a new MySQL user: e.g., `username_agrouser` with a strong password.
4. Add the user to the database and grant **ALL PRIVILEGES**.

#### Step 3.6: Configure `.env` File
In `agrovision_core/.env`, set the production environment parameters:

```ini
APP_NAME="AgroVision"
APP_ENV=production
APP_KEY=base64:... (use generated key)
APP_DEBUG=false
APP_URL=https://www.yourdomain.com

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=username_agrovision
DB_USERNAME=username_agrouser
DB_PASSWORD="your_strong_password"

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

#### Step 3.7: Create Storage Symlink on GoDaddy Shared Hosting
GoDaddy shared hosting often does not provide SSH access on standard tier plans. You can create the storage symlink using either of these two methods:

##### Method A: Via SSH (If SSH is enabled)
```bash
ln -s /home/username/agrovision_core/storage/app/public /home/username/public_html/storage
```

##### Method B: Via One-Time PHP Script
Create a file `public_html/symlink.php`:
```php
<?php
$target = '/home/username/agrovision_core/storage/app/public';
$link = __DIR__ . '/storage';

if (symlink($target, $link)) {
    echo "Storage symbolic link created successfully!";
} else {
    echo "Failed to create symbolic link. Check parent directory permissions.";
}
```
Access `https://www.yourdomain.com/symlink.php` once in your browser, verify the success message, and **immediately delete `symlink.php`**.

#### Step 3.8: Run Database Migrations & Seeders
##### Method A: Via SSH
```bash
cd /home/username/agrovision_core
/usr/local/bin/php artisan migrate --force
/usr/local/bin/php artisan db:seed --force
```

##### Method B: Via phpMyAdmin (Recommended for cPanel without SSH)
1. Export your local database `argo_db` as an `.sql` file using phpMyAdmin or mysqldump.
2. In GoDaddy cPanel, open **phpMyAdmin**.
3. Select your database `username_agrovision`.
4. Click **Import**, choose the `.sql` file, and click **Go**.

---

### 4. Setting Up GoDaddy Cron Jobs (Scheduled Tasks)

If you configure automated enquiry email reports or banner scheduling:
1. In cPanel, click **Cron Jobs**.
2. Select Interval: **Once Per Minute** (`* * * * *`).
3. Set Command:
```bash
/usr/local/bin/php /home/username/agrovision_core/artisan schedule:run >> /dev/null 2>&1
```
*(Verify the exact path to PHP in your cPanel dashboard, e.g. `/usr/local/bin/php` or `/usr/bin/php`).*

---

### 5. Apache `.htaccess` Verification

Ensure `public_html/.htaccess` contains standard URL rewrite rules:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Block access to hidden files and sensitive extensions
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---

### 6. SSL & HTTPS Enforcement

1. In GoDaddy cPanel, ensure your **AutoSSL** certificate is active under **SSL/TLS Status**.
2. Add the HTTPS redirection rule to `public_html/.htaccess`:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

### 7. Post-Deployment Verification

1. **Public Site**: Open `https://www.yourdomain.com` - should automatically redirect to `https://www.yourdomain.com/en`.
2. **Language Switching**: Test toggling between English and Hindi (`/hi`).
3. **Products Catalogue**: Browse `/en/products` and filter by category (Rotary Tillers, Laser Levellers).
4. **Product Details**: Check technical specs table, images, and brochure PDF download.
5. **Contact / Enquiry**: Submit a test enquiry on `/en/contact`. Verify confirmation message.
6. **Admin Login**: Go to `https://www.yourdomain.com/admin/login` and log in:
   - Super Admin: `admin@agrovision.com` / `password123`
   - Verify that the submitted enquiry appears in the **Enquiry Inbox**.
7. **Change Default Admin Passwords**: Immediately update passwords for all admin accounts in the **Staff & Roles** module.

---

### 8. Backup & Rollback Strategy

- **Database Backup**: In cPanel, use **Backup Wizard** or **phpMyAdmin** -> **Export** before performing updates.
- **Code Backup**: Zip the current `agrovision_core/` directory before uploading new code releases.
- **Rollback**: In case of deployment issues, restore the previous `.sql` backup via phpMyAdmin and unzip the previous application archive.
