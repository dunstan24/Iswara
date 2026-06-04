# 🗑️ ISWARA — Integrated Solution Waste Range

> **TPS 3R Sapuh Jagat, Desa Gulingan, Mengwi**  
> *"Transformasi Digital Bertahap Menuju Smart Waste Management"*

Sistem digital terpadu berbasis web dan mobile untuk mendukung seluruh proses pengelolaan sampah TPS 3R Sapuh Jagat — mulai dari pengelolaan penerima manfaat, pengangkutan, pengolahan sampah, bank sampah, hingga pelaporan dan pengambilan keputusan berbasis data.

---

## 📦 Ruang Lingkup Sistem

**9 Modul · 45 Sub-Modul**

| # | Modul | Sub-Modul |
|---|-------|-----------|
| 1 | **Manajemen Master Data & Autentifikasi** | 11 sub-modul |
| 2 | **Manajemen Penerima Manfaat & SDM** | 4 sub-modul |
| 3 | **Manajemen Aset & Armada** | 3 sub-modul |
| 4 | **Manajemen Pengangkutan Sampah** | 6 sub-modul |
| 5 | **Manajemen Keluhan & Ticketing** | 2 sub-modul |
| 6 | **Manajemen Pembayaran & Keuangan** ⭐ | 3 sub-modul |
| 7 | **Manajemen TPS 3R & Pengkomposan** | 4 sub-modul |
| 8 | **Manajemen Bank Sampah Digital** | 5 sub-modul |
| 9 | **Manajemen Pendukung** | 7 sub-modul |

> ⭐ Modul Keuangan mendapat alokasi waktu pengerjaan lebih panjang karena kompleksitas fitur rekonsiliasi, penagihan, dan ledger keuangan.

---

## 🗓️ Project Timeline

**Periode: Juni – Desember 2026 · Tim: 7 Developer**

### Fase 1 — Pondasi `Jun – Jul`

| Periode | Modul | Keterangan |
|---------|-------|------------|
| Juni | Modul 1 — Master Data & Auth | Role, user, wilayah, jalan, metode pembayaran, dll (11 sub-modul) |
| Juni – Juli | Modul 2 — Penerima Manfaat & SDM | Data penerima manfaat, petugas, absensi (4 sub-modul) |

### Fase 2 — Operasional `Agu – Sep`

| Periode | Modul | Keterangan |
|---------|-------|------------|
| Agustus – September | Modul 3 — Aset & Armada | Data aset, kendaraan, pemeliharaan (3 sub-modul) |
| Agustus – September | Modul 4 — Pengangkutan Sampah | Jadwal, rute, GPS tracking, log per rumah (6 sub-modul) |
| September | Modul 5 — Keluhan & Ticketing | Pelaporan & penanganan keluhan (2 sub-modul) |

### Fase 3 — Keuangan & Produksi `Sep – Nov`

| Periode | Modul | Keterangan |
|---------|-------|------------|
| September – November ⭐ | Modul 6 — Pembayaran & Keuangan | Tagihan bulanan, pembayaran, ledger keuangan (3 sub-modul) |
| Oktober | Modul 7 — TPS 3R & Pengkomposan | Sampah masuk, batch kompos, distribusi (4 sub-modul) |
| Oktober | Modul 8 — Bank Sampah Digital | Unit bank sampah, setoran, ledger saldo (5 sub-modul) |

### Fase 4 — Pendukung & Penutup `Nov – Des`

| Periode | Modul | Keterangan |
|---------|-------|------------|
| November | Modul 9 — Manajemen Pendukung | Notifikasi, audit log, statistik, surat otomatis (7 sub-modul) |
| November – Desember | Revisi & Bug Fix | Perbaikan bug, feedback stakeholder, UAT |

---

## 🏁 Milestones

```
Jun ──── Jul ──── Agu ──── Sep ──── Okt ──── Nov ──── Des
 │        │                 │        │        │        │
MS-1     MS-2              MS-3    MS-4     MS-5     MS-6
```

### MS-1 · Akhir Juni 2026 — Kickoff & Infrastruktur Siap

- [ ] Modul 1 (Master Data & Auth) live
- [ ] Environment dev/staging aktif
- [ ] CI/CD pipeline terkonfigurasi
- [ ] Database schema final

### MS-2 · Akhir Juli 2026 — Modul Pondasi Selesai

- [ ] Modul 1 & 2 fully tested
- [ ] Role-based access control (RBAC) aktif
- [ ] Data onboarding penerima manfaat siap
- [ ] API dokumentasi Modul 1–2 selesai

### MS-3 · Akhir September 2026 — Modul Operasional Selesai

- [ ] Modul 3–5 fully tested
- [ ] GPS tracking armada aktif
- [ ] Sistem ticketing keluhan live
- [ ] Jadwal & rute pengangkutan berjalan

### MS-4 · Akhir Oktober 2026 — Modul Keuangan Selesai ⭐

- [ ] Modul 6 fully tested (unit + integrasi)
- [ ] Rekonsiliasi keuangan tervalidasi
- [ ] Laporan keuangan otomatis berjalan
- [ ] Integrasi metode pembayaran aktif
- [ ] Audit finansial internal selesai

### MS-5 · Akhir November 2026 — Semua Modul Selesai

- [ ] Modul 7–9 fully tested
- [ ] Sistem notifikasi & audit log aktif
- [ ] Generate surat otomatis berjalan
- [ ] Bank sampah digital live
- [ ] Semua modul terintegrasi end-to-end

### MS-6 · Akhir Desember 2026 — Go-Live ISWARA 🚀

- [ ] Bug fix & revisi selesai
- [ ] UAT bersama pengurus TPS 3R Sapuh Jagat selesai
- [ ] Training user selesai
- [ ] Sistem live di production
- [ ] Dokumentasi teknis & panduan user final

---

## 📋 Catatan Pengembangan

- Sprint review dilakukan **setiap 2 minggu**
- Modul 6 wajib melalui **2 siklus testing** (unit test + integrasi penuh) sebelum MS-4
- Modul memiliki dependensi berurutan — pastikan modul prasyarat selesai sebelum memulai modul berikutnya
- Dokumentasi teknis disiapkan paralel dengan pengembangan mulai Oktober
- November–Desember difokuskan pada revisi berdasarkan feedback UAT

---

*Disusun oleh Intercode.id Team · Kantor Desa Gulingan, Mei 2026*
