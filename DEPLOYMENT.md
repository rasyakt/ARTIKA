# 🚀 ARTIKA POS - Deployment Guide

Production deployment guide untuk ARTIKA POS system.

---

## 📋 Table of Contents

- [Server Requirements](#server-requirements)
- [Preparation](#preparation)
- [Deployment Steps](#deployment-steps)
- [Environment Configuration](#environment-configuration)
- [Security Hardening](#security-hardening)
- [Performance Optimization](#performance-optimization)
- [Monitoring & Maintenance](#monitoring--maintenance)
- [Backup Strategy](#backup-strategy)
- [Troubleshooting](#troubleshooting)

---

## Server Requirements

### Minimum Production Requirements

- **Server:** VPS atau Dedicated Server
- **CPU:** 2 cores minimum
- **RAM:** 4GB minimum (8GB recommended)
- **Storage:** 20GB SSD minimum
- **OS:** Ubuntu 22.04 LTS atau CentOS 8+
- **PHP:** 8.2+
- **MySQL:** 8.0+ atau MariaDB 10.6+
- **Web Server:** Nginx 1.18+ atau Apache 2.4+
- **SSL Certificate:** Required (Let's Encrypt atau commercial)

### Software Stack

```
Ubuntu 22.04 LTS
├── Nginx 1.18+
├── PHP 8.2-FPM
├── MySQL 8.0
├── Redis (optional, recommended untuk cache)
├── Supervisor (untuk queue workers)
└── Certbot (untuk SSL)
```

---

## Preparation

### 1. Provision Server

**Cloud Providers:**
- DigitalOcean
- AWS EC2
- Google Cloud Platform
- Azure
- Vultr

**Recommended:** DigitalOcean Droplet (2GB RAM, 1 vCPU, $12/month)

### 2. Initial Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Create deploy user
sudo adduser deploy
sudo usermod -aG sudo deploy

# Setup SSH key auth
ssh-copy-id deploy@your-server-ip

# Disable root SSH login (security)
sudo sed -i 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config
sudo systemctl restart sshd
```

### 3. Install Required Software

```bash
# Install Nginx
sudo apt install nginx -y

# Install PHP 8.2
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring \
    php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl -y

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Redis (optional)
sudo apt install redis-server -y

# Install Supervisor
sudo apt install supervisor -y
```

---

## Deployment Steps

### Option 1: Manual Deployment

#### Step 1: Clone Repository

```bash
# Login as deploy user
ssh deploy@your-server-ip

# Create directory
sudo mkdir -p /var/www/artika
sudo chown deploy:deploy /var/www/artika

# Clone repo
cd /var/www
git clone https://github.com/yourusername/artika-pos.git artika
cd artika
```

#### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies
npm install

# Build assets
npm run build
```

#### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Edit environment
nano .env
```

**Production .env:**
```env
APP_NAME="ARTIKA POS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika_production
DB_USERNAME=artika_user
DB_PASSWORD=secure_password_here

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Step 4: Database Setup

```bash
# Create database
mysql -u root -p

CREATE DATABASE artika_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'artika_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON artika_production.* TO 'artika_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --force
```

#### Step 5: Set Permissions

```bash
sudo chown -R deploy:www-data /var/www/artika
sudo chmod -R 775 /var/www/artika/storage
sudo chmod -R 775 /var/www/artika/bootstrap/cache
```

### Option 2: Automated Deployment (Using Deployer)

```bash
# Install Deployer
composer require deployer/deployer --dev

# Create deploy.php
php vendor/bin/dep init

# Deploy
dep deploy production
```

---

## Environment Configuration

### Nginx Configuration

Create: `/etc/nginx/sites-available/artika`

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/artika/public;

    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Logs
    access_log /var/log/nginx/artika-access.log;
    error_log /var/log/nginx/artika-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/artika /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

#  Auto-renewal
sudo certbot renew --dry-run
```

### PHP-FPM Configuration

Edit: `/etc/php/8.2/fpm/pool.d/www.conf`

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 15
pm.max_requests = 500
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

---

## Security Hardening

### 1. Firewall (UFW)

```bash
# Enable UFW
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 2. Fail2Ban (Against Brute Force)

```bash
# Install
sudo apt install fail2ban -y

# Configure
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 3. Security Checklist

- ✅ Set `APP_DEBUG=false` in production
- ✅ Use HTTPS (SSL certificate)
- ✅ Strong database passwords
- ✅ Disable directory listing
- ✅ Hide PHP version: `expose_php = Off` in php.ini
- ✅ Limit file upload size
- ✅ Enable CSRF protection (Laravel default)
- ✅ Keep dependencies updated
- ✅ Regular security audits

### 4. Rate Limiting

Laravel default: 60 requests/minute. Adjust in `RouteServiceProvider.php`:

```php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

---

## Performance Optimization

### 1. Laravel Optimizations

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize composer autoloader
composer install --optimize-autoloader --no-dev

# Optimize application
php artisan optimize
```

### 2. Database Optimization

```sql
-- Add indexes
ALTER TABLE products ADD INDEX idx_barcode (barcode);

-- Analyze tables
ANALYZE TABLE products, stocks, transactions;
```

### 3. Nginx Optimization

```nginx
# Enable gzip compression
gzip on;
gzip_vary on;
gzip_types text/plain text/css application/json application/javascript;

# Browser caching
location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
    expires 365d;
    add_header Cache-Control "public, immutable";
}
```

### 4. Redis Caching

```php
// Cache categories
$categories = Cache::remember('categories', 3600, fn() => Category::all());

// Cache product list
$products = Cache::remember('products:all', 1800, fn() => Product::with('category')->get());
```

---

## Monitoring & Maintenance

### 1. Queue Workers (Supervisor)

Create: `/etc/supervisor/conf.d/artika-worker.conf`

```ini
[program:artika-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/artika/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=deploy
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/artika/storage/logs/worker.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start artika-worker:*
```

### 2. Logs

```bash
# Laravel logs
tail -f /var/www/artika/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/artika-error.log

# PHP-FPM logs
tail -f /var/log/php8.2-fpm.log
```

### 3. Monitoring Tools

- **Laravel Telescope** (dev only)
- **Laravel Pulse** untuk monitoring
- **New Relic / Datadog** for APM
- **Uptime monitoring:** UptimeRobot, Pingdom

---

## Backup Strategy

### 1. Database Backup

**Daily Automated Backup:**

Create: `/home/deploy/backup-db.sh`

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/deploy/backups"
DB_NAME="artika_production"

mkdir -p $BACKUP_DIR

mysqldump -u artika_user -psecure_password $DB_NAME | gzip > $BACKUP_DIR/artika_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -name "artika_*.sql.gz" -mtime +30 -delete
```

```bash
chmod +x /home/deploy/backup-db.sh

# Add to cron (daily at 2 AM)
crontab -e
0 2 * * * /home/deploy/backup-db.sh
```

### 2. Application Backup

```bash
# Backup files
tar -czf artika_app_$(date +%Y%m%d).tar.gz /var/www/artika

# Upload to S3/cloud storage (optional)
```

### 3. Backup Verification

Test restore monthly:
```bash
gunzip < artika_20260109.sql.gz | mysql -u root -p artika_test
```

---

## Deployment Checklist

### Pre-Deployment

- [ ] Code reviewed dan tested
- [ ] Database migrations tested
- [ ] `.env` configured correctly
- [ ] Dependencies updated
- [ ] Assets compiled (`npm run build`)
- [ ] Backup created

### Deployment

- [ ] Put application in maintenance mode
- [ ] Pull latest code
- [ ] Run `composer install --no-dev`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear caches
- [ ] Restart queue workers
- [ ] Test critical functions
- [ ] Disable maintenance mode

### Post-Deployment

- [ ] Verify homepage loads
- [ ] Test login
- [ ] Test POS transaction
- [ ] Check logs untuk errors
- [ ] Monitor performance
- [ ] Notify stakeholders

---

## Zero-Downtime Deployment

Use **Deployer** atau **Envoyer** untuk zero-downtime:

```bash
# Example with Deployer
dep deploy production
```

Symlink strategy:
```
/var/www/artika
├── current -> releases/20260109_120000
├── releases/
│   ├── 20260109_120000/
│   └── 20260108_110000/
└── shared/
    ├── storage/
    └── .env
```

---

## Rollback Procedure

```bash
# With Deployer
dep rollback production

# Manual rollback
cd /var/www/artika
ln -nfs releases/previous_release current
sudo systemctl reload php8.2-fpm
```

---

## Troubleshooting

### 500 Internal Server Error

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Nginx error log: `/var/log/nginx/artika-error.log`
3. Check permissions: `storage/` dan `bootstrap/cache/`
4. Run `php artisan config:clear`

### Database Connection Error

1. Check MySQL is running: `sudo systemctl status mysql`
2. Verify credentials in `.env`
3. Test connection: `mysql -u artika_user -p artika_production`

### Queue Not Processing

1. Check supervisor status: `sudo supervisorctl status`
2. Restart workers: `sudo supervisorctl restart artika-worker:*`
3. Check queue logs: `storage/logs/worker.log`

---

## Scaling Considerations

### Horizontal Scaling

- Load balancer (HAProxy, AWS ELB)
- Multiple app servers
- Centralized database (RDS)
- Shared session storage (Redis)
- CDN for assets

### Vertical Scaling

- Upgrade server resources (CPU, RAM)
- Optimize database queries
- Use caching extensively
- Database read replicas

---

**Deploy with Confidence! 🚀**

**Version:** 2.0  
**Last Updated:** 2026-01-09
