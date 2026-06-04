# ISWARA — Integrated Solution Waste Range
## TPS 3R Sapuh Jagat, Desa Gulingan, Mengwi

> *"Transformasi Digital Bertahap Menuju Smart Waste Management"*

---

## Daftar Isi

- [Gambaran Umum Proyek](#gambaran-umum-proyek)
- [Stack Teknologi](#stack-teknologi)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Struktur Folder](#struktur-folder)
- [Modul Sistem (9 Modul, 45 Sub-Modul)](#modul-sistem)
- [Setup & Instalasi](#setup--instalasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Database & Migrasi](#database--migrasi)
- [API Conventions](#api-conventions)
- [Frontend Conventions](#frontend-conventions)
- [GIS & PostGIS](#gis--postgis)
- [Roles & Hak Akses (RBAC)](#roles--hak-akses-rbac)
- [Testing](#testing)
- [Deployment](#deployment)
- [Panduan untuk Agent AI](#panduan-untuk-agent-ai)

---

## Gambaran Umum Proyek

ISWARA adalah platform digital terpadu berbasis web dan mobile untuk mendukung seluruh proses pengelolaan sampah TPS 3R Sapuh Jagat. Sistem ini mengintegrasikan:

- Manajemen penerima manfaat & SDM
- Pengangkutan sampah dengan tracking GPS & GIS
- Pengelolaan TPS 3R & pengomposan
- Bank sampah digital
- Sistem pembayaran & keuangan
- Keluhan & ticketing
- Dashboard TV, dashboard desa, dan dashboard manajemen
- Administrasi kelembagaan (surat, buku tamu)

**Disusun oleh:** Intercode.id Team  
**Referensi Dokumen:** `ISWARA_TPS_3R_SAPUH_JAGAT_GULINGAN_V3.docx`

---

## Stack Teknologi

| Layer | Teknologi |
|-------|-----------|
| Backend Framework | Laravel 13 |
| Frontend Framework | React.js + Inertia.js |
| Database | PostgreSQL 16+ dengan ekstensi PostGIS |
| Spatial / GIS | PostGIS 3.x |
| Styling | Tailwind CSS |
| Build Tool | Vite |
| Authentication | Laravel Sanctum (SPA) + Spatie Laravel Permission (RBAC) |
| Queue | Laravel Queue (database / Redis) |
| Real-time | Laravel Reverb (WebSocket) atau Pusher |
| Storage File | Laravel Storage (local/S3) |
| PDF Generation | DomPDF (barryvdh/laravel-dompdf) |
| QR Code | SimpleSoftwareIO/simple-qrcode |
| Maps/GIS (Frontend) | Leaflet.js atau MapLibre GL JS |
| Testing | Pest PHP (backend) + Vitest (frontend) |
| Containerisasi | Docker + Docker Compose |
| CI/CD | GitHub Actions |

---

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                         │
│  Web Browser (React + Inertia)  │  Mobile PWA / App Native  │
└──────────────────┬──────────────────────────────────────────┘
                   │ HTTP / WebSocket
┌──────────────────▼──────────────────────────────────────────┐
│                     LARAVEL 13 (Backend)                     │
│  Routes → Middleware → Controllers → Services → Models       │
│  ┌───────────┐  ┌──────────┐  ┌────────────┐  ┌──────────┐ │
│  │   RBAC    │  │  Queue   │  │  Events/   │  │  PDF /   │ │
│  │ (Spatie)  │  │  Jobs    │  │  Listeners │  │  QR Gen  │ │
│  └───────────┘  └──────────┘  └────────────┘  └──────────┘ │
└──────────────────┬──────────────────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────────────────┐
│              DATABASE LAYER (PostgreSQL + PostGIS)           │
│  Relational Tables  │  PostGIS Geometry  │  Full-Text Search │
└─────────────────────────────────────────────────────────────┘
```

---

## Struktur Folder

Berikut adalah struktur folder lengkap yang wajib diikuti oleh seluruh anggota tim dan agent AI.

```
iswara/
│
├── app/
│   ├── Console/
│   │   └── Commands/                    # Artisan commands (cron jobs, data seeding, dll)
│   │       ├── GenerateDailyStatistics.php
│   │       ├── GenerateMonthlyInvoices.php
│   │       └── SendPaymentReminders.php
│   │
│   ├── Exceptions/
│   │   └── Handler.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # Authentication controllers
│   │   │   │   ├── AuthController.php
│   │   │   │   └── ProfileController.php
│   │   │   │
│   │   │   ├── Master/                  # Modul 1: Master Data
│   │   │   │   ├── RoleController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── RegionController.php
│   │   │   │   ├── RoadController.php
│   │   │   │   ├── SpecialPlaceController.php
│   │   │   │   ├── BusinessTypeController.php
│   │   │   │   ├── PaymentMethodController.php
│   │   │   │   ├── CustomerCategoryController.php
│   │   │   │   ├── WasteTypeController.php
│   │   │   │   ├── ComplaintTypeController.php
│   │   │   │   └── AssetTypeController.php
│   │   │   │
│   │   │   ├── Customer/                # Modul 2: Penerima Manfaat & SDM
│   │   │   │   ├── CustomerController.php
│   │   │   │   ├── CustomerUserController.php
│   │   │   │   ├── WorkerController.php
│   │   │   │   └── WorkerAttendanceController.php
│   │   │   │
│   │   │   ├── Asset/                   # Modul 3: Aset & Armada
│   │   │   │   ├── AssetController.php
│   │   │   │   ├── FleetVehicleController.php
│   │   │   │   └── AssetMaintenanceController.php
│   │   │   │
│   │   │   ├── Pickup/                  # Modul 4: Pengangkutan
│   │   │   │   ├── PickupScheduleController.php
│   │   │   │   ├── SpecialWasteRequestController.php
│   │   │   │   ├── PickupRouteController.php
│   │   │   │   ├── VehicleTrackingController.php
│   │   │   │   ├── PickupActivityLogController.php
│   │   │   │   └── LandfillDisposalController.php
│   │   │   │
│   │   │   ├── Complaint/               # Modul 5: Keluhan & Ticketing
│   │   │   │   ├── ComplaintController.php
│   │   │   │   └── ComplaintHandlingController.php
│   │   │   │
│   │   │   ├── Finance/                 # Modul 6: Pembayaran & Keuangan
│   │   │   │   ├── MonthlyInvoiceController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   └── FinancialLedgerController.php
│   │   │   │
│   │   │   ├── Composting/              # Modul 7: TPS 3R & Pengomposan
│   │   │   │   ├── IncomingWasteLogController.php
│   │   │   │   ├── CompostBatchController.php
│   │   │   │   ├── CompostOutputController.php
│   │   │   │   └── CompostDistributionController.php
│   │   │   │
│   │   │   ├── WasteBank/               # Modul 8: Bank Sampah Digital
│   │   │   │   ├── WasteBankUnitController.php
│   │   │   │   ├── WasteBankDepositController.php
│   │   │   │   ├── WasteBankDepositDetailController.php
│   │   │   │   ├── WasteBankLedgerController.php
│   │   │   │   └── PlasticPickupRequestController.php
│   │   │   │
│   │   │   ├── Support/                 # Modul 9: Manajemen Pendukung
│   │   │   │   ├── MediaFileController.php
│   │   │   │   ├── NotificationController.php
│   │   │   │   ├── AuditLogController.php
│   │   │   │   ├── OperationalStatisticController.php
│   │   │   │   ├── DocumentGeneratorController.php
│   │   │   │   ├── CorrespondenceController.php
│   │   │   │   └── GuestBookController.php
│   │   │   │
│   │   │   └── Dashboard/               # Dashboard controllers
│   │   │       ├── DashboardTvController.php
│   │   │       ├── DashboardManagementController.php
│   │   │       └── DashboardVillageController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── CheckPermission.php      # Middleware cek permission RBAC
│   │   │   ├── AuditLogger.php          # Auto-log semua aksi penting
│   │   │   └── EnsureAccountActive.php  # Cek status akun aktif
│   │   │
│   │   └── Requests/                    # Form Request Validation
│   │       ├── Master/
│   │       ├── Customer/
│   │       ├── Asset/
│   │       ├── Pickup/
│   │       ├── Complaint/
│   │       ├── Finance/
│   │       ├── Composting/
│   │       ├── WasteBank/
│   │       └── Support/
│   │
│   ├── Models/
│   │   ├── Master/
│   │   │   ├── Role.php
│   │   │   ├── User.php
│   │   │   ├── Region.php               # HasPostGISGeometry trait
│   │   │   ├── Road.php                 # HasPostGISGeometry trait
│   │   │   ├── SpecialPlace.php
│   │   │   ├── BusinessType.php
│   │   │   ├── PaymentMethod.php
│   │   │   ├── CustomerCategory.php
│   │   │   ├── WasteType.php
│   │   │   ├── ComplaintType.php
│   │   │   └── AssetType.php
│   │   │
│   │   ├── Customer/
│   │   │   ├── Customer.php
│   │   │   ├── CustomerUser.php
│   │   │   ├── Worker.php
│   │   │   └── WorkerAttendance.php
│   │   │
│   │   ├── Asset/
│   │   │   ├── Asset.php
│   │   │   ├── FleetVehicle.php
│   │   │   └── AssetMaintenance.php
│   │   │
│   │   ├── Pickup/
│   │   │   ├── PickupSchedule.php
│   │   │   ├── SpecialWasteRequest.php
│   │   │   ├── PickupRoute.php
│   │   │   ├── VehicleTrackingLog.php
│   │   │   ├── PickupActivityLog.php
│   │   │   └── LandfillDisposal.php
│   │   │
│   │   ├── Complaint/
│   │   │   ├── Complaint.php
│   │   │   └── ComplaintHandling.php
│   │   │
│   │   ├── Finance/
│   │   │   ├── MonthlyInvoice.php
│   │   │   ├── Payment.php
│   │   │   └── FinancialLedger.php
│   │   │
│   │   ├── Composting/
│   │   │   ├── IncomingWasteLog.php
│   │   │   ├── CompostBatch.php
│   │   │   ├── CompostOutput.php
│   │   │   └── CompostDistribution.php
│   │   │
│   │   ├── WasteBank/
│   │   │   ├── WasteBankUnit.php
│   │   │   ├── WasteBankDeposit.php
│   │   │   ├── WasteBankDepositDetail.php
│   │   │   ├── WasteBankLedger.php
│   │   │   └── PlasticPickupRequest.php
│   │   │
│   │   └── Support/
│   │       ├── MediaFile.php
│   │       ├── Notification.php
│   │       ├── AuditLog.php
│   │       ├── OperationalStatistic.php
│   │       ├── DocumentTemplate.php
│   │       ├── GeneratedDocument.php
│   │       ├── Correspondence.php
│   │       └── GuestBook.php
│   │
│   ├── Services/                        # Business Logic Layer (WAJIB digunakan)
│   │   ├── Master/
│   │   ├── Customer/
│   │   │   └── CustomerService.php      # Generate QR Code, kode pelanggan, dll
│   │   ├── Asset/
│   │   ├── Pickup/
│   │   │   ├── PickupScheduleService.php
│   │   │   └── RouteOptimizationService.php  # Logika optimasi rute GIS
│   │   ├── Finance/
│   │   │   ├── InvoiceGeneratorService.php
│   │   │   └── LedgerService.php
│   │   ├── Composting/
│   │   ├── WasteBank/
│   │   ├── Support/
│   │   │   ├── AuditService.php         # Mencatat audit log otomatis
│   │   │   ├── StatisticAggregatorService.php
│   │   │   ├── DocumentGeneratorService.php  # Generate PDF surat
│   │   │   └── NotificationService.php
│   │   └── GIS/
│   │       └── GeoService.php           # PostGIS queries, proximity, polygon
│   │
│   ├── Jobs/                            # Queue Jobs
│   │   ├── GenerateMonthlyInvoicesJob.php
│   │   ├── AggregateStatisticsJob.php
│   │   ├── SendPaymentReminderJob.php
│   │   └── GenerateDocumentJob.php
│   │
│   ├── Events/                          # Laravel Events
│   │   ├── PaymentVerified.php
│   │   ├── ComplaintCreated.php
│   │   └── PickupCompleted.php
│   │
│   ├── Listeners/
│   │   ├── UpdateLedgerOnPayment.php
│   │   ├── NotifyOnComplaint.php
│   │   └── UpdateStatisticsOnPickup.php
│   │
│   ├── Traits/
│   │   ├── HasAuditLog.php              # Trait untuk auto-audit pada model
│   │   ├── HasPostGIS.php               # Trait untuk field geometri PostGIS
│   │   └── GeneratesCode.php            # Trait untuk auto-generate kode unik
│   │
│   ├── Enums/                           # PHP 8.1+ Backed Enums
│   │   ├── AccountStatus.php
│   │   ├── RegionType.php
│   │   ├── WasteCategory.php
│   │   ├── ComplaintStatus.php
│   │   ├── InvoiceStatus.php
│   │   ├── AttendanceStatus.php
│   │   ├── AuditActivityType.php
│   │   └── ...                          # Semua enum dari requirement
│   │
│   └── Policies/                        # Laravel Policies untuk RBAC
│       ├── CustomerPolicy.php
│       ├── PaymentPolicy.php
│       └── ...
│
├── bootstrap/
├── config/
│   ├── app.php
│   ├── database.php
│   ├── iswara.php                       # Konfigurasi custom ISWARA
│   └── postgis.php                      # Konfigurasi PostGIS
│
├── database/
│   ├── migrations/
│   │   ├── 0001_create_master_tables/   # Grup migrasi per modul
│   │   │   ├── 0001_01_create_roles_table.php
│   │   │   ├── 0001_02_create_users_table.php
│   │   │   ├── 0001_03_create_master_regions_table.php
│   │   │   ├── 0001_04_create_master_roads_table.php
│   │   │   ├── 0001_05_create_master_special_places_table.php
│   │   │   ├── 0001_06_create_master_business_types_table.php
│   │   │   ├── 0001_07_create_master_payment_methods_table.php
│   │   │   ├── 0001_08_create_master_customer_categories_table.php
│   │   │   ├── 0001_09_create_master_waste_types_table.php
│   │   │   ├── 0001_10_create_master_complaint_types_table.php
│   │   │   └── 0001_11_create_master_asset_types_table.php
│   │   ├── 0002_create_customer_tables/
│   │   ├── 0003_create_asset_tables/
│   │   ├── 0004_create_pickup_tables/
│   │   ├── 0005_create_complaint_tables/
│   │   ├── 0006_create_finance_tables/
│   │   ├── 0007_create_composting_tables/
│   │   ├── 0008_create_waste_bank_tables/
│   │   └── 0009_create_support_tables/
│   │
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── Master/
│   │   │   ├── RoleSeeder.php
│   │   │   ├── UserSeeder.php
│   │   │   ├── RegionSeeder.php         # Data wilayah/banjar Desa Gulingan
│   │   │   ├── WasteTypeSeeder.php
│   │   │   ├── CustomerCategorySeeder.php  # 13 kategori penerima manfaat
│   │   │   ├── ComplaintTypeSeeder.php
│   │   │   └── PaymentMethodSeeder.php
│   │   └── Demo/
│   │       └── DemoDataSeeder.php       # Data demo untuk development
│   │
│   └── factories/
│       ├── CustomerFactory.php
│       ├── WorkerFactory.php
│       └── ...
│
├── resources/
│   ├── js/
│   │   ├── app.jsx                      # Entry point React + Inertia
│   │   ├── bootstrap.js
│   │   │
│   │   ├── Components/                  # Reusable React components
│   │   │   ├── UI/                      # Komponen UI generik
│   │   │   │   ├── Button.jsx
│   │   │   │   ├── Modal.jsx
│   │   │   │   ├── DataTable.jsx
│   │   │   │   ├── StatusBadge.jsx
│   │   │   │   ├── QRCodeDisplay.jsx
│   │   │   │   └── ...
│   │   │   ├── Map/                     # Komponen GIS & Peta
│   │   │   │   ├── MapContainer.jsx     # Wrapper Leaflet/MapLibre
│   │   │   │   ├── CustomerMarker.jsx
│   │   │   │   ├── RoutePolyline.jsx
│   │   │   │   ├── RegionPolygon.jsx
│   │   │   │   └── VehicleTracker.jsx   # Real-time tracking
│   │   │   ├── Charts/                  # Komponen dashboard & statistik
│   │   │   │   ├── WasteVolumeChart.jsx
│   │   │   │   ├── PaymentRateChart.jsx
│   │   │   │   └── CompostProductionChart.jsx
│   │   │   └── Forms/                   # Form components per modul
│   │   │
│   │   ├── Layouts/
│   │   │   ├── AppLayout.jsx            # Layout utama (sidebar, topbar)
│   │   │   ├── AuthLayout.jsx           # Layout login
│   │   │   ├── DashboardTvLayout.jsx    # Layout untuk display TV publik
│   │   │   └── MobileLayout.jsx         # Layout untuk petugas lapangan
│   │   │
│   │   ├── Pages/                       # Inertia Pages (1 file = 1 route)
│   │   │   ├── Auth/
│   │   │   │   ├── Login.jsx
│   │   │   │   └── Profile.jsx
│   │   │   ├── Master/
│   │   │   │   ├── Roles/
│   │   │   │   ├── Users/
│   │   │   │   ├── Regions/
│   │   │   │   ├── Roads/
│   │   │   │   ├── SpecialPlaces/
│   │   │   │   ├── WasteTypes/
│   │   │   │   └── ...
│   │   │   ├── Customer/
│   │   │   ├── Asset/
│   │   │   ├── Pickup/
│   │   │   ├── Complaint/
│   │   │   ├── Finance/
│   │   │   ├── Composting/
│   │   │   ├── WasteBank/
│   │   │   ├── Support/
│   │   │   └── Dashboard/
│   │   │       ├── TvDisplay.jsx        # Dashboard TV publik (full screen)
│   │   │       ├── Management.jsx
│   │   │       └── Village.jsx
│   │   │
│   │   └── Hooks/                       # Custom React Hooks
│   │       ├── usePermission.js         # Cek hak akses RBAC di frontend
│   │       ├── useGeolocation.js        # GPS tracking
│   │       └── useRealtime.js           # WebSocket / Echo
│   │
│   ├── views/
│   │   ├── app.blade.php                # Root Inertia view
│   │   └── pdf/                         # Blade templates untuk PDF
│   │       ├── invoice.blade.php
│   │       ├── surat_template.blade.php
│   │       └── laporan_operasional.blade.php
│   │
│   └── css/
│       └── app.css
│
├── routes/
│   ├── web.php                          # Route utama Inertia (semua page routes)
│   ├── api.php                          # API routes (untuk mobile app)
│   └── channels.php                     # WebSocket channels
│
├── storage/
│   └── app/
│       ├── public/
│       │   ├── photos/                  # Foto pelanggan, petugas, aset
│       │   ├── documents/               # PDF generated (surat, invoice)
│       │   ├── qrcodes/                 # QR Code pelanggan
│       │   └── attachments/             # Lampiran keluhan, korespondensi
│       └── private/
│           └── reports/                 # Laporan rahasia
│
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── Master/
│   │   ├── Customer/
│   │   ├── Finance/
│   │   ├── Pickup/
│   │   └── ...
│   └── Unit/
│       ├── Services/
│       └── Models/
│
├── docs/                                # Dokumentasi tambahan
│   ├── README.md                        # File ini
│   ├── WORKFLOW.md                      # Panduan workflow pengembangan
│   ├── API.md                           # Dokumentasi API endpoint
│   ├── DATABASE.md                      # ERD dan skema database
│   ├── GIS.md                           # Panduan PostGIS & GIS
│   ├── RBAC.md                          # Matriks roles & permissions
│   ├── MODULES/                         # Dokumentasi per modul
│   │   ├── 01_MASTER_DATA.md
│   │   ├── 02_CUSTOMER_SDM.md
│   │   ├── 03_ASSET_FLEET.md
│   │   ├── 04_PICKUP.md
│   │   ├── 05_COMPLAINT.md
│   │   ├── 06_FINANCE.md
│   │   ├── 07_COMPOSTING.md
│   │   ├── 08_WASTE_BANK.md
│   │   └── 09_SUPPORT.md
│   └── AGENT_GUIDE.md                   # Panduan khusus untuk AI agent
│
├── .env.example
├── .gitignore
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
├── phpunit.xml
├── docker-compose.yml
└── Makefile                             # Shortcut perintah umum
```

---

## Modul Sistem

Sistem terdiri dari **9 Modul** dan **45 Sub-Modul**:

| No | Modul | Sub-Modul | Tabel Utama |
|----|-------|-----------|-------------|
| 1 | **Master Data & Autentifikasi** | 11 sub-modul | `roles`, `users`, `master_regions`, `master_roads`, `master_special_places`, `master_business_types`, `master_payment_methods`, `master_customer_categories`, `master_waste_types`, `master_complaint_types`, `master_asset_types` |
| 2 | **Penerima Manfaat & SDM** | 4 sub-modul | `customers`, `customer_users`, `workers`, `worker_attendances` |
| 3 | **Aset & Armada** | 3 sub-modul | `assets`, `fleet_vehicles`, `asset_maintenances` |
| 4 | **Pengangkutan Sampah** | 6 sub-modul | `pickup_schedules`, `special_waste_requests`, `pickup_routes`, `vehicle_tracking_logs`, `pickup_activity_logs`, `landfill_disposals` |
| 5 | **Keluhan & Ticketing** | 2 sub-modul | `complaints`, `complaint_handlings` |
| 6 | **Pembayaran & Keuangan** | 3 sub-modul | `monthly_invoices`, `payments`, `financial_ledger` |
| 7 | **TPS 3R & Pengomposan** | 4 sub-modul | `incoming_waste_logs`, `compost_batches`, `compost_outputs`, `compost_distributions` |
| 8 | **Bank Sampah Digital** | 5 sub-modul | `waste_bank_units`, `waste_bank_deposits`, `waste_bank_deposit_details`, `waste_bank_ledger`, `plastic_pickup_requests` |
| 9 | **Manajemen Pendukung** | 7 sub-modul | `media_files`, `notifications`, `audit_logs`, `operational_statistics`, `document_templates`, `generated_documents`, `correspondences`, `guest_books` |

---

## Setup & Instalasi

### Prasyarat

- PHP 8.3+
- Node.js 20+
- PostgreSQL 16+ dengan ekstensi PostGIS
- Composer 2.x
- Docker (opsional, direkomendasikan)

### 1. Clone Repository

```bash
git clone https://github.com/intercode-id/iswara.git
cd iswara
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai konfigurasi lokal (lihat bagian [Konfigurasi Environment](#konfigurasi-environment)).

### 4. Setup Database PostgreSQL + PostGIS

```bash
# Buat database
createdb iswara_db

# Aktifkan ekstensi PostGIS
psql -d iswara_db -c "CREATE EXTENSION IF NOT EXISTS postgis;"
psql -d iswara_db -c "CREATE EXTENSION IF NOT EXISTS postgis_topology;"
psql -d iswara_db -c "CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\";"

# Jalankan migrasi
php artisan migrate

# Jalankan seeder master data
php artisan db:seed --class=Master\\RoleSeeder
php artisan db:seed --class=Master\\WasteTypeSeeder
php artisan db:seed --class=Master\\CustomerCategorySeeder
php artisan db:seed --class=Master\\ComplaintTypeSeeder
```

### 5. Setup Storage Link & QR Code

```bash
php artisan storage:link
```

### 6. Build Frontend

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Server

```bash
php artisan serve
# atau
php artisan serve --host=0.0.0.0 --port=8000
```

### Menggunakan Docker

```bash
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

---

## Konfigurasi Environment

Salin `.env.example` ke `.env` dan sesuaikan nilai berikut:

```dotenv
APP_NAME="ISWARA TPS 3R Sapuh Jagat"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database PostgreSQL + PostGIS
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=iswara_db
DB_USERNAME=postgres
DB_PASSWORD=secret
DB_SCHEMA=public

# Queue (gunakan database untuk dev, redis untuk production)
QUEUE_CONNECTION=database

# File Storage
FILESYSTEM_DISK=local
# Untuk production, ganti ke s3

# Mail (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=

# WebSocket (pilih salah satu)
BROADCAST_CONNECTION=reverb
# atau: pusher

# Reverb (jika pakai Laravel Reverb)
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=localhost
REVERB_PORT=8080

# ISWARA Custom Config
ISWARA_TPS_NAME="TPS 3R Sapuh Jagat"
ISWARA_TPS_ADDRESS="Desa Gulingan, Mengwi, Badung, Bali"
ISWARA_DEFAULT_TIMEZONE="Asia/Makassar"
ISWARA_QR_CODE_SIZE=300
ISWARA_MAP_CENTER_LAT=-8.5455   # Koordinat pusat peta Desa Gulingan
ISWARA_MAP_CENTER_LNG=115.1760
ISWARA_MAP_DEFAULT_ZOOM=14
```

---

## Database & Migrasi

### Konvensi Penamaan Tabel

Semua tabel menggunakan **snake_case** dan **plural**. Tabel master diberi prefix `master_`:

| Jenis | Contoh |
|-------|--------|
| Tabel master | `master_regions`, `master_waste_types` |
| Tabel transaksi | `monthly_invoices`, `payments` |
| Tabel log | `audit_logs`, `vehicle_tracking_logs` |
| Tabel relasi | `customer_users`, `waste_bank_deposit_details` |

### Konvensi Kolom

- Primary key: `{nama_tabel_singular}_id` — tipe `BIGINT UNSIGNED AUTO INCREMENT` atau `UUID`
- Foreign key: mengikuti nama kolom yang direferensikan
- Timestamp: selalu ada `created_at` dan `updated_at`
- Soft delete: gunakan `deleted_at` hanya pada tabel yang membutuhkan history

### Kolom GIS (PostGIS)

Untuk field koordinat atau geometri, gunakan tipe PostGIS:

```php
// Di migration
$table->geography('location', 'POINT', 4326)->nullable();      // Titik koordinat
$table->geography('polygon_area', 'POLYGON', 4326)->nullable(); // Area wilayah
$table->geography('route_line', 'LINESTRING', 4326)->nullable(); // Rute jalan
```

### Menjalankan Migrasi

```bash
# Semua migrasi
php artisan migrate

# Rollback satu batch
php artisan migrate:rollback

# Reset & re-migrate (HATI-HATI di production!)
php artisan migrate:fresh --seed
```

---

## API Conventions

### URL Pattern

```
/api/v1/{modul}/{resource}
```

Contoh:
- `GET /api/v1/customers` — list pelanggan
- `POST /api/v1/customers` — buat pelanggan baru
- `GET /api/v1/customers/{id}` — detail pelanggan
- `PUT /api/v1/customers/{id}` — update pelanggan
- `DELETE /api/v1/customers/{id}` — hapus pelanggan

### Response Format

Semua response API menggunakan format berikut:

```json
{
  "success": true,
  "message": "Data berhasil disimpan",
  "data": { ... },
  "meta": {
    "page": 1,
    "per_page": 15,
    "total": 100
  }
}
```

### Error Response

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "field_name": ["Pesan error"]
  }
}
```

---

## Frontend Conventions

### Struktur Page (Inertia)

Setiap Inertia Page mengikuti pola berikut:

```jsx
// resources/js/Pages/Customer/Customers/Index.jsx
import AppLayout from '@/Layouts/AppLayout';
import { Head } from '@inertiajs/react';
import DataTable from '@/Components/UI/DataTable';
import usePermission from '@/Hooks/usePermission';

export default function CustomersIndex({ customers, filters }) {
  const { can } = usePermission();

  return (
    <AppLayout>
      <Head title="Penerima Manfaat" />
      
      {/* Konten halaman */}
    </AppLayout>
  );
}
```

### Cek Permissions di Frontend

```jsx
import usePermission from '@/Hooks/usePermission';

const { can, is } = usePermission();

// Cek permission
if (can('create-customers')) { ... }

// Cek role
if (is('admin')) { ... }
```

### Inertia Form

```jsx
import { useForm } from '@inertiajs/react';

const { data, setData, post, processing, errors } = useForm({
  full_name: '',
  phone_number: '',
});

const handleSubmit = (e) => {
  e.preventDefault();
  post(route('customers.store'));
};
```

---

## GIS & PostGIS

### Aturan Penggunaan Koordinat

- Semua koordinat menggunakan **WGS 84 (SRID 4326)**
- Latitude: Decimal(10,8) — kisaran Bali ≈ `-8.3` s/d `-8.9`
- Longitude: Decimal(11,8) — kisaran Bali ≈ `114.4` s/d `115.7`

### Query Contoh PostGIS

```php
// Cari pelanggan dalam radius 500 meter dari titik tertentu
Customer::whereRaw(
    "ST_DWithin(location::geography, ST_MakePoint(?, ?)::geography, ?)",
    [$longitude, $latitude, 500]
)->get();

// Cari pelanggan dalam polygon wilayah
Customer::whereRaw(
    "ST_Within(ST_MakePoint(longitude, latitude), ST_GeomFromGeoJSON(?))",
    [$region->polygon_geojson]
)->get();
```

### Data GeoJSON

- Polygon wilayah (`master_regions.polygon_geojson`) — format GeoJSON Polygon
- Polyline jalan (`master_roads.map_polyline`) — format GeoJSON LineString atau WKT

---

## Roles & Hak Akses (RBAC)

ISWARA menggunakan **Spatie Laravel Permission** untuk manajemen RBAC.

### Daftar Role

| Role | Keterangan |
|------|-----------|
| `admin` | Akses penuh ke semua fitur sistem |
| `operator_lapangan` | Akses modul pengangkutan & absensi |
| `kesling` | Akses monitoring lingkungan & keluhan |
| `penerima_manfaat` | Akses portal self-service pelanggan |
| `kolektor_iuran` | Akses modul pembayaran & tagihan |
| `kasir` | Akses pembayaran & verifikasi |
| `manajemen` | Akses dashboard & laporan |
| `kelian_adat` | Akses data wilayah adat & sampah khusus |
| `kelian_dinas` | Akses data wilayah dinas |

### Konvensi Penamaan Permission

Format: `{aksi}-{resource}` dalam huruf kecil dengan tanda hubung.

```
view-customers
create-customers
edit-customers
delete-customers
view-payments
verify-payments
...
```

Detail lengkap: lihat `docs/RBAC.md`

---

## Testing

### Menjalankan Tests

```bash
# Semua tests
php artisan test

# Test spesifik modul
php artisan test --filter=CustomerTest

# Dengan coverage
php artisan test --coverage
```

### Frontend Tests

```bash
npm run test
npm run test:coverage
```

---

## Deployment

Lihat `docs/DEPLOYMENT.md` untuk panduan deployment lengkap ke server production.

Checklist deployment:
- [ ] Set `APP_ENV=production` dan `APP_DEBUG=false`
- [ ] Jalankan `php artisan optimize`
- [ ] Jalankan `npm run build`
- [ ] Jalankan `php artisan migrate --force`
- [ ] Aktifkan queue worker (Supervisor)
- [ ] Aktifkan WebSocket server
- [ ] Setup cron job untuk scheduled commands

---

## Panduan untuk Agent AI

Lihat `docs/AGENT_GUIDE.md` untuk panduan lengkap pengembangan menggunakan AI agent, termasuk:
- Konteks setiap modul
- Pola kode yang harus diikuti
- Enum values yang tersedia
- Relasi antar tabel
- Aturan bisnis penting

---

*Dibuat oleh Intercode.id Team — Proyek ISWARA TPS 3R Sapuh Jagat, Desa Gulingan, Mengwi, Badung, Bali*