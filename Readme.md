Sistem kehadiran digital berbasis QR Code untuk manajemen event dan buku tamu dengan Laravel 11 + Filament 3 + PostgreSQL.

---

## 📋 Table of Contents

- [System Overview](#system-overview)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Schema](#database-schema)
- [User Roles & Permissions](#user-roles--permissions)
- [Key Features Documentation](#key-features-documentation)
- [Deployment](#deployment)
- [Maintenance](#maintenance)
- [Troubleshooting](#troubleshooting)
- [API Documentation](#api-documentation)
- [Security](#security)
- [Credits](#credits)

---

## 🎯 System Overview

Aplikasi ini mengelola 2 jenis kehadiran:

1. **Event Check-in**: Kehadiran peserta event dengan roster pre-registered
2. **Buku Tamu Permanen**: Kunjungan tamu umum tanpa registrasi sebelumnya

### Architecture
```
┌─────────────────────────────────────────────────────────────┐
│                    Nginx Reverse Proxy                       │
│              https://absen.ikp.my.id (SSL/TLS)              │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   Docker Container (PHP 8.2)                 │
│  ┌───────────────────────────────────────────────────────┐  │
│  │             Laravel 11 Application                     │  │
│  │  - Filament 3 (Admin Panel)                           │  │
│  │  - Livewire 3 (Reactive Components)                   │  │
│  │  - SimpleSoftwareIO/SimpleQrCode (QR Generation)      │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│              PostgreSQL 16 Database (Docker)                 │
│  - events, event_participants, attendances                   │
│  - guest_visits, organizations, users                        │
└─────────────────────────────────────────────────────────────┘
```

---

## 🛠 Tech Stack

### Backend
- **PHP**: 8.2
- **Laravel**: 11.x
- **Filament**: 3.3.x (Admin Panel Framework)
- **Livewire**: 3.x (Frontend Reactivity)

### Database
- **PostgreSQL**: 16.x

### Frontend
- **TailwindCSS**: 3.x
- **Alpine.js**: 3.x (via Livewire)

### Infrastructure
- **Docker**: Container orchestration
- **Nginx**: Reverse proxy
- **Certbot**: SSL/TLS certificates

### Key Packages
```json
{
  "filament/filament": "^3.3",
  "simplesoftwareio/simple-qrcode": "^4.2",
  "maatwebsite/excel": "^3.1",
  "anourvalar/eloquent-serialize": "^1.2"
}
```

---

## ✨ Features

### 1. Event Management
- ✅ Create/edit/delete events
- ✅ Multiple event types: Rekreasi, Rapat, Kajian, Training, Lainnya
- ✅ Event date ranges (start date - end date)
- ✅ Unique event codes (auto-generated)
- ✅ QR code generation per event
- ✅ Clone event feature (duplicate with new dates)

### 2. Roster Management (Event Participants)
- ✅ Bulk import via CSV (Nama, HP, Organisasi, Tipe Org)
- ✅ Manual add participants
- ✅ Link participants to organizations
- ✅ Export roster to Excel

### 3. Attendance System
- ✅ QR code scan check-in
- ✅ Manual attendance entry (admin/operator)
- ✅ Check-in timestamp (date + time)
- ✅ Attendance status tracking
- ✅ Export attendance to Excel/CSV
- ✅ Filter by event, date, organization

### 4. Buku Tamu Permanen
- ✅ Single QR code for all guest visits (valid 1-2 years)
- ✅ Multi-day visit support (start date - end date)
- ✅ Auto-calculate visit duration (days)
- ✅ Guest information: Nama, HP, Organisasi, Agenda, Lokasi
- ✅ Group visit support (jumlah rombongan)
- ✅ Export to Excel with duration columns
- ✅ Statistics dashboard (today, week, month, total)

### 5. Organization Management
- ✅ Manage organizations (PRM, PCM, PDM, PWM, PRA, dll)
- ✅ Active/inactive status
- ✅ Link to events and participants

### 6. User Management & Permissions
- ✅ 3 roles: Admin, Operator, Viewer
- ✅ Permission matrix (create, edit, delete, view)
- ✅ User-specific event access
- ✅ Password reset functionality

### 7. Admin Features
- ✅ Dashboard with statistics widgets
- ✅ Selective database reset (per-table)
- ✅ Double password confirmation for destructive actions
- ✅ Export all data to Excel
- ✅ QR code download (PNG, high-res)

### 8. Branding
- ✅ Logo Muhammadiyah
- ✅ Custom headers: "PIMPINAN RANTING MUHAMMADIYAH WAGE - CABANG SEPANJANG DAERAH SIDOARJO"
- ✅ "Powered by ICMI" footer
- ✅ Blue color scheme (Muhammadiyah brand)

---

## 📦 Installation

### Prerequisites
- Docker & Docker Compose installed
- Git
- Domain with SSL certificate (or use Let's Encrypt)

### Step 1: Clone Repository
```bash
cd ~
git clone <repository-url> qr-attendance
cd qr-attendance
```

### Step 2: Environment Setup
```bash
# Copy .env.example
cp .env.example .env

# Edit .env
nano .env
```

**Critical .env variables:**
```env
APP_NAME="QR Attendance PRM WG"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://absen.ikp.my.id

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=qr_attendance
DB_USERNAME=qr_user
DB_PASSWORD=<secure-password>

SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 3: Start Docker Containers
```bash
docker-compose up -d
```

### Step 4: Install Dependencies
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
```

### Step 5: Run Migrations
```bash
docker-compose exec app php artisan migrate
```

### Step 6: Create Admin User
```bash
docker-compose exec app php artisan tinker

# Inside Tinker:
\App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'admin@prmwg.com',
    'password' => \Hash::make('admin123'),
    'role' => 'admin',
    'can_delete' => true,
    'can_create_event' => true,
    'can_manage_users' => true,
]);
```

### Step 7: Upload Logo Muhammadiyah
```bash
# From local machine:
scp path/to/logo-muhammadiyah.png user@server:~/qr-attendance/app/public/images/

# On server:
sudo chown www-data:www-data app/public/images/logo-muhammadiyah.png
```

### Step 8: Optimize & Cache
```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan filament:optimize
```

---

## ⚙️ Configuration

### Docker Compose Configuration

**docker-compose.yml:**
```yaml
services:
  app:
    image: php:8.2-fpm
    ports:
      - "1102:9000"
    volumes:
      - ./app:/var/www/html
    environment:
      - PHP_UPLOAD_MAX_FILESIZE=10M
      - PHP_POST_MAX_SIZE=10M
      
  db:
    image: postgres:16
    environment:
      POSTGRES_DB: qr_attendance
      POSTGRES_USER: qr_user
      POSTGRES_PASSWORD: <password>
    volumes:
      - pgdata:/var/lib/postgresql/data
      
  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
```

### Nginx Reverse Proxy

**nginx.conf:**
```nginx
server {
    listen 443 ssl http2;
    server_name absen.ikp.my.id;
    
    ssl_certificate /etc/nginx/ssl/fullchain.pem;
    ssl_certificate_key /etc/nginx/ssl/privkey.pem;
    
    location / {
        proxy_pass http://172.93.186.163:1102;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### PHP Extensions Required
- pdo_pgsql
- gd (for QR codes)
- imagick (optional, for high-quality QR)
- zip (for Excel export)
- intl

---

## 🗄️ Database Schema

### Core Tables

**users**
```sql
- id (uuid, PK)
- name (string)
- email (string, unique)
- password (string)
- role (enum: admin, operator, viewer)
- can_create_event (boolean)
- can_delete (boolean)
- can_manage_users (boolean)
- created_at, updated_at
```

**organizations**
```sql
- id (uuid, PK)
- name (string)
- type (string: PRM, PCM, PDM, etc)
- is_active (boolean)
- created_at, updated_at
```

**events**
```sql
- id (uuid, PK)
- name (string)
- event_code (string, unique, indexed)
- event_type (enum: rekreasi, rapat, kajian, training, other)
- description (text, nullable)
- location (string, nullable)
- date_start (date)
- date_end (date, nullable)
- created_by (uuid, FK → users.id)
- created_at, updated_at
```

**event_participants** (Roster)
```sql
- id (uuid, PK)
- event_id (uuid, FK → events.id, cascade)
- full_name (string)
- phone (string, indexed)
- organization_id (uuid, FK → organizations.id, nullable)
- organization_name (string, nullable)
- organization_type (string, nullable)
- created_at, updated_at

- INDEX: (event_id, phone)
```

**attendances**
```sql
- id (uuid, PK)
- event_id (uuid, FK → events.id, cascade)
- participant_id (uuid, FK → event_participants.id, nullable)
- full_name (string)
- phone (string, indexed)
- organization_name (string, nullable)
- checked_in_at (timestamp, indexed)
- is_manual_entry (boolean, default: false)
- created_at, updated_at

- UNIQUE: (event_id, phone)
- INDEX: (event_id, checked_in_at)
```

**guest_visits** (Buku Tamu)
```sql
- id (uuid, PK)
- full_name (string)
- phone (string, indexed)
- organization_name (string, nullable)
- organization_type (string, nullable)
- agenda (string, nullable)
- location (string, nullable)
- group_count (integer, default: 1)
- notes (text, nullable)
- visited_at (timestamp, indexed)
- visit_end_date (date, nullable)
- duration_days (integer, nullable)
- created_at, updated_at

- INDEX: (visited_at, organization_name)
```

### Foreign Key Relationships
```
users (1) ──< events (many) [created_by]
events (1) ──< event_participants (many) [event_id]
events (1) ──< attendances (many) [event_id]
event_participants (1) ──< attendances (many) [participant_id]
organizations (1) ──< event_participants (many) [organization_id]
```

---

## 👥 User Roles & Permissions

### Permission Matrix

| Feature | Admin | Operator | Viewer |
|---------|-------|----------|--------|
| View Dashboard | ✅ | ✅ | ✅ |
| Create Event | ✅ | ✅ | ❌ |
| Edit Event | ✅ | ✅ | ❌ |
| Delete Event | ✅ | ❌ | ❌ |
| Import Roster | ✅ | ✅ | ❌ |
| Manual Check-in | ✅ | ✅ | ❌ |
| View Attendance | ✅ | ✅ | ✅ |
| Export Excel | ✅ | ✅ | ✅ |
| Manage Organizations | ✅ | ✅ | ❌ |
| Manage Users | ✅ | ❌ | ❌ |
| Reset Database | ✅ | ❌ | ❌ |
| View Buku Tamu | ✅ | ✅ | ✅ |
| Download QR | ✅ | ✅ | ❌ |

### Creating Users

**Via Tinker:**
```bash
docker-compose exec app php artisan tinker

# Admin
\App\Models\User::create([
    'name' => 'Admin Name',
    'email' => 'admin@example.com',
    'password' => \Hash::make('password'),
    'role' => 'admin',
    'can_create_event' => true,
    'can_delete' => true,
    'can_manage_users' => true,
]);

# Operator
\App\Models\User::create([
    'name' => 'Operator Name',
    'email' => 'operator@example.com',
    'password' => \Hash::make('password'),
    'role' => 'operator',
    'can_create_event' => true,
    'can_delete' => false,
    'can_manage_users' => false,
]);

# Viewer
\App\Models\User::create([
    'name' => 'Viewer Name',
    'email' => 'viewer@example.com',
    'password' => \Hash::make('password'),
    'role' => 'viewer',
    'can_create_event' => false,
    'can_delete' => false,
    'can_manage_users' => false,
]);
```

---

## 🔑 Key Features Documentation

### 1. Event Check-in Flow

#### Create Event
1. Admin/Operator: **Events → Create Event**
2. Fill: Name, Type, Location, Date Range
3. System auto-generates unique `event_code` (e.g., `EV-1234567890`)
4. QR Code generated automatically

#### Import Roster
1. Open Event → **Roster Peserta tab**
2. Click **Import CSV**
3. Upload CSV with columns: `Nama`, `HP`, `Organisasi` (optional), `Tipe Organisasi` (optional)
4. System validates & imports

**CSV Format:**
```csv
Nama,HP,Organisasi,Tipe Organisasi
Ahmad Fauzi,081234567890,PCM Taman,PCM
Siti Aisyah,082345678901,PRM Wage,PRM
```

#### Check-in Process
1. User scans QR code → Opens `/event/{event_code}/checkin`
2. Form auto-fills if phone matches roster
3. Submit → Record saved to `attendances` table
4. Success page with timestamp

#### Manual Check-in (Admin/Operator)
1. **Attendance → Create Attendance**
2. Select Event
3. Fill: Name, HP (auto-suggest from roster)
4. `is_manual_entry = true`

### 2. Buku Tamu Flow

#### Setup
1. Admin: **QR Code Buku Tamu → Download QR**
2. Print & tempel di resepsionis

#### Guest Check-in
1. Scan QR → Opens `/tamu`
2. Fill form:
   - Nama Lengkap *
   - No HP *
   - Organisasi (optional, with datalist autocomplete)
   - Tipe Organisasi (dropdown)
   - Agenda/Keperluan
   - Lokasi Kunjungan
   - **Sampai Tanggal** (for multi-day visits)
   - Jumlah Rombongan (default: 1)
3. Submit → Auto-calculate `duration_days`
4. Success page

#### Export Guest Visits
1. **Buku Tamu Permanen → Select rows**
2. **Bulk Actions → Export Excel**
3. Choose date range
4. Download Excel with columns:
   - No, Tanggal & Jam, Sampai Tanggal, Durasi (Hari)
   - Nama, HP, Organisasi, Tipe, Agenda, Lokasi, Rombongan

### 3. Selective Database Reset

**Admin only feature** for cleaning up test/old data.

#### Usage:
1. **Pengaturan → Reset Database**
2. Select tables to reset:
   - ☑️ Buku Tamu Permanen (guest_visits)
   - ☑️ Data Attendance (attendances only)
   - ☑️ Roster Peserta (event_participants only)
   - ☑️ Semua Events (+ cascade delete roster & attendance)
3. Enter password **twice** for confirmation
4. Click **Reset Database yang Dipilih**

**Example Use Cases:**
- Reset only Guest Visits after trial period
- Clear Attendance data without deleting Events/Roster
- Completely reset all Events for new semester

**Safety:**
- Double password confirmation
- Shows record counts before delete
- Transaction rollback on error
- Foreign key cascade handled automatically

---

## 🚀 Deployment

### Production Checklist

**Before Deploy:**
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate strong `APP_KEY`
- [ ] Use secure database password
- [ ] Configure SSL certificate
- [ ] Set up daily backups
- [ ] Test all QR codes
- [ ] Upload logo Muhammadiyah

**Deploy Steps:**
```bash
# 1. Pull latest code
cd ~/qr-attendance
git pull origin main

# 2. Install dependencies
docker-compose exec app composer install --no-dev --optimize-autoloader

# 3. Run migrations
docker-compose exec app php artisan migrate --force

# 4. Clear & optimize cache
docker-compose exec app php artisan optimize:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan filament:optimize

# 5. Set permissions
sudo chown -R www-data:www-data app/storage app/bootstrap/cache app/public

# 6. Restart services
docker-compose restart app
```

### SSL Certificate Setup (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate certificate
sudo certbot --nginx -d absen.ikp.my.id

# Auto-renewal (cron job)
sudo crontab -e
# Add: 0 3 * * * certbot renew --quiet
```

### Backup Strategy

**Daily Database Backup (Cron):**
```bash
#!/bin/bash
# /root/backup-qr-db.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/root/backups/qr-attendance"
mkdir -p $BACKUP_DIR

docker-compose exec -T db pg_dump -U qr_user qr_attendance > $BACKUP_DIR/db_$DATE.sql

# Keep only last 30 days
find $BACKUP_DIR -name "db_*.sql" -mtime +30 -delete

# Compress old backups
find $BACKUP_DIR -name "db_*.sql" -mtime +7 -exec gzip {} \;
```

**Crontab:**
```bash
0 2 * * * /root/backup-qr-db.sh
```

**Restore from Backup:**
```bash
# Stop app
docker-compose stop app

# Restore database
cat /root/backups/qr-attendance/db_20260115_020000.sql | docker-compose exec -T db psql -U qr_user qr_attendance

# Start app
docker-compose start app
```

---

## 🔧 Maintenance

### Daily Tasks
- ✅ Check dashboard for errors
- ✅ Monitor attendance submissions
- ✅ Export daily guest visits

### Weekly Tasks
- ✅ Review user activity logs
- ✅ Clean up old test events
- ✅ Check disk space

### Monthly Tasks
- ✅ Database backup verification
- ✅ Security updates (`composer update`)
- ✅ Review & archive old events
- ✅ Export monthly reports

### Common Commands

**Clear All Cache:**
```bash
docker-compose exec app php artisan optimize:clear
```

**Reset Failed Jobs:**
```bash
docker-compose exec app php artisan queue:flush
```

**View Logs:**
```bash
docker-compose logs app --tail=100 -f
```

**Check Database Connection:**
```bash
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

**Optimize Performance:**
```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app composer dump-autoload --optimize
```

---

## 🐛 Troubleshooting

### Issue: Checkbox & Bulk Actions Hilang di Table

**Cause:** Mixed Content Error (HTTP assets loaded on HTTPS page)

**Fix:**
```bash
# Ensure APP_URL uses HTTPS
echo "APP_URL=https://absen.ikp.my.id" >> app/.env

# Force HTTPS in AppServiceProvider
# Already configured in app/Providers/AppServiceProvider.php

# Clear cache
docker-compose exec app php artisan config:clear
docker-compose restart app
```

### Issue: QR Code Download Error (Imagick)

**Cause:** Imagick extension not installed

**Fix Option 1 (Use GD - Recommended):**
```bash
# Already handled in routes/web.php
# QR codes use GD backend by default
```

**Fix Option 2 (Install Imagick):**
```bash
docker-compose exec app apt-get update
docker-compose exec app apt-get install -y libmagickwand-dev imagemagick
docker-compose exec app pecl install imagick
docker-compose exec app docker-php-ext-enable imagick
docker-compose restart app
```

### Issue: 500 Error Setelah Deploy

**Debug Steps:**
```bash
# 1. Check logs
docker-compose logs app --tail=50

# 2. Enable debug mode temporarily
docker-compose exec app sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env
docker-compose exec app php artisan config:clear

# 3. Check permissions
docker-compose exec app ls -la storage/logs
sudo chown -R www-data:www-data app/storage

# 4. Clear all cache
docker-compose exec app php artisan optimize:clear
```

### Issue: Database Connection Failed

**Check:**
```bash
# 1. Verify DB container running
docker-compose ps

# 2. Check credentials in .env
docker-compose exec app cat .env | grep DB_

# 3. Test connection
docker-compose exec db psql -U qr_user -d qr_attendance -c "\dt"
```

### Issue: Slow Performance

**Optimize:**
```bash
# 1. Cache configs
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# 2. Optimize Composer autoload
docker-compose exec app composer dump-autoload --optimize

# 3. Check PostgreSQL indexes
docker-compose exec db psql -U qr_user -d qr_attendance -c "
SELECT schemaname, tablename, indexname 
FROM pg_indexes 
WHERE schemaname = 'public' 
ORDER BY tablename, indexname;
"

# 4. Add missing indexes (if needed)
docker-compose exec app php artisan tinker
>>> Schema::table('attendances', function($table) {
      $table->index('checked_in_at');
  });
```

---

## 🌐 API Documentation

### Public Endpoints

**Event Check-in Form:**
```
GET /event/{event_code}/checkin
- Returns: HTML form
- No auth required
```

**Event Check-in Submit:**
```
POST /event/{event_code}/checkin
Body: {
  full_name: string (required),
  phone: string (required),
  organization_name: string (optional)
}
- Returns: Redirect to success page
```

**Guest Check-in Form:**
```
GET /tamu
- Returns: HTML form
```

**Guest Check-in Submit:**
```
POST /tamu
Body: {
  full_name: string (required),
  phone: string (required),
  organization_name: string (optional),
  organization_type: string (optional),
  agenda: string (optional),
  location: string (optional),
  group_count: integer (default: 1),
  visit_end_date: date (optional, format: Y-m-d)
}
- Returns: Redirect to success page
```

### Admin Endpoints (Authenticated)

**All admin routes require login:**
```
/admin/*
- Protected by Filament authentication middleware
```

---

## 🔒 Security

### Best Practices Implemented

1. **Authentication:**
   - Session-based auth via Filament
   - Password hashing with bcrypt
   - CSRF protection on all forms

2. **Authorization:**
   - Role-based access control (RBAC)
   - Permission checks on destructive actions
   - Double password confirmation for database reset

3. **Input Validation:**
   - Server-side validation on all forms
   - Phone number format validation
   - Unique constraint on event codes
   - Prevent duplicate check-ins (unique per event+phone)

4. **SQL Injection Prevention:**
   - Eloquent ORM with parameter binding
   - Prepared statements for raw queries

5. **XSS Prevention:**
   - Blade templating auto-escapes output
   - CSP headers configured

6. **HTTPS Enforcement:**
   - Force HTTPS in production
   - HSTS headers
   - SSL certificate auto-renewal

### Security Checklist

- [ ] Change default admin password
- [ ] Use strong database password (20+ chars)
- [ ] Enable firewall (UFW)
- [ ] Regular security updates
- [ ] Monitor failed login attempts
- [ ] Regular backup testing
- [ ] Limit SSH access (key-based only)

### Recommended Firewall Rules
```bash
# Allow SSH, HTTP, HTTPS only
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## 📊 Monitoring

### Key Metrics to Monitor

1. **Application Health:**
   - Response time < 200ms (avg)
   - Error rate < 0.1%
   - Uptime > 99.9%

2. **Database Performance:**
   - Query time < 50ms (avg)
   - Active connections < 80% of max
   - Disk usage < 80%

3. **Business Metrics:**
   - Daily active events
   - Total check-ins per day
   - Guest visits per day

### Simple Monitoring Script
```bash
#!/bin/bash
# /root/monitor-qr.sh

# Check if containers are running
docker-compose ps | grep -q "Up" || echo "⚠️ Containers down!"

# Check disk space
DISK_USAGE=$(df -h / | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "⚠️ Disk usage: ${DISK_USAGE}%"
fi

# Check database size
DB_SIZE=$(docker-compose exec -T db psql -U qr_user -d qr_attendance -t -c "
SELECT pg_size_pretty(pg_database_size('qr_attendance'));
" | tr -d ' ')
echo "📊 Database size: $DB_SIZE"

# Check logs for errors
ERRORS=$(docker-compose logs app --since 24h 2>&1 | grep -i error | wc -l)
if [ $ERRORS -gt 10 ]; then
    echo "⚠️ Errors in last 24h: $ERRORS"
fi
```

---

## 🎨 Customization Guide

### Change Branding

**Update Logo:**
```bash
# Replace logo file
sudo cp new-logo.png app/public/images/logo-muhammadiyah.png
```

**Update Organization Name:**
```bash
# Edit views
find app/resources/views -type f -name "*.blade.php" \
  -exec sed -i 's/PIMPINAN RANTING MUHAMMADIYAH WAGE/YOUR ORG NAME/g' {} \;
```

**Change Color Scheme:**
```php
// app/Providers/Filament/AdminPanelProvider.php

->colors([
    'primary' => Color::Blue, // Change to your brand color
])
```

### Add Custom Event Type
```bash
# 1. Update migration
# database/migrations/*_create_events_table.php

$table->enum('event_type', [
    'rekreasi', 'rapat', 'kajian', 'training', 'other',
    'seminar' // ADD NEW TYPE
])->default('other');

# 2. Run migration
docker-compose exec app php artisan migrate

# 3. Update EventResource form
# app/Filament/Resources/EventResource.php

Forms\Components\Select::make('event_type')
    ->options([
        // ... existing
        'seminar' => 'Seminar', // ADD HERE
    ])
```

---

## 📝 Credits

**Developed by:** ICMI (Ikatan Cendekiawan Muslim se-Indonesia)

**For:** Pimpinan Ranting Muhammadiyah (PRM) Wage, Cabang Sepanjang, Daerah Sidoarjo

**Tech Stack:**
- Laravel (Taylor Otwell)
- Filament (Dan Harrin)
- SimpleSoftwareIO/SimpleQrCode
- Maatwebsite Excel

**Server Infrastructure:**
- Hosted at: absen.ikp.my.id
- Database: PostgreSQL
- Containerized with Docker

---

## 📞 Support

**For technical issues:**
- Check logs: `docker-compose logs app`
- Review this README
- Contact system administrator

**For feature requests:**
- Document requirements clearly
- Provide use case examples
- Submit via proper channels

---

## 📜 License

Proprietary - © 2026 PRM Wage Muhammadiyah

All rights reserved. Unauthorized copying, modification, distribution, or use of this software is strictly prohibited.

---

## 🔄 Changelog

### v1.0.0 (January 2026)
- ✅ Initial release
- ✅ Event management with QR codes
- ✅ Roster import/export
- ✅ Attendance tracking
- ✅ Buku Tamu Permanen with multi-day support
- ✅ Selective database reset
- ✅ Excel export functionality
- ✅ Role-based access control
- ✅ Muhammadiyah branding

---

**Last Updated:** January 15, 2026  
**Version:** 1.0.0  
**Maintainer:** System Administrator
