# Master Project Timeline: ISWARA TPS 3R Sapuh Jagat Gulingan V2
**Periode Pengerjaan:** 8 Juni 2026 - 31 Desember 2026 (29 Minggu)
**Tim Pengembang:** 7 Orang (2 UI/UX Designer, 5 Full-Stack Developer)

---

## 📅 JUNI 2026 (Minggu 1 - 4)
### Fokus: Modul 1 (Manajemen Master Data - Bagian 1) & Setup Infrastruktur
Bulan pertama difokuskan pada setup awal, arsitektur dasar, dan master data tahap pertama (Autentikasi, Wilayah, dan Infrastruktur Jalan).

*   **Minggu 1 (8 - 14 Jun):** 
    *   **Perencanaan:** Analisis kebutuhan keseluruhan, setup repository Git, dan setup server staging (Docker).
    *   **Desain:** Wireframing Master Role dan Master User.
*   **Minggu 2 (15 - 21 Jun):** 
    *   **Pengembangan:** API Autentikasi (JWT), CRUD Master Role, CRUD Master User.
    *   **Frontend:** Slicing UI Dashboard Admin, halaman Login/Register.
*   **Minggu 3 (22 - 28 Jun):** 
    *   **Desain:** Wireframing Master Wilayah dan Master Jalan & Gang.
    *   **Pengembangan:** Integrasi Frontend manajemen User dan Role.
*   **Minggu 4 (29 Jun - 5 Jul):** 
    *   **Pengembangan:** API & Frontend untuk Master Wilayah dan Master Jalan.
    *   **Testing:** Pengujian integrasi modul autentikasi dan wilayah.

---

## 📅 JULI 2026 (Minggu 5 - 8)
### Fokus: Modul 1 (Manajemen Master Data - Bagian 2) & Finalisasi Modul 1
Melanjutkan pengembangan master data yang lebih spesifik untuk kebutuhan operasional TPS 3R hingga tuntas.

*   **Minggu 5 (6 - 12 Jul):**
    *   **Desain:** Wireframing Tempat Khusus dan Jenis Usaha.
    *   **Pengembangan:** API & Frontend Master Tempat Khusus dan Jenis Usaha.
*   **Minggu 6 (13 - 19 Jul):**
    *   **Desain:** Wireframing Metode Pembayaran dan Kategori Penerima Manfaat.
    *   **Pengembangan:** API & Frontend Master Metode Pembayaran dan Kategori Pelanggan.
*   **Minggu 7 (20 - 26 Jul):**
    *   **Desain:** Wireframing Jenis Sampah, Jenis Keluhan, dan Jenis Aset.
    *   **Pengembangan:** API & Frontend Master Jenis Sampah, Keluhan, dan Aset.
*   **Minggu 8 (27 Jul - 2 Ags):**
    *   **Testing & Evaluasi:** End-to-end integrasi seluruh fitur Modul 1.
    *   **Deployment:** Dockerisasi awal Modul 1 ke server Staging.

---

## 📅 AGUSTUS 2026 (Minggu 9 - 12)
### Fokus: Modul 2 (Penerima Manfaat & SDM) & Modul 3 (Aset & Armada)
Pengembangan bergeser ke pendataan pelanggan (penerima manfaat), integrasi data kepegawaian (absensi), serta manajemen inventaris TPS 3R.

*   **Minggu 9 (3 - 9 Ags):**
    *   **Desain:** Wireframing UI Penerima Manfaat (Customers) dan Relasi Akun.
    *   **Pengembangan:** API Data Pelanggan dan pembuatan QR Code otomatis per rumah.
*   **Minggu 10 (10 - 16 Ags):**
    *   **Pengembangan:** Frontend Manajemen Pelanggan. API dan UI Modul SDM (Data Petugas & Absensi GPS).
*   **Minggu 11 (17 - 23 Ags):**
    *   **Desain:** Wireframing Modul Aset & Armada.
    *   **Pengembangan:** API & CRUD Manajemen Aset (Assets) dan Inventaris TPS 3R.
*   **Minggu 12 (24 - 30 Ags):**
    *   **Pengembangan:** API & Frontend Data Armada (Kendaraan), integrasi dengan aset, dan pencatatan Jadwal Pemeliharaan (Maintenance).
    *   **Testing:** UAT internal Modul 2 & 3.

---

## 📅 SEPTEMBER 2026 (Minggu 13 - 16)
### Fokus: Modul 4 (Manajemen Pengangkutan Sampah)
Modul kompleksitas tinggi: navigasi, GIS, tracking armada, dan log aktivitas.

*   **Minggu 13 (31 Ags - 6 Sep):**
    *   **Desain:** Wireframing Penjadwalan, Rute Pengangkutan, dan UI Mobile/Web untuk Operator.
    *   **Pengembangan:** Algoritma penyusunan Jadwal Pengangkutan & Request Sampah Khusus.
