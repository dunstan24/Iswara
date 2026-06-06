# Panduan Otorisasi (Spatie Permission)

ISWARA menggunakan package `spatie/laravel-permission` untuk mengatur hak akses pengguna. Sistem ini memisahkan konsep antara **Role** (Jabatan/Peran) dan **Permission** (Hak Spesifik).

## 1. Konsep Dasar

- **Permission (Hak Spesifik)**: Tindakan tunggal yang boleh dilakukan. *Contoh: `view-complaints`, `create-complaints`, `delete-users`*.
- **Role (Peran)**: Kumpulan dari beberapa *Permission*. *Contoh: `admin`, `operator`, `kesling`*.
- **User (Pengguna)**: Diberikan *Role* (dan/atau *Permission* langsung), sehingga mewarisi hak akses tersebut.

## 2. Struktur Model

Kita memiliki model khusus `App\Models\Master\Role` yang merupakan ekstensi dari bawaan Spatie, ditambah dengan `HasAuditLog` untuk merekam siapa yang mengubah data role:

```php
use App\Models\Master\Role;

// Membuat role baru
$role = Role::create(['name' => 'manajer']);

// Membuat permission baru
use Spatie\Permission\Models\Permission;
Permission::create(['name' => 'approve-payments']);

// Menghubungkan Role dengan Permission
$role->givePermissionTo('approve-payments');
```

## 3. Penggunaan pada User (Backend)

Model `App\Models\User` sudah dilengkapi dengan trait `HasRoles`.

```php
$user = auth()->user();

// Memberikan role ke user
$user->assignRole('admin');

// Mengecek role
if ($user->hasRole('operator')) {
    // Lakukan sesuatu...
}

// Mengecek permission secara langsung (SANGAT DISARANKAN)
if ($user->can('create-complaints')) {
    // Eksekusi jika diizinkan
}
```

## 4. Melindungi Route & Controller

Gunakan middleware bawaan Spatie pada deklarasi `Route` di dalam folder `routes/modules/`.

```php
// Melindungi route agar hanya bisa diakses user dengan permission tertentu
Route::get('/complaints/create', [ComplaintController::class, 'create'])
    ->middleware('permission:create-complaints');

// Bisa juga diproteksi via role (meski proteksi via permission lebih fleksibel)
Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])
    ->middleware('role:admin');
```

## 5. Penggunaan pada Frontend (Inertia + React/TSX)

Di sisi Frontend (TSX), kita menggunakan kustom *hooks* `usePermission()` untuk merender UI secara kondisional. Data permissions dikirim ke *frontend* secara global melalui `HandleInertiaRequests` middleware.

```tsx
import usePermission from '@/Hooks/usePermission';

export default function ActionButtons() {
    const { can, hasRole } = usePermission();

    return (
        <div>
            {/* Tombol hanya muncul jika punya hak akses 'edit-complaints' */}
            {can('edit-complaints') && (
                <button className="btn-primary">Edit Keluhan</button>
            )}

            {/* Cek role admin */}
            {hasRole('admin') && (
                <span className="badge">Administrator</span>
            )}
        </div>
    );
}
```

## 6. Seeding Data Awal

Setiap penambahan *Role* atau *Permission* baru pada proses *development* **WAJIB** diregistrasikan ke dalam file `database/seeders/Master/RoleSeeder.php` agar sinkron dengan seluruh *environment* tim.

```php
// database/seeders/Master/RoleSeeder.php
public function run(): void {
    $permissions = ['view-complaints', 'create-complaints'];
    
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $adminRole->givePermissionTo(Permission::all());
}
```

---

> [!WARNING]
> **Praktik Terbaik:** Saat membatasi fitur atau logika bisnis, biasakan mengecek `can('nama-permission')` daripada `hasRole('nama-role')`. Hal ini memudahkan jika di masa depan ada role baru yang juga diizinkan melakukan fitur yang sama.
