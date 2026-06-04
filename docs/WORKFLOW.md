# WORKFLOW — Panduan Pengembangan ISWARA

Dokumen ini menjelaskan alur kerja pengembangan sistem ISWARA, mulai dari membuat fitur baru hingga deployment. Dokumen ini **wajib dibaca** sebelum mulai berkontribusi, baik oleh developer maupun AI agent.

---

## Daftar Isi

- [Prinsip Arsitektur](#prinsip-arsitektur)
- [Git Workflow](#git-workflow)
- [Alur Membuat Fitur Baru](#alur-membuat-fitur-baru)
- [Backend Workflow](#backend-workflow)
- [Struktur Route per Modul](#struktur-route-per-modul)
- [Frontend Workflow (React + Inertia)](#frontend-workflow-react--inertia)

- [Workflow Database & Migrasi](#workflow-database--migrasi)
- [Workflow GIS (PostGIS)](#workflow-gis-postgis)
- [Workflow RBAC & Permissions](#workflow-rbac--permissions)
- [Workflow Audit Log](#workflow-audit-log)
- [Workflow Queue & Jobs](#workflow-queue--jobs)
- [Workflow Testing](#workflow-testing)
- [Code Review Checklist](#code-review-checklist)

---

## Prinsip Arsitektur

ISWARA mengikuti prinsip-prinsip berikut. **Jangan dilanggar tanpa diskusi terlebih dahulu.**

### 1. Fat Service, Thin Controller

Semua logika bisnis **WAJIB** ditulis di Service class, bukan di Controller.

```php
// ❌ SALAH — logika di controller
class CustomerController extends Controller {
    public function store(Request $request) {
        $qrCode = QrCode::generate($request->customer_code);
        $customer = Customer::create([...$request->all(), 'qr_code' => $qrCode]);
        AuditLog::create([...]);  // jangan di sini
    }
}

// ✅ BENAR — controller hanya delegasi ke service
class CustomerController extends Controller {
    public function store(StoreCustomerRequest $request, CustomerService $service) {
        $customer = $service->create($request->validated());
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }
}
```

### 2. Form Request untuk Validasi

Semua validasi input **WAJIB** menggunakan Form Request class.

```php
// app/Http/Requests/Customer/StoreCustomerRequest.php
class StoreCustomerRequest extends FormRequest {
    public function authorize(): bool {
        return $this->user()->can('create-customers');
    }

    public function rules(): array {
        return [
            'NIK'          => ['required', 'digits:16', 'unique:customers'],
            'full_name'    => ['required', 'string', 'max:150'],
            'region_id'    => ['required', 'exists:master_regions,region_id'],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
        ];
    }
}
```

### 3. Enum untuk Nilai Tetap

Semua nilai kolom yang bersifat tetap (status, kategori, tipe) **WAJIB** menggunakan PHP Enum.

```php
// app/Enums/CustomerStatus.php
enum CustomerStatus: string {
    case Active    = 'Aktif';
    case Inactive  = 'Nonaktif';
    case Arrears   = 'Menunggak';
    case Moved     = 'Pindah';
    case Suspended = 'Ditangguhkan';

    public function label(): string {
        return match($this) {
            self::Active    => 'Aktif',
            self::Inactive  => 'Nonaktif',
            self::Arrears   => 'Menunggak',
            self::Moved     => 'Pindah',
            self::Suspended => 'Ditangguhkan',
        };
    }

    public function color(): string {
        return match($this) {
            self::Active    => 'green',
            self::Inactive  => 'gray',
            self::Arrears   => 'red',
            self::Moved     => 'yellow',
            self::Suspended => 'orange',
        };
    }
}
```

### 4. HasAuditLog Trait

Setiap Model yang datanya penting **WAJIB** menggunakan `HasAuditLog` trait agar semua perubahan tercatat otomatis ke tabel `audit_logs`.

```php
use App\Traits\HasAuditLog;

class Customer extends Model {
    use HasAuditLog;
    // ...
}
```

### 5. Inertia Resource Response

Semua response ke frontend menggunakan Inertia, bukan JSON (kecuali endpoint API mobile).

```php
// Controller untuk web (Inertia)
return Inertia::render('Customer/Customers/Index', [
    'customers' => CustomerResource::collection($customers),
    'filters'   => $request->only(['search', 'region_id', 'status']),
]);

// Controller untuk API mobile
return response()->json([
    'success' => true,
    'data'    => CustomerResource::collection($customers),
]);
```

---

## Git Workflow

ISWARA menggunakan **Gitflow** yang disederhanakan:

```
main          ← Production-ready, hanya merge dari develop via PR
  └── develop ← Branch integrasi utama
        ├── feature/ISW-001-customer-module
        ├── feature/ISW-002-payment-module
        ├── fix/ISW-015-invoice-calculation-bug
        └── hotfix/ISW-030-login-crash (dari main, langsung ke main & develop)
```

### Format Branch Name

```
{tipe}/{ticket-id}-{deskripsi-singkat}

Contoh:
feature/ISW-001-master-data-module
feature/ISW-015-gis-customer-map
fix/ISW-023-qr-code-not-generated
hotfix/ISW-031-payment-duplicate
```

### Format Commit Message

Mengikuti **Conventional Commits**:

```
{tipe}({scope}): {deskripsi singkat}

{body opsional}

{footer opsional — referensi issue}
```

Tipe commit yang digunakan:

| Tipe | Kapan Digunakan |
|------|----------------|
| `feat` | Fitur baru |
| `fix` | Bug fix |
| `refactor` | Refactor kode (tidak mengubah behaviour) |
| `test` | Menambah/mengubah test |
| `docs` | Perubahan dokumentasi |
| `style` | Format kode, spasi, titik koma |
| `chore` | Config, dependency, CI/CD |
| `db` | Perubahan migrasi atau seeder |

Contoh:
```
feat(customer): add QR code generation on customer creation

- Generate QR code via SimpleSoftwareIO/simple-qrcode
- Store QR code path in customers.qr_code column
- Display QR code in customer detail page

Closes ISW-008
```

### Pull Request Rules

- Setiap PR **harus** di-review minimal 1 orang sebelum merge ke `develop`
- PR ke `main` harus di-review minimal 2 orang
- Semua tests harus hijau sebelum merge
- Wajib sertakan screenshot/video untuk perubahan UI

---

## Alur Membuat Fitur Baru

Setiap fitur baru mengikuti urutan langkah berikut **tanpa boleh dilewati**:

```
1.  Buat branch dari develop
2.  Buat/update migration & jalankan
3.  Buat/update Model dengan relasi & enum
4.  Buat Form Request (validasi)
5.  Buat Service class (logika bisnis)
6.  Buat Controller (delegasi ke service, return Inertia)
7.  Daftarkan routes di file modul yang sesuai (routes/modules/{modul}.php)
8.  Pastikan file modul sudah di-require dari routes/web.php
9.  Buat API Resource (transformasi data)
10. Buat React Page & Components
11. Tambahkan permissions ke seeder
12. Tulis tests (Feature test minimal)
13. Update dokumentasi modul di docs/MODULES/
14. Buat PR ke develop
```

---

## Backend Workflow

### Membuat Modul Baru (Contoh: Modul Keluhan)

#### Langkah 1: Migrasi Database

```bash
php artisan make:migration create_complaints_table
```

```php
// database/migrations/..._create_complaints_table.php
public function up(): void {
    Schema::create('complaints', function (Blueprint $table) {
        $table->bigIncrements('complaint_id');
        $table->string('complaint_code', 30)->unique();
        $table->foreignId('customer_id')->constrained('customers', 'customer_id');
        $table->foreignId('complaint_type_id')->constrained('master_complaint_types', 'complaint_type_id');
        $table->string('complaint_status', 30)->default('Baru');
        $table->text('description');
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        $table->timestamps();
    });
}
```

#### Langkah 2: Model

```php
// app/Models/Complaint/Complaint.php
class Complaint extends Model {
    use HasAuditLog, GeneratesCode;

    protected $primaryKey = 'complaint_id';
    protected $guarded = ['complaint_id'];

    protected $casts = [
        'complaint_status' => ComplaintStatus::class,
    ];

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function complaintType(): BelongsTo {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id', 'complaint_type_id');
    }

    public function handlings(): HasMany {
        return $this->hasMany(ComplaintHandling::class, 'complaint_id', 'complaint_id');
    }
}
```

#### Langkah 3: Form Request

```bash
php artisan make:request Complaint/StoreComplaintRequest
php artisan make:request Complaint/UpdateComplaintRequest
```

#### Langkah 4: Service

```php
// app/Services/Complaint/ComplaintService.php
class ComplaintService {
    public function create(array $data, int $userId): Complaint {
        $data['complaint_code'] = $this->generateCode();
        $data['reported_by'] = $userId;
        $data['complaint_status'] = ComplaintStatus::New->value;

        $complaint = Complaint::create($data);

        // Kirim notifikasi ke role yang bertanggung jawab
        $this->notificationService->notifyNewComplaint($complaint);

        return $complaint;
    }

    public function updateStatus(Complaint $complaint, string $status, string $notes): void {
        $complaint->update([
            'complaint_status' => $status,
        ]);

        ComplaintHandling::create([
            'complaint_id' => $complaint->complaint_id,
            'handled_by'   => auth()->id(),
            'action_taken' => $notes,
            'handled_at'   => now(),
        ]);
    }
}
```

#### Langkah 5: Controller

```bash
php artisan make:controller Complaint/ComplaintController --resource
```

#### Langkah 6: Routes

Route **tidak** langsung ditulis di `routes/web.php`. Setiap modul memiliki file route-nya sendiri di `routes/modules/`. Buat atau buka file yang sesuai dengan modul yang sedang dikerjakan:

```php
// routes/modules/complaint.php  ← file khusus Modul 5

use App\Http\Controllers\Complaint\ComplaintController;
use App\Http\Controllers\Complaint\ComplaintHandlingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('complaints')
    ->name('complaints.')
    ->group(function () {

        Route::get('/', [ComplaintController::class, 'index'])
            ->name('index')
            ->middleware('permission:view-complaints');

        Route::get('/create', [ComplaintController::class, 'create'])
            ->name('create')
            ->middleware('permission:create-complaints');

        Route::post('/', [ComplaintController::class, 'store'])
            ->name('store')
            ->middleware('permission:create-complaints');

        Route::get('/{id}', [ComplaintController::class, 'show'])
            ->name('show')
            ->middleware('permission:view-complaints');

        Route::put('/{id}/status', [ComplaintController::class, 'updateStatus'])
            ->name('update-status')
            ->middleware('permission:edit-complaints|assign-complaints');

        // ── Penanganan Keluhan ──────────────────────────────────────────
        Route::prefix('{complaintId}/handlings')
            ->name('handlings.')
            ->group(function () {
                Route::post('/', [ComplaintHandlingController::class, 'store'])
                    ->name('store')
                    ->middleware('permission:assign-complaints');
            });
    });
```

Pastikan file modul sudah di-`require` di `routes/web.php` (lihat seksi [Struktur Route per Modul](#struktur-route-per-modul)).

---

## Struktur Route per Modul

Semua route web diorganisir dalam folder `routes/modules/`, satu file per modul. `routes/web.php` hanya berfungsi sebagai **entry point** yang me-`require` semua file modul.

### Struktur Folder

```
routes/
├── web.php                  ← Entry point, hanya berisi require ke modul
├── api.php                  ← Route API untuk mobile app
├── auth.php                 ← Route login, register, password reset (Laravel default)
├── channels.php             ← WebSocket channels
└── modules/
    ├── dashboard.php        ← Dashboard (admin, TV publik, desa, manajemen)
    ├── master.php           ← Modul 1: Master Data & Autentifikasi
    ├── customer.php         ← Modul 2: Penerima Manfaat & SDM
    ├── asset.php            ← Modul 3: Aset & Armada
    ├── pickup.php           ← Modul 4: Pengangkutan Sampah
    ├── complaint.php        ← Modul 5: Keluhan & Ticketing
    ├── finance.php          ← Modul 6: Pembayaran & Keuangan
    ├── composting.php       ← Modul 7: TPS 3R & Pengomposan
    ├── waste-bank.php       ← Modul 8: Bank Sampah Digital
    └── support.php          ← Modul 9: Manajemen Pendukung
```

### Isi `routes/web.php`

`web.php` **hanya** berisi require — tidak ada definisi route di sini:

```php
<?php

use Illuminate\Support\Facades\Route;

// ── Halaman awal ──────────────────────────────────────────────────────────────
Route::get('/', fn () => inertia('Welcome'));

// ── Autentikasi (Laravel default) ─────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Modul-modul ───────────────────────────────────────────────────────────────
require __DIR__ . '/modules/dashboard.php';   // Dashboard
require __DIR__ . '/modules/master.php';       // Modul 1: Master Data & Auth
require __DIR__ . '/modules/customer.php';     // Modul 2: Penerima Manfaat & SDM
require __DIR__ . '/modules/asset.php';        // Modul 3: Aset & Armada
require __DIR__ . '/modules/pickup.php';       // Modul 4: Pengangkutan Sampah
require __DIR__ . '/modules/complaint.php';    // Modul 5: Keluhan & Ticketing
require __DIR__ . '/modules/finance.php';      // Modul 6: Pembayaran & Keuangan
require __DIR__ . '/modules/composting.php';   // Modul 7: TPS 3R & Pengomposan
require __DIR__ . '/modules/waste-bank.php';   // Modul 8: Bank Sampah Digital
require __DIR__ . '/modules/support.php';      // Modul 9: Manajemen Pendukung
```

### Template File Route per Modul

Setiap file di `routes/modules/` mengikuti template berikut:

```php
<?php
// routes/modules/{nama-modul}.php

use App\Http\Controllers\{Namespace}\{Controller};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Modul {N} — {Nama Modul}
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('{prefix}')          // contoh: 'complaints'
    ->name('{prefix}.')           // contoh: 'complaints.'
    ->group(function () {

        // Gunakan resource() untuk CRUD standar
        Route::resource('{resource}', {Controller}::class);

        // Tambahkan route custom di bawah resource()
        Route::put('{resource}/{id}/status', [{Controller}::class, 'updateStatus'])
            ->name('{resource}.update-status')
            ->middleware('permission:{aksi}-{resource}');
    });
```

### Aturan Penulisan Route

1. **Satu file per modul** — jangan campur route Modul 2 ke dalam file modul lain
2. **Selalu gunakan `name()`** — semua route wajib punya nama, format: `{modul}.{aksi}`
3. **Middleware permission di setiap route** — gunakan `->middleware('permission:...')`, bukan di dalam controller
4. **Gunakan `Route::resource()`** untuk endpoint CRUD standar, tambahkan route custom secara eksplisit di bawahnya
5. **Import `use` di atas file** — jangan gunakan FQCN (fully-qualified class name) inline di dalam closure

### Contoh Nama Route yang Benar

| Route | Nama |
|-------|------|
| `GET /complaints` | `complaints.index` |
| `GET /complaints/create` | `complaints.create` |
| `POST /complaints` | `complaints.store` |
| `GET /complaints/{id}` | `complaints.show` |
| `GET /complaints/{id}/edit` | `complaints.edit` |
| `PUT /complaints/{id}` | `complaints.update` |
| `DELETE /complaints/{id}` | `complaints.destroy` |
| `PUT /complaints/{id}/status` | `complaints.update-status` |

---

## Frontend Workflow (React + Inertia)


### Struktur Page

```
resources/js/Pages/Complaint/
├── Complaints/
│   ├── Index.jsx     ← Daftar keluhan dengan filter & tabel
│   ├── Create.jsx    ← Form buat keluhan baru
│   ├── Show.jsx      ← Detail keluhan + timeline penanganan
│   └── Edit.jsx      ← Form edit keluhan
└── ComplaintHandlings/
    └── Create.jsx    ← Form tambah penanganan
```

### Pola Halaman Index (List)

```jsx
// resources/js/Pages/Complaint/Complaints/Index.jsx
import AppLayout from '@/Layouts/AppLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import DataTable from '@/Components/UI/DataTable';
import StatusBadge from '@/Components/UI/StatusBadge';
import usePermission from '@/Hooks/usePermission';

export default function ComplaintsIndex({ complaints, filters }) {
  const { can } = usePermission();
  const [search, setSearch] = useState(filters.search || '');

  const handleSearch = (e) => {
    e.preventDefault();
    router.get(route('complaints.index'), { search }, { preserveState: true });
  };

  return (
    <AppLayout>
      <Head title="Keluhan & Pengaduan" />

      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold">Keluhan & Pengaduan</h1>
        {can('create-complaints') && (
          <Link href={route('complaints.create')} className="btn-primary">
            + Laporkan Keluhan
          </Link>
        )}
      </div>

      {/* Filter & Search */}
      <form onSubmit={handleSearch} className="mb-4 flex gap-2">
        <input
          type="text"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Cari nomor keluhan atau nama pelanggan..."
          className="input-field flex-1"
        />
        <button type="submit" className="btn-secondary">Cari</button>
      </form>

      {/* Tabel Data */}
      <DataTable
        data={complaints.data}
        columns={[
          { key: 'complaint_code', label: 'Nomor Keluhan' },
          { key: 'customer.full_name', label: 'Pelanggan' },
          { key: 'complaint_type.complaint_name', label: 'Jenis Keluhan' },
          {
            key: 'complaint_status',
            label: 'Status',
            render: (row) => <StatusBadge status={row.complaint_status} />
          },
          { key: 'created_at', label: 'Tanggal Lapor' },
        ]}
        pagination={complaints}
        rowLink={(row) => route('complaints.show', row.complaint_id)}
      />
    </AppLayout>
  );
}
```

### Pola Form dengan Inertia

```jsx
// resources/js/Pages/Complaint/Complaints/Create.jsx
import { useForm } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function ComplaintsCreate({ customers, complaintTypes }) {
  const { data, setData, post, processing, errors } = useForm({
    customer_id: '',
    complaint_type_id: '',
    description: '',
    latitude: '',
    longitude: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    post(route('complaints.store'));
  };

  return (
    <AppLayout>
      <form onSubmit={handleSubmit}>
        {/* Field inputs */}
        {errors.description && (
          <p className="text-red-500 text-sm">{errors.description}</p>
        )}
        <button type="submit" disabled={processing}>
          {processing ? 'Menyimpan...' : 'Simpan Keluhan'}
        </button>
      </form>
    </AppLayout>
  );
}
```

---

## Workflow Database & Migrasi

### Aturan Migrasi

1. **Satu migrasi per tabel** — jangan campur buat tabel A dan B dalam satu file migrasi
2. **Beri nama yang deskriptif** — `create_complaints_table`, bukan `complaints_migration`
3. **Selalu sertakan `down()` yang valid** — untuk rollback bisa berjalan
4. **Jangan edit migrasi yang sudah di-push ke shared branch** — buat migrasi baru untuk alter

```bash
# Buat migrasi baru untuk alter tabel
php artisan make:migration add_priority_to_complaints_table

# Jangan lakukan ini kalau sudah di-push:
# php artisan migrate:rollback  → lalu edit file lama
```

### Urutan Migrasi

Migrasi **harus** dijalankan sesuai urutan dependency:

```
1. Master tables (tidak ada FK ke modul lain)
   roles → users → master_regions → master_roads → ...

2. Customer tables (FK ke master)
   customers → customer_users → workers → worker_attendances

3. Asset tables (FK ke master)
   assets → fleet_vehicles → asset_maintenances

4. Transactional tables (FK ke customer + master)
   pickup_schedules → pickup_routes → ...
   monthly_invoices → payments → financial_ledger
   ...

5. Support tables (FK ke hampir semua)
   notifications → audit_logs → operational_statistics → ...
```

---

## Workflow GIS (PostGIS)

### Menambah Field GIS ke Model

```php
// app/Traits/HasPostGIS.php
trait HasPostGIS {
    // Konversi koordinat lat/lng ke PostGIS Point
    public function setLocationFromCoordinates(float $lat, float $lng): void {
        DB::statement(
            "UPDATE {$this->getTable()} 
             SET location = ST_SetSRID(ST_MakePoint(?, ?), 4326) 
             WHERE {$this->primaryKey} = ?",
            [$lng, $lat, $this->getKey()]
        );
    }

    // Scope: cari dalam radius tertentu (meter)
    public function scopeNearby(Builder $query, float $lat, float $lng, int $radiusMeters = 500): Builder {
        return $query->whereRaw(
            "ST_DWithin(location::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)",
            [$lng, $lat, $radiusMeters]
        )->selectRaw(
            "*, ST_Distance(location::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) as distance",
            [$lng, $lat]
        )->orderBy('distance');
    }
}
```

### Menyimpan Polygon GeoJSON

```php
// Service: simpan polygon wilayah
public function updateRegionPolygon(Region $region, array $geoJsonPolygon): void {
    DB::statement(
        "UPDATE master_regions 
         SET polygon_area = ST_SetSRID(ST_GeomFromGeoJSON(?), 4326) 
         WHERE region_id = ?",
        [json_encode($geoJsonPolygon), $region->region_id]
    );

    // Simpan juga GeoJSON mentah untuk keperluan frontend
    $region->update(['polygon_geojson' => json_encode($geoJsonPolygon)]);
}
```

### Visualisasi Peta di Frontend

```jsx
// resources/js/Components/Map/CustomerMap.jsx
import { MapContainer, TileLayer, Marker, Polygon } from 'react-leaflet';

export default function CustomerMap({ customers, regions }) {
  const center = [
    parseFloat(import.meta.env.VITE_MAP_CENTER_LAT),
    parseFloat(import.meta.env.VITE_MAP_CENTER_LNG),
  ];

  return (
    <MapContainer center={center} zoom={14} className="h-96 w-full rounded-lg">
      <TileLayer
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        attribution="© OpenStreetMap"
      />
      {regions.map((region) => (
        region.polygon_geojson && (
          <Polygon
            key={region.region_id}
            positions={JSON.parse(region.polygon_geojson).coordinates[0].map(
              ([lng, lat]) => [lat, lng]
            )}
          />
        )
      ))}
      {customers.map((customer) => (
        <Marker
          key={customer.customer_id}
          position={[customer.latitude, customer.longitude]}
        />
      ))}
    </MapContainer>
  );
}
```

---

## Workflow RBAC & Permissions

### Menambah Permission Baru

Setiap kali menambah fitur baru, **wajib** tambahkan permission terkait di seeder:

```php
// database/seeders/Master/PermissionSeeder.php
$permissions = [
    // === Modul Keluhan ===
    'view-complaints',
    'create-complaints',
    'edit-complaints',
    'delete-complaints',
    'assign-complaints',
    'close-complaints',
    // Tambahkan permission baru di sini
];
```

### Assign Permission ke Role

```php
// Setelah menambah permission baru, assign ke role yang sesuai:
$adminRole->givePermissionTo([
    'view-complaints',
    'create-complaints',
    'edit-complaints',
    'delete-complaints',
    'assign-complaints',
    'close-complaints',
]);

$operatorRole->givePermissionTo([
    'view-complaints',
    'create-complaints',
]);
```

### Middleware Permission di Route

```php
// Gunakan middleware permission, bukan role
Route::get('/complaints', ...)->middleware('permission:view-complaints');
Route::post('/complaints', ...)->middleware('permission:create-complaints');

// Untuk aksi yang memerlukan salah satu dari beberapa permission
Route::put('/complaints/{id}', ...)->middleware('permission:edit-complaints|assign-complaints');
```

---

## Workflow Audit Log

Audit log dicatat **otomatis** melalui `HasAuditLog` trait. Agent tidak perlu mencatat audit secara manual di controller atau service.

### Cara Kerja HasAuditLog

```php
// app/Traits/HasAuditLog.php
trait HasAuditLog {
    protected static function bootHasAuditLog(): void {
        static::created(fn ($model) => AuditService::log('CREATE', $model));
        static::updated(fn ($model) => AuditService::log('UPDATE', $model, $model->getOriginal()));
        static::deleted(fn ($model) => AuditService::log('DELETE', $model));
    }
}
```

### Aktivitas yang Dicatat Manual

Untuk aktivitas yang tidak berhubungan langsung dengan perubahan model (LOGIN, EXPORT, dll.), catat secara manual melalui `AuditService`:

```php
// Di AuthController setelah login berhasil
AuditService::logActivity(
    activityType: AuditActivityType::Login,
    description: 'Pengguna berhasil login',
    result: 'SUCCESS'
);

// Di controller export laporan
AuditService::logActivity(
    activityType: AuditActivityType::Export,
    entityType: 'monthly_invoices',
    description: "Export laporan tagihan bulan {$month}",
    result: 'SUCCESS'
);
```

---

## Workflow Queue & Jobs

Beberapa proses berat dijalankan via Queue Job agar tidak memblokir response:

| Job | Trigger | Jadwal |
|-----|---------|--------|
| `GenerateMonthlyInvoicesJob` | Cron awal bulan | 1 setiap bulan, jam 00:00 |
| `AggregateStatisticsJob` | Cron harian | Setiap hari, jam 23:55 |
| `SendPaymentReminderJob` | Manual / jadwal | Setiap tanggal 15 & 25 |
| `GenerateDocumentJob` | User request | Setelah user klik "Generate" |

### Menjalankan Queue Worker

```bash
# Development
php artisan queue:work

# Production (via Supervisor)
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

### Scheduled Commands

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule): void {
    // Generate statistik harian setiap tengah malam
    $schedule->command('iswara:aggregate-statistics')
             ->dailyAt('23:55')
             ->withoutOverlapping();

    // Generate tagihan bulanan setiap tanggal 1
    $schedule->command('iswara:generate-monthly-invoices')
             ->monthlyOn(1, '00:00')
             ->withoutOverlapping();

    // Kirim reminder pembayaran
    $schedule->command('iswara:send-payment-reminders')
             ->monthlyOn(15, '08:00');
}
```

---

## Workflow Testing

### Menulis Feature Test

```php
// tests/Feature/Complaint/ComplaintCreationTest.php
class ComplaintCreationTest extends TestCase {
    use RefreshDatabase;

    public function test_customer_can_create_complaint(): void {
        $customer = Customer::factory()->create();
        $user = User::factory()->withRole('penerima_manfaat')->create();
        $user->customers()->attach($customer->customer_id);

        $response = $this->actingAs($user)
            ->post(route('complaints.store'), [
                'customer_id'       => $customer->customer_id,
                'complaint_type_id' => ComplaintType::factory()->create()->complaint_type_id,
                'description'       => 'Sampah tidak diangkut sejak 3 hari lalu',
            ]);

        $response->assertRedirect(route('complaints.index'));
        $this->assertDatabaseHas('complaints', [
            'customer_id' => $customer->customer_id,
            'description' => 'Sampah tidak diangkut sejak 3 hari lalu',
        ]);
    }

    public function test_complaint_requires_description(): void {
        $user = User::factory()->withRole('penerima_manfaat')->create();

        $response = $this->actingAs($user)
            ->post(route('complaints.store'), []);

        $response->assertSessionHasErrors(['description']);
    }
}
```

### Test Coverage Minimum

- Semua CRUD endpoint: **Feature test wajib**
- Service method penting (kalkulasi tagihan, generate invoice): **Unit test wajib**
- Edge cases: tambahkan sesuai kompleksitas modul

---

## Code Review Checklist

Sebelum merge PR, reviewer **wajib** memverifikasi semua poin berikut:

### Backend

- [ ] Logika bisnis ada di Service, bukan Controller
- [ ] Validasi menggunakan Form Request
- [ ] Enum digunakan untuk nilai kolom tetap
- [ ] Model menggunakan `HasAuditLog` jika data penting
- [ ] Tidak ada raw SQL selain untuk PostGIS (gunakan Eloquent)
- [ ] Permission dicek di Form Request atau Middleware (bukan hanya di blade/jsx)
- [ ] Tidak ada `dd()`, `var_dump()`, atau `print_r()` tertinggal
- [ ] Semua FK menggunakan `constrained()` dengan referensi kolom yang tepat
- [ ] Seeder diupdate jika ada permission baru
- [ ] Tests tersedia dan berjalan hijau

### Frontend

- [ ] Page menggunakan `AppLayout` atau layout yang sesuai
- [ ] Permission dicek sebelum menampilkan tombol aksi sensitif (`can('...')`)
- [ ] Form menggunakan `useForm` dari Inertia
- [ ] Error validasi ditampilkan di dekat field terkait
- [ ] Loading state ditampilkan saat form di-submit (`processing`)
- [ ] Tidak ada data sensitif (password, token) dikirim ke props Inertia

### Database

- [ ] Migrasi punya `down()` yang valid
- [ ] Index ditambahkan pada kolom yang sering di-WHERE atau JOIN
- [ ] Kolom GIS menggunakan tipe PostGIS geography (bukan decimal saja)
- [ ] Tidak ada `nullable()` pada kolom yang seharusnya wajib diisi

---

*Dokumen ini diperbarui setiap ada perubahan arsitektur signifikan. Terakhir diperbarui: Juni 2026*