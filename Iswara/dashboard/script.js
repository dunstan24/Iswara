// Data Tugas Proyek (Modul 1: Manajemen Master Data) - Realistik per Minggu (8 Jun - 31 Jul 2026)
const projectTasks = [
    { 
        id: 1, name: "Minggu 1: Perencanaan & Setup Awal", category: "planning", duration: "8 Jun - 14 Jun 2026", pic: "Semua Tim", isDocker: false,
        subtasks: [
            { name: "Analisis Flowchart Master Role & User", assignee: "UI/UX Designer 1" },
            { name: "Wireframe UI Wilayah & Jalan", assignee: "UI/UX Designer 2" },
            { name: "Setup Skema Database Awal", assignee: "Full-Stack Dev 1" },
            { name: "Setup Struktur Boilerplate Backend", assignee: "Full-Stack Dev 2" },
            { name: "Setup Struktur Boilerplate Frontend", assignee: "Full-Stack Dev 3" },
            { name: "Pembuatan Design System Dasar", assignee: "Full-Stack Dev 4" },
            { name: "Setup Repositori Git & Server Staging", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    },
    { 
        id: 2, name: "Minggu 2: Pengembangan Master Role & User", category: "development", duration: "15 Jun - 21 Jun 2026", pic: "Tim Backend & Design", isDocker: false,
        subtasks: [
            { name: "Prototyping UI Master Role & User", assignee: "UI/UX Designer 1" },
            { name: "Wireframe UI Tempat Khusus & Usaha", assignee: "UI/UX Designer 2" },
            { name: "Pengembangan API CRUD Master Role", assignee: "Full-Stack Dev 1" },
            { name: "Pengembangan API Autentikasi (JWT)", assignee: "Full-Stack Dev 2" },
            { name: "Slicing UI Login & Register", assignee: "Full-Stack Dev 3" },
            { name: "Slicing UI Dashboard Admin", assignee: "Full-Stack Dev 4" },
            { name: "Instalasi Docker Engine di Server Staging", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    },
    { 
        id: 3, name: "Minggu 3: Integrasi User & Master Wilayah", category: "development", duration: "22 Jun - 28 Jun 2026", pic: "Tim Full-Stack", isDocker: true,
        subtasks: [
            { name: "Prototyping UI Wilayah & Jalan", assignee: "UI/UX Designer 1" },
            { name: "Wireframe UI Kategori & Pembayaran", assignee: "UI/UX Designer 2" },
            { name: "Pengembangan API Master Wilayah", assignee: "Full-Stack Dev 1" },
            { name: "Pengembangan API Master Jalan", assignee: "Full-Stack Dev 2" },
            { name: "Integrasi Frontend Autentikasi & Role", assignee: "Full-Stack Dev 3" },
            { name: "Slicing UI Master Wilayah & Jalan", assignee: "Full-Stack Dev 4" },
            { name: "Setup Dockerfile Frontend & Backend (Lokal)", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    },
    { 
        id: 4, name: "Minggu 4: Frontend Wilayah & API Tempat Khusus", category: "development", duration: "29 Jun - 5 Jul 2026", pic: "Tim Full-Stack", isDocker: false,
        subtasks: [
            { name: "Prototyping UI Tempat Khusus & Usaha", assignee: "UI/UX Designer 1" },
            { name: "Wireframe UI Jenis Sampah & Keluhan", assignee: "UI/UX Designer 2" },
            { name: "Pengembangan API Tempat Khusus", assignee: "Full-Stack Dev 1" },
            { name: "Pengembangan API Master Jenis Usaha", assignee: "Full-Stack Dev 2" },
            { name: "Integrasi Frontend Master Wilayah & Jalan", assignee: "Full-Stack Dev 3" },
            { name: "Slicing UI Tempat Khusus & Jenis Usaha", assignee: "Full-Stack Dev 4" },
            { name: "Konfigurasi docker-compose.yml untuk Staging", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    },
    { 
        id: 5, name: "Minggu 5: Master Keuangan & Kategori Pelanggan", category: "development", duration: "6 Jul - 12 Jul 2026", pic: "Tim Full-Stack", isDocker: false,
        subtasks: [
            { name: "Prototyping UI Kategori & Pembayaran", assignee: "UI/UX Designer 1" },
            { name: "Wireframe UI Master Aset & Inventaris", assignee: "UI/UX Designer 2" },
            { name: "Pengembangan API Kategori Pelanggan", assignee: "Full-Stack Dev 1" },
            { name: "Pengembangan API Metode Pembayaran", assignee: "Full-Stack Dev 2" },
            { name: "Integrasi Frontend Tempat Khusus & Usaha", assignee: "Full-Stack Dev 3" },
            { name: "Slicing UI Kategori & Metode Pembayaran", assignee: "Full-Stack Dev 4" },
            { name: "Testing Deployment Awal di Docker Staging", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    },
    { 
        id: 6, name: "Minggu 6: Master Operasional (Sampah & Keluhan)", category: "development", duration: "13 Jul - 19 Jul 2026", pic: "Tim Full-Stack", isDocker: false,
        subtasks: [
            { name: "Prototyping UI Jenis Sampah & Keluhan", assignee: "UI/UX Designer 1" },
            { name: "Prototyping UI Master Aset", assignee: "UI/UX Designer 2" },
            { name: "Pengembangan API Jenis Sampah & Keluhan", assignee: "Full-Stack Dev 1" },
            { name: "Pengembangan API Master Jenis Aset", assignee: "Full-Stack Dev 2" },
            { name: "Integrasi Frontend Kategori & Pembayaran", assignee: "Full-Stack Dev 3" },
            { name: "Slicing UI Jenis Sampah, Keluhan & Aset", assignee: "Full-Stack Dev 4" },
            { name: "Monitoring Performa Container Staging", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    },
    { 
        id: 7, name: "Minggu 7: Penyelesaian Integrasi & UAT", category: "planning", duration: "20 Jul - 26 Jul 2026", pic: "Semua Tim", isDocker: false,
        subtasks: [
            { name: "Review UX Keseluruhan Modul 1", assignee: "UI/UX Designer 1" },
            { name: "Penyusunan Panduan Pengguna (Manual Book)", assignee: "UI/UX Designer 2" },
            { name: "Optimasi Query Database (Indexing)", assignee: "Full-Stack Dev 1" },
            { name: "Security Check (Role Based Access Control)", assignee: "Full-Stack Dev 2" },
            { name: "Integrasi Frontend Sampah, Keluhan & Aset", assignee: "Full-Stack Dev 3" },
            { name: "UAT (User Acceptance Testing) Internal", assignee: "Full-Stack Dev 4" },
            { name: "Bug Fixing & Code Review", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    },
    { 
        id: 8, name: "Minggu 8: Dockerisasi Rilis & Finalisasi", category: "infrastructure", duration: "27 Jul - 31 Jul 2026", pic: "DevOps & Full-Stack", isDocker: true,
        subtasks: [
            { name: "Revisi UI Berdasarkan Hasil UAT", assignee: "UI/UX Designer 1" },
            { name: "Finalisasi Aset Grafis (Ikon/Logo)", assignee: "UI/UX Designer 2" },
            { name: "Perbaikan Bug Backend (Sesuai Laporan UAT)", assignee: "Full-Stack Dev 1" },
            { name: "Perbaikan Bug Backend Lanjutan", assignee: "Full-Stack Dev 2" },
            { name: "Perbaikan Bug Frontend (Sesuai Laporan UAT)", assignee: "Full-Stack Dev 3" },
            { name: "Perbaikan Bug Frontend Lanjutan", assignee: "Full-Stack Dev 4" },
            { name: "Build Image Docker Rilis & Deploy ke Production", assignee: "Full-Stack Dev 5 (DevOps)" }
        ]
    }
];

document.addEventListener('DOMContentLoaded', () => {
    // Set Total Tasks (based on weeks)
    document.getElementById('total-tasks-count').innerText = "8 Minggu";

    renderTasks(projectTasks);
    renderGanttChart();

    // Filter Logic
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Update active class
            filterBtns.forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');

            // Filter data
            const filterValue = e.target.getAttribute('data-filter');
            if (filterValue === 'all') {
                renderTasks(projectTasks);
            } else {
                const filteredTasks = projectTasks.filter(task => task.category === filterValue);
                renderTasks(filteredTasks);
            }
        });
    });

    // Modal Close Logic
    const modal = document.getElementById('task-modal');
    const closeBtn = document.getElementById('close-modal');
    
    closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
});

function openModal(task) {
    const modal = document.getElementById('task-modal');
    
    // Populate Data
    document.getElementById('modal-task-title').innerText = task.name;
    document.getElementById('modal-task-duration').innerText = task.duration;
    document.getElementById('modal-task-pic').innerText = task.pic;
    
    // Populate Subtasks
    const subtaskList = document.getElementById('modal-subtask-list');
    subtaskList.innerHTML = '';
    
    if (task.subtasks && task.subtasks.length > 0) {
        task.subtasks.forEach(sub => {
            const li = document.createElement('li');
            li.className = 'subtask-item';
            li.innerHTML = `
                <span class="subtask-name">${sub.name}</span>
                <span class="subtask-assignee"><i class="fa-solid fa-user"></i> ${sub.assignee}</span>
            `;
            subtaskList.appendChild(li);
        });
    } else {
        subtaskList.innerHTML = '<li style="color: var(--text-secondary); text-align: center; padding: 16px;">Tidak ada detail tugas.</li>';
    }

    modal.classList.add('active');
}

function renderTasks(tasks) {
    const tbody = document.getElementById('task-list-body');
    tbody.innerHTML = '';

    tasks.forEach(task => {
        const tr = document.createElement('tr');

        // Category Badge
        let categoryIcon = '';
        if (task.category === 'planning') categoryIcon = '<i class="fa-solid fa-list-check"></i> Perencanaan';
        if (task.category === 'design') categoryIcon = '<i class="fa-solid fa-pen-nib"></i> Desain';
        if (task.category === 'development') categoryIcon = '<i class="fa-solid fa-code"></i> Development';
        if (task.category === 'infrastructure') categoryIcon = '<i class="fa-brands fa-docker category-docker"></i> Infrastruktur';

        const totalTugas = task.subtasks ? task.subtasks.length : 0;

        tr.innerHTML = `
            <td>
                <strong>${task.name}</strong>
                ${task.isDocker ? '<span style="color: var(--docker-color); font-size: 0.8rem; margin-left: 8px;"><i class="fa-brands fa-docker"></i></span>' : ''}
            </td>
            <td><span class="category-badge">${categoryIcon}</span></td>
            <td>${task.pic}</td>
            <td><span style="background: rgba(255,255,255,0.05); padding: 4px 10px; border-radius: 12px; font-size: 0.8rem;">${totalTugas} Tugas</span></td>
        `;
        
        // Add click event for modal
        tr.addEventListener('click', () => openModal(task));
        
        tbody.appendChild(tr);
    });
}

function renderGanttChart() {
    const container = document.getElementById('gantt-container');
    container.innerHTML = ''; // Clear existing
    
    // Gantt Chart visualization for 8 weeks
    const ganttData = [
        { label: "Minggu 1: Perencanaan", class: "bar-planning", width: "12.5%", left: "0%" },
        { label: "Minggu 2: API & Design", class: "bar-dev", width: "12.5%", left: "12.5%" },
        { label: "Minggu 3: Integrasi & Docker", class: "bar-docker", width: "12.5%", left: "25%" },
        { label: "Minggu 4: Fitur Wilayah & Usaha", class: "bar-dev", width: "12.5%", left: "37.5%" },
        { label: "Minggu 5: Keuangan & Kategori", class: "bar-dev", width: "12.5%", left: "50%" },
        { label: "Minggu 6: Sampah & Keluhan", class: "bar-dev", width: "12.5%", left: "62.5%" },
        { label: "Minggu 7: UAT & Review", class: "bar-planning", width: "12.5%", left: "75%" },
        { label: "Minggu 8: Final Deploy", class: "bar-docker", width: "12.5%", left: "87.5%" }
    ];

    ganttData.forEach(item => {
        const row = document.createElement('div');
        row.className = 'gantt-row';
        
        row.innerHTML = `
            <div class="gantt-label">${item.label}</div>
            <div class="gantt-track">
                <div class="gantt-bar ${item.class}" style="width: ${item.width}; left: ${item.left};">
                    ${item.label}
                </div>
            </div>
        `;
        container.appendChild(row);
    });
}
