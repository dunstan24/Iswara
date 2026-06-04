# 🗑️ ISWARA TPS 3R — Sapuh Jagat Gulingan 

> **Sistem Informasi Terpadu Pengelolaan Sampah Berbasis Digital**  
> Platform manajemen TPS 3R (Tempat Pengolahan Sampah Reduce-Reuse-Recycle) yang mencakup pengangkutan, keuangan, bank sampah, dan pengkomposan.

---

## 📋 Ringkasan Proyek

| Atribut | Detail |
|---|---|
| **Nama Sistem** | ISWARA TPS 3R Sapuh Jagat Gulingan V2 |
| **Total Milestone** | 6 Milestone |
| **Durasi Proyek** | 29 Minggu |
| **Periode** | 8 Juni – 31 Desember 2026 |
| **Ukuran Tim** | 7 Orang |

---

## 🗺️ Roadmap Milestone

```
Jun 2026        Jul         Ags         Sep         Okt         Nov         Des
|── MS-1 ──────────|── MS-2 ──|── MS-3 ──|──── MS-4 ────|── MS-5 ──|── MS-6 ──|
  Fondasi &          Pelanggan   Pengangkut  Keuangan &     Bank        Finalisasi
  Infrastruktur      & Aset      an Sampah   Pengolahan     Sampah      & Go-Live
  (Minggu 1–8)       (9–12)      (13–16)     (17–21)        (22–25)     (26–29)
```

---

## 📅 Tabel Milestone

| # | Milestone | Periode | Durasi | Modul | Minggu |
|---|---|---|---|---|---|
| **MS-1** | Fondasi, Infrastruktur & Master Data | 8 Jun – 2 Ags 2026 | 8 minggu | Setup · Auth · Wilayah · M1 Final | Minggu 1–8 |
| **MS-2** | Data Pelanggan & Aset | 3 – 30 Ags 2026 | 4 minggu | Modul 2 · Modul 3 | Minggu 9–12 |
| **MS-3** | Sistem Pengangkutan Sampah | 31 Ags – 27 Sep 2026 | 4 minggu | Modul 4 | Minggu 13–16 |
| **MS-4** | Keuangan & Pengolahan Sampah | 28 Sep – 1 Nov 2026 | 5 minggu | Modul 5 · Modul 6 · Modul 7 | Minggu 17–21 |
| **MS-5** | Bank Sampah Digital | 2 – 29 Nov 2026 | 4 minggu | Modul 8 | Minggu 22–25 |
| **MS-6** | Finalisasi, UAT & Go-Live | 30 Nov – 31 Des 2026 | 4 minggu | Modul 9 · UAT · Deploy | Minggu 26–29 |

---

## 🔍 Detail Deliverable per Milestone

### MS-1 · Fondasi, Infrastruktur & Master Data
**Periode:** 8 Juni – 2 Agustus 2026 | **Durasi:** 8 Minggu | **Minggu:** 1–8

#### 🏗️ Infrastruktur & Perencanaan
- [ ] Analisis kebutuhan keseluruhan & setup repository Git
- [ ] Setup server staging dengan Docker
- [ ] Konfigurasi CI/CD pipeline awal

#### 🔐 Autentikasi & Akses
- [ ] API Autentikasi JWT (login, register, refresh token)
- [ ] CRUD Master Role & Master User
- [ ] Slicing UI Dashboard Admin & halaman Login/Register

#### 🗺️ Data Wilayah
- [ ] API & Frontend Master Wilayah (Banjar/Desa)
- [ ] API & Frontend Master Jalan & Gang
- [ ] Pengujian integrasi end-to-end autentikasi & wilayah

---

### MS-2 · Data Pelanggan & Aset
**Periode:** 3 – 30 Agustus 2026 | **Durasi:** 4 Minggu | **Minggu:** 9–12

#### 👥 Penerima Manfaat & SDM (Modul 2)
- [ ] API Data Pelanggan & QR Code otomatis per rumah
- [ ] Frontend manajemen pelanggan (CRUD + filter wilayah)
- [ ] Modul SDM: Data Petugas & Absensi berbasis GPS

#### 🚛 Aset & Armada (Modul 3)
- [ ] API & CRUD Manajemen Aset & Inventaris TPS 3R
- [ ] API & Frontend Data Armada (Kendaraan)
- [ ] Pencatatan Jadwal Pemeliharaan (Maintenance)
- [ ] UAT internal Modul 2 & 3

---

### MS-3 · Sistem Pengangkutan Sampah
**Periode:** 31 Agustus – 27 September 2026 | **Durasi:** 4 Minggu | **Minggu:** 13–16

#### 📅 Penjadwalan & Routing
- [ ] Algoritma penyusunan Jadwal Pengangkutan otomatis
- [ ] Manajemen Rute Pengangkutan (Pickup Routes)
- [ ] Request Sampah Khusus oleh pelanggan

