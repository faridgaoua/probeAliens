# WordPress Project – Local / Environment Clone Guide For my case i used (wordpress studio local server)

This README explains how to clone this WordPress project **including the database**, and run it locally or in another environment (staging / production).

---

## Prerequisites

Make sure you have the following installed:

* Git
* PHP (version compatible with the site)
* MySQL / MariaDB
* Web server (Apache, Nginx, or local stack like XAMPP, MAMP, WAMP, wordpress studio)
* (Optional but recommended) WP-CLI

---

## 1. Clone the Repository

Clone the project from GitHub:

```bash
git clone https://github.com/faridgaoua/probeAliens.git
cd probeAliens
```
---

## 2. Create a Database

Create a new empty database:

```sql
CREATE DATABASE your_database_name;
```

Also create a database user if needed and grant permissions.

---

## 3. Import the Database

### Option A: Using phpMyAdmin

1. Open phpMyAdmin
2. Select the new database
3. Click **Import**
4. Upload the `.sql` file found in:

   ```
   /database/probe.sql
   ```
5. Click **Go**

### Option B: Using Command Line

```bash
mysql -u db_user -p your_database_name < database/probe.sql
```

---

## 4. Configure `wp-config.php`

Copy the example config if needed:

```bash
cp wp-config-sample.php wp-config.php
```

Update database credentials:

```php
define('DB_NAME', 'your_database_name');
define('DB_USER', 'db_user');
define('DB_PASSWORD', 'db_password');
define('DB_HOST', 'localhost');
```

---

## 5. Update Site URL (Important)

If the site URL changed (e.g. production → local), update it.

### Option A: WP-CLI (Recommended)

```bash
wp search-replace 'http://localhost:8881/' 'eg:https://probe.locale'

```

Then flush cache:

```bash
wp cache flush
```

### Option B: Database (Manual)

Update these values in the `wp_options` table:

* `siteurl`
* `home`


ps: custom fields images loaded with http://localhost:8881/ prefix . to avoid error create a new field with new featured image !!
---

## 6. File Permissions

Ensure correct permissions:

```bash
chmod -R 755 wp-content
chmod -R 775 wp-content/uploads
```

---

## 7. Access the Site

Add a local host entry if needed:

```text
127.0.0.1 yoursite.test
```

Then open:

```
http://yoursite.test
```

---

## Common Issues

### White Screen / 500 Error

* Enable debugging in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
```

### Broken Links or Images

* Run `search-replace`
* Clear browser and plugin cache

## License

Probe Test Aliens Exist.

PS: I did everything inside a plugin Just You need to install ACF plugin and the field create automatically by the custom block plugin. 

    ** Maybe i forgot Something due to don't have task file "Readme2" i did just why i remember !! ** Thank you