*   **Minggu 14 (7 - 13 Sep):**
    *   **Pengembangan:** Manajemen Rute (Pickup Routes) dan integrasi awal API Tracking GPS Armada.
*   **Minggu 15 (14 - 20 Sep):**
    *   **Pengembangan:** Log Pengangkutan tiap rumah (Pickup Activity Logs) & Fitur Scan QR Code oleh petugas.
*   **Minggu 16 (21 - 27 Sep):**
    *   **Pengembangan:** Pencatatan Pengeluaran Sampah ke TPA (Landfill Disposals).
    *   **Testing:** Uji Lapangan (Field Testing) sistem Tracking dan QR.

---

## 📅 OKTOBER 2026 (Minggu 17 - 21)
### Fokus: Modul 5 (Keluhan), Modul 6 (Keuangan) & Modul 7 (Pengkomposan TPS 3R)
Bulan dengan intensitas tinggi untuk menyelesaikan siklus pelayanan, penagihan, dan pengolahan internal di TPS 3R.

*   **Minggu 17 (28 Sep - 4 Okt):**
    *   **Pengembangan (Modul 5):** Sistem Ticketing Pelaporan Keluhan (dengan foto/GPS) & Penanganan Keluhan (SLA Monitoring).
*   **Minggu 18 (5 - 11 Okt):**
    *   **Pengembangan (Modul 6):** Algoritma Tagihan Bulanan otomatis (Invoice) berdasarkan kategori pelanggan.
*   **Minggu 19 (12 - 18 Okt):**
    *   **Pengembangan (Modul 6):** API Pembayaran Kasir dan pembentukan Ledger Keuangan.
*   **Minggu 20 (19 - 25 Okt):**
    *   **Pengembangan (Modul 7):** Pencatatan Sampah Masuk (Incoming Waste Logs) dan Manajemen Batch Pengkomposan.
*   **Minggu 21 (26 Okt - 1 Nov):**
    *   **Pengembangan (Modul 7):** Pencatatan Hasil Kompos, Quality Control, Distribusi Penjualan Kompos, dan Testing integrasi dengan Modul 6.

---

## 📅 NOVEMBER 2026 (Minggu 22 - 25)
### Fokus: Modul 8 (Manajemen Bank Sampah Digital)
Pengembangan ekonomi sirkular desa dan sistem daur ulang anorganik.

*   **Minggu 22 (2 - 8 Nov):**
    *   **Desain:** UI Bank Sampah (Admin Banjar & Nasabah).
    *   **Pengembangan:** Setup Data Unit Bank Sampah per Banjar.
*   **Minggu 23 (9 - 15 Nov):**
    *   **Pengembangan:** Transaksi Setoran Bank Sampah (Pencatatan berat dan fluktuasi harga).
*   **Minggu 24 (16 - 22 Nov):**
    *   **Pengembangan:** Pembentukan Ledger Saldo Bank Sampah Nasabah (Debit/Kredit).
*   **Minggu 25 (23 - 29 Nov):**
    *   **Pengembangan:** Fitur Layanan Request Pengambilan Sampah Plastik ke Rumah dan notifikasi ke operator.

---

## 📅 DESEMBER 2026 (Minggu 26 - 29)
### Fokus: Modul 9 (Pendukung), UAT Keseluruhan, & Rilis Produksi
Penyelesaian sistem *analytics*, pengujian akhir, pengamanan sistem, dan peluncuran (*go-live*).

*   **Minggu 26 (30 Nov - 6 Des):**
    *   **Pengembangan (Modul 9):** Manajemen File Media S3, Notifikasi Sistem (Push/WA), dan Audit Logs (Tracking aktivitas user).
*   **Minggu 27 (7 - 13 Des):**
    *   **Pengembangan (Modul 9):** Pembuatan Dashboard Statistik Operasional TV (Heatmap, Tren Pendapatan, Tunggakan).
*   **Minggu 28 (14 - 20 Des):**
    *   **Testing Terpadu (UAT Akhir):** Pengujian aplikasi oleh Desa dan Manajemen TPS 3R secara menyeluruh (Bug Bash).
    *   **Security:** Audit *Role-Based Access* dan optimasi keamanan data.
*   **Minggu 29 (21 - 31 Des):**
    *   **Deployment:** Dockerisasi (*Containerization*) seluruh *service* untuk rilis (Frontend, Backend, DB).
    *   **Go-Live:** Migrasi server Staging ke Production, User Training, dan Serah Terima Sistem.

---
*Catatan: Timeline ini mengasumsikan pengembangan dengan metode Agile di mana desain dan pengembangan berjalan secara paralel. Alokasi 2 bulan (Juni & Juli) untuk Modul 1 sangat ideal mengingat ini adalah fondasi yang harus kuat sebelum modul lain dibangun di atasnya.*