#### 📍 Tracking & Lapangan
- [ ] Integrasi API Tracking GPS Armada real-time
- [ ] Log Pengangkutan tiap rumah (Pickup Activity Logs)
- [ ] Fitur Scan QR Code oleh petugas di lapangan
- [ ] Pencatatan Pengeluaran Sampah ke TPA (Landfill Disposals)
- [ ] Uji Lapangan sistem Tracking & QR

---

### MS-4 · Keuangan & Pengolahan Sampah
**Periode:** 28 September – 1 November 2026 | **Durasi:** 5 Minggu | **Minggu:** 17–21

> ⚠️ Milestone terpanjang — mencakup 3 modul sekaligus

#### 🎫 Keluhan Pelanggan (Modul 5)
- [ ] Sistem Ticketing Keluhan dengan foto & GPS
- [ ] Penanganan Keluhan & SLA Monitoring

#### 💰 Keuangan (Modul 6)
- [ ] Algoritma Tagihan Bulanan otomatis per kategori pelanggan
- [ ] API Pembayaran Kasir (cash & digital)
- [ ] Pembentukan Ledger Keuangan (debit/kredit)

#### ♻️ Pengkomposan TPS 3R (Modul 7)
- [ ] Pencatatan Sampah Masuk (Incoming Waste Logs)
- [ ] Manajemen Batch Pengkomposan
- [ ] Pencatatan Hasil Kompos & Quality Control
- [ ] Distribusi & Penjualan Kompos + integrasi Modul 6

---

### MS-5 · Bank Sampah Digital
**Periode:** 2 – 29 November 2026 | **Durasi:** 4 Minggu | **Minggu:** 22–25

#### 🏦 Setup & Transaksi
- [ ] Desain UI Bank Sampah (Admin Banjar & Nasabah)
- [ ] Setup Data Unit Bank Sampah per Banjar
- [ ] Transaksi Setoran: pencatatan berat & fluktuasi harga

#### 📒 Ledger & Layanan Jemput
- [ ] Ledger Saldo Nasabah Bank Sampah (Debit/Kredit)
- [ ] Fitur Request Pengambilan Sampah Plastik ke Rumah
- [ ] Notifikasi ke operator lapangan

---

### MS-6 · Finalisasi, UAT & Go-Live
**Periode:** 30 November – 31 Desember 2026 | **Durasi:** 4 Minggu | **Minggu:** 26–29

#### 🔧 Modul Pendukung (Modul 9)
- [ ] Manajemen File Media S3
- [ ] Notifikasi Sistem (Push Notification & WhatsApp)
- [ ] Audit Logs (tracking seluruh aktivitas user)
- [ ] Dashboard Statistik Operasional TV (Heatmap, Tren, Tunggakan)

#### 🧪 Quality Assurance
- [ ] UAT Akhir: Bug Bash oleh Desa & Manajemen TPS 3R
- [ ] Audit Role-Based Access Control
- [ ] Optimasi keamanan & penetration test

#### 🚀 Production Release
- [ ] Dockerisasi seluruh service (Frontend, Backend, DB)
- [ ] Migrasi server Staging ke Production
- [ ] User Training & dokumentasi sistem
- [ ] Serah Terima Sistem

---

## 🧩 Daftar Modul

| Modul | Nama | Milestone |
|---|---|---|
| Setup | Infrastruktur & Repository | MS-1 |
| Auth | Autentikasi & Manajemen Akses | MS-1 |
| Wilayah | Master Data Wilayah | MS-1 |
| Modul 2 | Data Pelanggan & SDM | MS-2 |
| Modul 3 | Aset & Armada | MS-2 |
| Modul 4 | Sistem Pengangkutan Sampah | MS-3 |
| Modul 5 | Keluhan Pelanggan | MS-4 |
| Modul 6 | Keuangan & Pembayaran | MS-4 |
| Modul 7 | Pengkomposan TPS 3R | MS-4 |
| Modul 8 | Bank Sampah Digital | MS-5 |
| Modul 9 | Modul Pendukung & Notifikasi | MS-6 |

---

## 📌 Catatan Penting

- Setiap milestone **berakhir dengan pengujian (testing/UAT)** sebelum lanjut ke milestone berikutnya.
- **MS-4** memiliki durasi terpanjang (5 minggu) karena mencakup 3 modul sekaligus (Modul 5, 6, dan 7).
- Tim terdiri dari **7 orang** dengan total waktu pengerjaan **29 minggu**.

---

## 👥 Tim Proyek

> Tim pengembang terdiri dari **7 orang** yang bekerja selama 29 minggu (8 Juni – 31 Desember 2026).

---

<div align="center">

**6 Milestone · 29 Minggu · 8 Juni – 31 Desember 2026 · Tim: 7 Orang**

*ISWARA TPS 3R Sapuh Jagat Gulingan V2*

</div>
