# AGENT GUIDE — Panduan Khusus untuk AI Agent
## Proyek ISWARA TPS 3R Sapuh Jagat

Dokumen ini adalah **panduan utama untuk AI agent** yang membantu pengembangan sistem ISWARA. Baca dokumen ini sepenuhnya sebelum mengerjakan task apapun.

---

## Daftar Isi

- [Konteks Bisnis Proyek](#konteks-bisnis-proyek)
- [Stack & Dependency](#stack--dependency)
- [Aturan Wajib untuk Agent](#aturan-wajib-untuk-agent)
- [Peta Modul & Tabel](#peta-modul--tabel)
- [Relasi Antar Tabel Kritis](#relasi-antar-tabel-kritis)
- [Semua Enum & Nilai yang Valid](#semua-enum--nilai-yang-valid)
- [Aturan Bisnis Penting](#aturan-bisnis-penting)
- [Pola Kode yang Harus Diikuti](#pola-kode-yang-harus-diikuti)
- [Penggunaan PostGIS](#penggunaan-postgis)
- [Cara Kerja Audit Log](#cara-kerja-audit-log)
- [Cara Kerja Invoice Otomatis](#cara-kerja-invoice-otomatis)
- [Cara Kerja Bank Sampah](#cara-kerja-bank-sampah)
- [Cara Kerja QR Code Pelanggan](#cara-kerja-qr-code-pelanggan)
- [Cara Kerja Statistik Operasional](#cara-kerja-statistik-operasional)
- [Pertanyaan Umum & Jawaban](#pertanyaan-umum--jawaban)

---

## Konteks Bisnis Proyek

**ISWARA** adalah sistem informasi manajemen untuk **TPS 3R Sapuh Jagat** di **Desa Gulingan, Mengwi, Kabupaten Badung, Bali**. TPS 3R (Tempat Pengelolaan Sampah — Reduce, Reuse, Recycle) adalah fasilitas komunal yang mengelola sampah dari warga sekitar.

### Istilah Penting yang Harus Dipahami

| Istilah | Arti dalam Konteks ISWARA |
|---------|--------------------------|
| **Penerima Manfaat** | Warga/pelanggan yang menerima layanan pengangkutan sampah. Dalam kode: `Customer` |
| **Petugas** | Staff operasional TPS 3R yang tidak punya akun sistem (sopir, tukang angkut). Dalam kode: `Worker` |
| **Pengguna** | Akun yang bisa login ke sistem (admin, kasir, operator, dll). Dalam kode: `User` |
| **Banjar** | Subdivisi wilayah khas Bali, mirip RT/RW. Dalam kode: `master_regions` |
| **Kelian Adat** | Pemimpin adat Banjar |
| **Kelian Dinas** | Pemimpin administratif Banjar |
| **Iuran** | Biaya bulanan yang dibayar warga untuk layanan pengangkutan sampah |
| **Sampah Khusus** | Sampah yang bukan dari jadwal reguler (sampah hajatan, upacara adat, renovasi) |
| **Bank Sampah** | Layanan tukar-sampah-dengan-uang. Warga setor sampah anorganik, dapat nilai rupiah |
| **TPA** | Tempat Pembuangan Akhir. Residu yang tidak bisa diolah dikirim ke sini |
| **Kompos** | Hasil olahan sampah organik. Dijual ke warga/petani |
| **Kesling** | Petugas Kesehatan Lingkungan |
| **3R** | Reduce, Reuse, Recycle — filosofi pengelolaan sampah |
| **SLA** | Service Level Agreement — batas waktu penanganan keluhan |

---

## Stack & Dependency

### Backend (Laravel 13)

```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^13.0",
    "inertiajs/inertia-laravel": "^2.0",
    "spatie/laravel-permission": "^6.0",
    "barryvdh/laravel-dompdf": "^3.0",
    "simplesoftwareio/simple-qrcode": "^4.2",
    "tightenco/ziggy": "^2.0"
  }
}
```

### Frontend (React + Inertia)

```json
{
  "dependencies": {
    "@inertiajs/react": "^2.0",
    "react": "^19.0",
    "react-dom": "^19.0",
    "leaflet": "^1.9",
    "react-leaflet": "^4.2",
    "axios": "^1.6"
  },
  "devDependencies": {
    "tailwindcss": "^3.4",
    "vite": "^6.0",
    "@vitejs/plugin-react": "^4.2"
  }
}
```

### Database

```sql
-- Ekstensi yang harus aktif di PostgreSQL
CREATE EXTENSION IF NOT EXISTS postgis;
CREATE EXTENSION IF NOT EXISTS postgis_topology;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
```

---

## Aturan Wajib untuk Agent

Ini adalah aturan yang **tidak boleh dilanggar** saat menulis kode:

### ✅ HARUS

1. **HARUS** menaruh logika bisnis di `app/Services/`, bukan di Controller
2. **HARUS** menggunakan Form Request untuk semua validasi input
3. **HARUS** menggunakan PHP Enum untuk kolom yang nilainya tetap
4. **HARUS** menambahkan `HasAuditLog` trait pada Model yang datanya perlu dilacak
5. **HARUS** menggunakan `Inertia::render()` untuk web response (bukan `response()->json()`)
6. **HARUS** menambahkan permission baru ke `PermissionSeeder` setiap buat fitur baru
7. **HARUS** menyertakan index database pada kolom FK dan kolom yang sering difilter
8. **HARUS** menyertakan `$table->timestamps()` di semua migrasi
9. **HARUS** menggunakan `geography` PostGIS (bukan `geometry`) untuk kolom koordinat karena perhitungan jarak dalam meter lebih akurat

### ❌ JANGAN

1. **JANGAN** menulis logika bisnis di Controller
2. **JANGAN** menggunakan string literal untuk nilai enum/status — selalu pakai Enum class
3. **JANGAN** menggunakan `Response::json()` untuk endpoint web (Inertia)
4. **JANGAN** membuat migration yang mengubah tabel yang sudah ada — buat migration baru
5. **JANGAN** menggunakan `$request->all()` untuk mass assignment — selalu `$request->validated()`
6. **JANGAN** hardcode koordinat Bali — ambil dari config `ISWARA_MAP_CENTER_LAT` / `ISWARA_MAP_CENTER_LNG`
7. **JANGAN** menggunakan `WHERE` langsung pada kolom `geography` — selalu gunakan fungsi PostGIS (`ST_DWithin`, `ST_Distance`, dll.)
8. **JANGAN** lupa sertakan foreign key constraint di migration

---

## Peta Modul & Tabel

### Modul 1: Master Data & Autentifikasi

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `roles` | `App\Models\Master\Role` | Role sistem (pakai Spatie Permission) |
| `users` | `App\Models\Master\User` | Akun pengguna yang bisa login |
| `master_regions` | `App\Models\Master\Region` | Wilayah/Banjar. Ada field polygon GIS |
| `master_roads` | `App\Models\Master\Road` | Jalan & gang. Ada field polyline GIS |
| `master_special_places` | `App\Models\Master\SpecialPlace` | Tempat ibadah, sekolah, pasar, dll |
| `master_business_types` | `App\Models\Master\BusinessType` | Jenis usaha pelanggan |
| `master_payment_methods` | `App\Models\Master\PaymentMethod` | Metode pembayaran iuran |
| `master_customer_categories` | `App\Models\Master\CustomerCategory` | Kategori & tarif iuran pelanggan |
| `master_waste_types` | `App\Models\Master\WasteType` | Jenis sampah (organik, anorganik, dll) |
| `master_complaint_types` | `App\Models\Master\ComplaintType` | Jenis keluhan + SLA response/resolution |
| `master_asset_types` | `App\Models\Master\AssetType` | Jenis aset TPS 3R |

### Modul 2: Penerima Manfaat & SDM

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `customers` | `App\Models\Customer\Customer` | **Tabel sentral.** Data warga penerima layanan |
| `customer_users` | `App\Models\Customer\CustomerUser` | Relasi many-to-many User ↔ Customer |
| `workers` | `App\Models\Customer\Worker` | Petugas lapangan (tidak punya akun login) |
| `worker_attendances` | `App\Models\Customer\WorkerAttendance` | Absensi harian petugas dengan GPS |

### Modul 3: Aset & Armada

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `assets` | `App\Models\Asset\Asset` | Semua aset TPS 3R (kendaraan, mesin, gedung) |
| `fleet_vehicles` | `App\Models\Asset\FleetVehicle` | Detail kendaraan operasional |
| `asset_maintenances` | `App\Models\Asset\AssetMaintenance` | Log servis & pemeliharaan aset |

### Modul 4: Pengangkutan Sampah

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `pickup_schedules` | `App\Models\Pickup\PickupSchedule` | Jadwal pengangkutan per wilayah |
| `special_waste_requests` | `App\Models\Pickup\SpecialWasteRequest` | Request sampah khusus (hajatan, adat) |
| `pickup_routes` | `App\Models\Pickup\PickupRoute` | Rute pengangkutan per armada per hari |
| `vehicle_tracking_logs` | `App\Models\Pickup\VehicleTrackingLog` | GPS tracking armada real-time |
| `pickup_activity_logs` | `App\Models\Pickup\PickupActivityLog` | Log pengangkutan per rumah (scan QR) |
| `landfill_disposals` | `App\Models\Pickup\LandfillDisposal` | Pengiriman residu ke TPA |

### Modul 5: Keluhan & Ticketing

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `complaints` | `App\Models\Complaint\Complaint` | Laporan keluhan masuk |
| `complaint_handlings` | `App\Models\Complaint\ComplaintHandling` | Timeline penanganan per keluhan |

### Modul 6: Pembayaran & Keuangan

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `monthly_invoices` | `App\Models\Finance\MonthlyInvoice` | Tagihan bulanan per pelanggan (auto-generate) |
| `payments` | `App\Models\Finance\Payment` | Pembayaran iuran & verifikasi |
| `financial_ledger` | `App\Models\Finance\FinancialLedger` | Buku besar keuangan TPS 3R |

### Modul 7: TPS 3R & Pengomposan

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `incoming_waste_logs` | `App\Models\Composting\IncomingWasteLog` | Sampah yang masuk ke TPS 3R (dari armada) |
| `compost_batches` | `App\Models\Composting\CompostBatch` | Batch proses pengomposan |
| `compost_outputs` | `App\Models\Composting\CompostOutput` | Hasil kompos yang diproduksi |
| `compost_distributions` | `App\Models\Composting\CompostDistribution` | Distribusi/penjualan kompos |

### Modul 8: Bank Sampah Digital

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `waste_bank_units` | `App\Models\WasteBank\WasteBankUnit` | Unit bank sampah (bisa ada lebih dari 1) |
| `waste_bank_deposits` | `App\Models\WasteBank\WasteBankDeposit` | Header setoran sampah |
| `waste_bank_deposit_details` | `App\Models\WasteBank\WasteBankDepositDetail` | Detail per jenis sampah dalam setoran |
| `waste_bank_ledger` | `App\Models\WasteBank\WasteBankLedger` | Mutasi saldo tabungan sampah per nasabah |
| `plastic_pickup_requests` | `App\Models\WasteBank\PlasticPickupRequest` | Request jemput plastik ke rumah |

### Modul 9: Manajemen Pendukung

| Tabel | Model | Keterangan |
|-------|-------|-----------|
| `media_files` | `App\Models\Support\MediaFile` | Registry semua file yang diupload |
| `notifications` | `App\Models\Support\Notification` | Notifikasi sistem per user |
| `audit_logs` | `App\Models\Support\AuditLog` | Log semua aktivitas penting (auto-filled) |
| `operational_statistics` | `App\Models\Support\OperationalStatistic` | Statistik agregat harian/bulanan/tahunan |
| `document_templates` | `App\Models\Support\DocumentTemplate` | Template surat (blade/HTML) |
| `generated_documents` | `App\Models\Support\GeneratedDocument` | Surat yang sudah di-generate |
| `correspondences` | `App\Models\Support\Correspondence` | Surat masuk & keluar |
| `guest_books` | `App\Models\Support\GuestBook` | Buku tamu digital |

---

## Relasi Antar Tabel Kritis

Pahami relasi ini sebelum menulis kode yang melibatkan join atau eager loading:

```
users (1) ──── (M) customer_users (M) ──── (1) customers
                                                    │
                            ┌───────────────────────┤
                            │                       │
                   (M) monthly_invoices      (M) pickup_activity_logs
                            │                       │
                   (M) payments        (FK) pickup_schedules
                            │
                   financial_ledger (via event)


master_customer_categories (1) ──── (M) customers
master_regions (1) ──── (M) customers
                   ──── (M) master_roads
                   ──── (M) pickup_schedules
                   ──── (M) operational_statistics


waste_bank_deposits (1) ──── (M) waste_bank_deposit_details
                        ──── (1 insert) waste_bank_ledger (debit)
customers (nasabah) ──── (M) waste_bank_deposits
                    ──── (M) waste_bank_ledger


compost_batches (1) ──── (M) compost_outputs
                    ──── input: incoming_waste_logs (organik)


master_complaint_types.SLA_response_hour ──── digunakan oleh complaints
                                              untuk hitung batas waktu respon
```

---

## Semua Enum & Nilai yang Valid

Gunakan nilai-nilai ini saat menulis seeders, tests, atau factory. Jangan hardcode string lain.

### `users.account_status`
`Aktif` | `Nonaktif` | `Ditangguhkan` | `Menunggu Verifikasi`

### `master_regions.region_type`
`Banjar` | `Non Banjar` | `Area Khusus`

### `master_regions.service_status` / `master_roads.road_status` / `master_special_places.place_status`
`Aktif` | `Nonaktif`

### `master_roads.road_type`
`Jalan` | `Gang`

### `master_roads.access_type`
`Mobil` | `Motor` | `Pejalan Kaki`

### `master_roads.road_surface`
`Aspal` | `Beton` | `Paving` | `Tanah` | `Kerikil`

### `master_roads.road_condition`
`Baik` | `Sedang` | `Rusak Ringan` | `Rusak Berat`

### `master_special_places.place_type`
`Tempat Ibadah` | `Banjar` | `Sekolah` | `Pasar` | `TPS3R` | `Setra` | `Kantor Pemerintah` | `Lapangan` | `Bank Sampah` | `Lainnya`

### `master_business_types.service_category` / `master_special_places.service_category`
`Rumah Tangga` | `Usaha Mikro` | `Usaha Kecil` | `Usaha Menengah` | `Institusi` | `Pariwisata`

### `master_business_types.recommended_collection_frequency`
`Harian` | `2 Hari Sekali` | `Mingguan` | `Sesuai Permintaan`

### `master_payment_methods.payment_category`
`Tunai` | `Transfer` | `Digital` | `Kolektor`

### `master_customer_categories.service_level`
`Standar` | `Prioritas` | `Khusus`

### `master_customer_categories.collection_frequency`
`Harian` | `2 Hari Sekali` | `Mingguan` | `Sesuai Permintaan`

### `master_waste_types.waste_category`
`Organik` | `Anorganik` | `Residu` | `Khusus` | `B3 Rumah Tangga`

### `master_waste_types.processing_method`
`Kompos` | `Daur Ulang` | `Bank Sampah` | `Residu ke TPA` | `Pengelolaan Khusus`

### `master_waste_types.unit`
`Kg` | `Gram` | `Ton` | `Karung` | `Kantong` | `Unit`

### `master_complaint_types.complaint_category`
`Pengangkutan` | `Lingkungan` | `Pelayanan` | `Pemilahan` | `Fasilitas` | `Keuangan`

### `master_complaint_types.priority_level`
`Rendah` | `Sedang` | `Tinggi` | `Darurat`

### `master_complaint_types.responsible_role`
`Operator` | `Kesling` | `Manajemen` | `Admin`

### `master_asset_types.asset_category`
`Bergerak` | `Tidak Bergerak` | `Digital`

### `master_asset_types.asset_criticality`
`Rendah` | `Sedang` | `Tinggi`

### `customers.customer_status`
`Aktif` | `Nonaktif` | `Menunggak` | `Pindah` | `Ditangguhkan`

### `customer_users.relationship_type`
`Owner` | `Family Member` | `Property Manager` | `Business Manager` | `Caretaker` | `Religious Administrator` | `Village Representative` | `Authorized Representative`

### `customer_users.access_level`
`View` | `Edit` | `Full Access`

### `workers.worker_type`
`Sopir` | `Petugas Angkut` | `Petugas Pemilahan` | `Petugas Pengomposan` | `Petugas Bank Sampah` | `Petugas Kebersihan` | `Petugas Gudang` | `Petugas Pemeliharaan` | `Petugas Keamanan`

### `workers.employment_status`
`Tetap` | `Kontrak` | `Harian Lepas` | `Magang` | `Relawan`

### `worker_attendances.attendance_status`
`Hadir` | `Terlambat` | `Izin` | `Sakit` | `Alpa` | `Libur` | `Dinas Luar`

### `worker_attendances.assigned_shift`
`Pagi` | `Siang` | `Sore` | `Malam` | `Fleksibel`

### `worker_attendances.verification_status`
`Belum Diverifikasi` | `Terverifikasi` | `Ditolak`

### `audit_logs.activity_type`
`LOGIN` | `LOGOUT` | `CREATE` | `UPDATE` | `DELETE` | `VIEW` | `APPROVE` | `REJECT` | `VERIFY` | `EXPORT` | `IMPORT` | `ASSIGN` | `PAYMENT` | `SYSTEM`

### `audit_logs.activity_result`
`SUCCESS` | `FAILED` | `WARNING` | `BLOCKED`

### `operational_statistics.statistic_period`
`HARIAN` | `MINGGUAN` | `BULANAN` | `TAHUNAN`

### `correspondences.correspondence_type`
`INCOMING` | `OUTGOING`

### `correspondences.priority_level`
`LOW` | `MEDIUM` | `HIGH` | `URGENT`

### `generated_documents.document_status`
`DRAFT` | `FINAL` | `SENT` | `ARCHIVED`

### `guest_books.institution_type`
`GOVERNMENT` | `DLH` | `SCHOOL` | `UNIVERSITY` | `COMMUNITY` | `NGO` | `COMPANY` | `CSR_PARTNER` | `MEDIA` | `RESEARCHER` | `INDIVIDUAL` | `OTHER`

### `guest_books.purpose_of_visit`
`STUDY_VISIT` | `EDUCATION` | `RESEARCH` | `MONITORING` | `AUDIT` | `COOPERATION` | `TRAINING` | `CSR_DISCUSSION` | `OFFICIAL_VISIT` | `OTHER`

---

## Aturan Bisnis Penting

Ini adalah aturan bisnis yang harus dipahami dan diimplementasikan dengan benar:

### 1. Kode Pelanggan (customer_code)
- Format: `{kode_wilayah}-{nomor_urut}` contoh: `BNJ01-0001`
- Di-generate otomatis oleh `CustomerService::generateCode()`
- Tidak bisa diubah setelah dibuat

### 2. Invoice Bulanan (monthly_invoices)
- Di-generate **otomatis** setiap tanggal 1 via `GenerateMonthlyInvoicesJob`
- Jumlah iuran diambil dari `master_customer_categories.monthly_fee`
- Status awal: `Belum Bayar`
- Jika pelanggan `Nonaktif` atau `Pindah`, tidak di-generate invoice
- Invoice bulan berjalan bisa di-generate ulang jika ada perubahan kategori (pro-rata)

### 3. Payment & Ledger
- Setelah payment diverifikasi (`verified`), otomatis insert ke `financial_ledger` (debit sisi pemasukan)
- Satu payment bisa melunasi beberapa invoice sekaligus (pelanggan menunggak)
- Setelah payment verified, status invoice terkait berubah menjadi `Lunas`

### 4. Bank Sampah — Alur Setoran
```
Nasabah datang / dijemput
  → Buat WasteBankDeposit (header)
  → Tambah WasteBankDepositDetail per jenis sampah
  → System hitung total nilai (berat × harga per kg dari master_waste_types.default_price)
  → System buat entry WasteBankLedger (credit nasabah)
  → Saldo nasabah bertambah
```

### 5. Pickup Activity Log (Scan QR)
- Saat petugas scan QR Code pelanggan: buat `pickup_activity_logs` dengan status `Terangkut`
- Jika tidak ada di rumah / tidak ada sampah: status `Tidak Ada Sampah`
- Jika pelanggan komplain di tempat: buat `complaints` dari log ini

### 6. SLA Keluhan
- `master_complaint_types.SLA_response_hour` adalah batas jam untuk respon pertama
- `master_complaint_types.SLA_resolution_hour` adalah batas jam untuk penyelesaian penuh
- Jika melewati SLA dan `escalation_required = true`, notifikasi otomatis ke Manajemen

### 7. Statistik Operasional
- Di-generate otomatis setiap hari pukul 23:55 via `AggregateStatisticsJob`
- **Tidak boleh diedit manual** — hanya di-generate ulang via job
- Formula `waste_diversion_rate`:
  ```
  (total_waste_collected - total_residual_waste) / total_waste_collected * 100
  ```

### 8. QR Code Pelanggan
- Di-generate saat pelanggan pertama kali dibuat
- Berisi URL verifikasi: `{APP_URL}/verify/customer/{customer_code}`
- Disimpan sebagai PNG di `storage/app/public/qrcodes/{customer_code}.png`
- Tidak berubah meskipun data pelanggan berubah

### 9. Sampah Khusus (special_waste_requests)
- Pelanggan request via portal atau app
- Memerlukan approval dari Operator/Manajemen
- Biaya dihitung terpisah dari iuran bulanan
- Harus dijadwalkan dalam `pickup_schedules` tersendiri

### 10. Dokumen Adat (Sampah Upacara)
- Kategori sampah `Khusus` + `Bunga upacara` / `Sampah hajatan` / `Sampah upacara adat`
- Memerlukan koordinasi dengan `kelian_adat`
- Sering bersifat musiman dan volume besar

---

## Pola Kode yang Harus Diikuti

### Model — Cara Mendefinisikan Relasi

```php
// app/Models/Finance/MonthlyInvoice.php
class MonthlyInvoice extends Model {
    use HasAuditLog;

    protected $primaryKey = 'invoice_id';
    protected $guarded    = ['invoice_id'];

    protected $casts = [
        'invoice_status' => InvoiceStatus::class,  // Selalu cast ke Enum
        'invoice_month'  => 'date:Y-m',
        'amount'         => 'decimal:2',
    ];

    // Relasi: selalu sertakan foreign key eksplisit
    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class, 'invoice_id', 'invoice_id');
    }

    // Scope yang sering digunakan
    public function scopeUnpaid(Builder $query): Builder {
        return $query->where('invoice_status', InvoiceStatus::Unpaid);
    }

    public function scopeForMonth(Builder $query, string $yearMonth): Builder {
        return $query->where('invoice_month', $yearMonth);
    }
}
```

### Service — Logika Bisnis

```php
// app/Services/Finance/InvoiceGeneratorService.php
class InvoiceGeneratorService {
    public function generateForMonth(string $yearMonth): int {
        $generatedCount = 0;

        // Hanya generate untuk pelanggan aktif
        $customers = Customer::where('customer_status', CustomerStatus::Active)
            ->with('category')
            ->get();

        foreach ($customers as $customer) {
            // Skip jika sudah ada invoice bulan ini
            if (MonthlyInvoice::where('customer_id', $customer->customer_id)
                ->forMonth($yearMonth)
                ->exists()) {
                continue;
            }

            MonthlyInvoice::create([
                'invoice_number'  => $this->generateInvoiceNumber($customer, $yearMonth),
                'customer_id'     => $customer->customer_id,
                'invoice_month'   => $yearMonth,
                'amount'          => $customer->category->monthly_fee,
                'invoice_status'  => InvoiceStatus::Unpaid->value,
                'due_date'        => Carbon::parse($yearMonth)->endOfMonth(),
            ]);

            $generatedCount++;
        }

        return $generatedCount;
    }

    private function generateInvoiceNumber(Customer $customer, string $yearMonth): string {
        $month = Carbon::parse($yearMonth)->format('Ym');
        $seq   = MonthlyInvoice::forMonth($yearMonth)->count() + 1;
        return "INV-{$month}-" . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }
}
```

### Controller — Hanya Delegasi

```php
// app/Http/Controllers/Finance/MonthlyInvoiceController.php
class MonthlyInvoiceController extends Controller {
    public function __construct(
        private readonly InvoiceGeneratorService $invoiceService,
        private readonly PaymentService $paymentService,
    ) {}

    public function index(Request $request): Response {
        $invoices = MonthlyInvoice::query()
            ->with(['customer', 'payments'])
            ->when($request->search, fn ($q) => $q->whereHas('customer', fn ($q) => $q->where('full_name', 'ilike', "%{$request->search}%")))
            ->when($request->status, fn ($q) => $q->where('invoice_status', $request->status))
            ->when($request->month, fn ($q) => $q->forMonth($request->month))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Finance/MonthlyInvoices/Index', [
            'invoices' => MonthlyInvoiceResource::collection($invoices),
            'filters'  => $request->only(['search', 'status', 'month']),
        ]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse {
        $this->invoiceService->generateForMonth($request->validated('month'));
        return redirect()->route('monthly-invoices.index')->with('success', 'Tagihan berhasil di-generate');
    }
}
```

### API Resource — Transformasi Data

```php
// app/Http/Resources/Finance/MonthlyInvoiceResource.php
class MonthlyInvoiceResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'invoice_id'     => $this->invoice_id,
            'invoice_number' => $this->invoice_number,
            'invoice_month'  => $this->invoice_month->format('Y-m'),
            'amount'         => (float) $this->amount,
            'amount_display' => 'Rp ' . number_format($this->amount, 0, ',', '.'),
            'invoice_status' => $this->invoice_status->value,
            'status_color'   => $this->invoice_status->color(),
            'due_date'       => $this->due_date?->format('d/m/Y'),
            'customer'       => new CustomerBriefResource($this->whenLoaded('customer')),
        ];
    }
}
```

---

## Penggunaan PostGIS

### Cara Benar Menyimpan Koordinat

```php
// Saat membuat Customer baru dengan koordinat
public function create(array $data): Customer {
    $customer = Customer::create($data);

    // Update field PostGIS geography setelah insert
    DB::statement(
        "UPDATE customers 
         SET location = ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography
         WHERE customer_id = ?",
        [$data['longitude'], $data['latitude'], $customer->customer_id]
    );

    return $customer->fresh();
}
```

### Cara Query Dalam Radius

```php
// Cari pelanggan dalam radius 1km dari armada
$nearbyCustomers = Customer::whereRaw(
    "ST_DWithin(
        location::geography,
        ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography,
        ?
    )",
    [$vehicleLng, $vehicleLat, 1000] // 1000 = 1km dalam meter
)
->selectRaw("*, ST_Distance(location::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) as distance_meters", [$vehicleLng, $vehicleLat])
->orderBy('distance_meters')
->get();
```

### Cara Simpan Polygon Wilayah

```php
// GeoJSON dikirim dari frontend (dari draw tools di Leaflet)
public function updatePolygon(Region $region, array $geoJsonCoordinates): void {
    DB::statement(
        "UPDATE master_regions 
         SET polygon_area = ST_SetSRID(ST_GeomFromGeoJSON(?), 4326)
         WHERE region_id = ?",
        [json_encode(['type' => 'Polygon', 'coordinates' => $geoJsonCoordinates]), $region->region_id]
    );

    // Simpan raw GeoJSON untuk keperluan rendering frontend
    $region->update(['polygon_geojson' => json_encode($geoJsonCoordinates)]);
}
```

---

## Cara Kerja Audit Log

```
Model::created/updated/deleted (via HasAuditLog trait)
  → AuditService::log($type, $model, $oldValues)
    → Ambil user_id dari auth()->id()
    → Ambil IP dari request()->ip()
    → Simpan ke audit_logs
```

**Jangan** mencatat audit log secara manual jika model sudah pakai `HasAuditLog`. Ini akan menciptakan duplikat.

---

## Cara Kerja Invoice Otomatis

```
Artisan Scheduler (setiap tanggal 1 jam 00:00)
  → GenerateMonthlyInvoicesJob::dispatch()
    → InvoiceGeneratorService::generateForMonth()
      → Loop semua Customer dengan status 'Aktif'
      → Cek apakah invoice bulan ini sudah ada
      → Buat MonthlyInvoice dengan amount dari CustomerCategory.monthly_fee
      → Fire event InvoiceGenerated
        → Listener: kirim notifikasi ke pelanggan
```

---

## Cara Kerja Bank Sampah

```
Nasabah datang ke Bank Sampah / request plastic pickup
  → Petugas buat WasteBankDeposit (header)
    deposit_date, customer_id, unit_id, petugas_id

  → Tambah WasteBankDepositDetail per jenis sampah
    waste_type_id, weight_kg, price_per_kg, subtotal

  → System hitung total_value = SUM(detail.subtotal)

  → System buat WasteBankLedger entry
    transaction_type = 'CREDIT'
    amount = total_value
    balance = saldo_sebelumnya + total_value

  → Status deposit = 'Completed'
```

---

## Cara Kerja QR Code Pelanggan

```
CustomerService::create($data)
  → Customer::create($data) — dapat customer_id
  → Generate QR Code
      content = "{APP_URL}/verify/customer/{customer_code}"
      format  = PNG, size = 300px
  → Simpan ke storage/app/public/qrcodes/{customer_code}.png
  → Update customers.qr_code = "qrcodes/{customer_code}.png"
```

**Endpoint verifikasi** (`/verify/customer/{code}`) diakses petugas saat scan QR di lapangan. Endpoint ini mengembalikan data pelanggan + jadwal pengangkutan hari ini + status pembayaran.

---

## Cara Kerja Statistik Operasional

```
Artisan Scheduler (setiap hari jam 23:55)
  → AggregateStatisticsJob::dispatch()
    → StatisticAggregatorService::aggregateForDate(today())
      → Query ke semua modul terkait
      → Hitung semua KPI
      → Upsert ke operational_statistics (update jika sudah ada, insert jika belum)
```

Statistik juga di-generate ulang setiap kali ada data besar yang berubah (bukan real-time, tapi via job queue).

---

## Pertanyaan Umum & Jawaban

**Q: Bagaimana cara filter pelanggan berdasarkan wilayah?**
A: Join ke `master_regions` via `customers.region_id`. Bisa juga filter menggunakan PostGIS: cari pelanggan yang koordinatnya masuk dalam polygon `master_regions.polygon_area`.

**Q: Apakah satu pelanggan bisa bayar beberapa invoice sekaligus?**
A: Ya. Di tabel `payments`, ada field untuk menyimpan daftar `invoice_ids` yang dilunasi. `PaymentService::verify()` akan mengupdate semua invoice terkait menjadi `Lunas`.

**Q: Bagaimana cara tahu apakah sebuah keluhan sudah melewati SLA?**
A: Bandingkan `complaints.created_at + master_complaint_types.SLA_resolution_hour` dengan waktu sekarang. Jika belum ada `complaint_handlings` dengan status `Selesai`, maka keluhan masih open.

**Q: Apa bedanya `users` dan `workers`?**
A: `users` adalah orang yang bisa **login ke sistem** (admin, kasir, operator yang pakai aplikasi). `workers` adalah petugas lapangan yang **tidak punya akun login** (sopir, tukang angkut sampah) — datanya hanya untuk keperluan absensi dan penugasan.

**Q: Apakah `master_regions` terhubung ke `Banjar` di Bali?**
A: Ya. `region_type = 'Banjar'` merepresentasikan Banjar Dinas di Desa Gulingan. Saat ini ada beberapa banjar yang menjadi wilayah layanan TPS 3R Sapuh Jagat. Data ini harus di-seed dari data desa.

**Q: Bagaimana cara generate surat otomatis?**
A: Gunakan `DocumentGeneratorService::generate($templateId, $variables)`. Service ini akan mengambil template dari `document_templates`, replace placeholder dengan nilai dari `$variables`, render ke PDF via DomPDF, simpan ke storage, dan insert ke `generated_documents`.

**Q: Apakah ada rate limit API?**
A: Untuk endpoint web (Inertia) tidak. Untuk endpoint API mobile (`/api/v1/...`), ada rate limit 60 request/menit per user. Konfigurasi di `config/iswara.php`.

**Q: Bagaimana cara kerja notifikasi real-time?**
A: Gunakan Laravel Reverb (WebSocket). Event di-broadcast via `NotificationService::send()`. Di frontend, gunakan `useRealtime()` hook yang sudah subscribe ke channel `notifications.{user_id}`.

---

*Dokumen ini adalah referensi utama untuk AI agent. Jika ada ambiguitas, selalu rujuk ke `ISWARA_TPS_3R_SAPUH_JAGAT_GULINGAN_V3.docx` sebagai sumber kebenaran requirement.*