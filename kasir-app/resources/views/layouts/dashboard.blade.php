<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/png" 
      href="{{ asset('logo.png') }}">
</head>

<body class="bg-[#0f172a] text-white">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-[#111827] border-r border-gray-700 p-4 flex flex-col justify-between transition-all duration-300">

        <div>
            <!-- LOGO + TOGGLE -->
            <div class="flex items-center justify-between mb-8">
                <a href="/dashboard" class="flex items-center gap-2">
                    <img src="{{ asset('logo.png') }}" class="w-7 h-7 rounded-full shadow-sm">
                    <span class="sidebar-text text-lg font-semibold">MouwMouw</span>
                </a>

                <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white">
                    <i data-lucide="menu"></i>
                </button>
            </div>

            <!-- MENU -->
            <nav class="space-y-2">

                <!-- DASHBOARD -->
                <a href="/dashboard"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                    {{ request()->is('dashboard') ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white' : 'text-gray-400 hover:bg-gray-700' }}">
                    
                    <i data-lucide="home"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            
                <!-- PRODUCT -->
                <a href="/products"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                    {{ request()->is('products*') ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white' : 'text-gray-400 hover:bg-gray-700' }}">
                    
                    <i data-lucide="box"></i>
                    <span class="sidebar-text">Produk</span>
                </a>

                <a href="/events"
                class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                {{ request()->is('events*') ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white' : 'text-gray-400 hover:bg-gray-700' }}">
                
                <i data-lucide="calendar"></i>
                <span class="sidebar-text">Event</span>
            </a>
            
                <!-- REPORT -->
                <a href="/reports"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                    {{ request()->is('reports') ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white' : 'text-gray-400 hover:bg-gray-700' }}">
                    
                    <i data-lucide="bar-chart-3"></i>
                    <span class="sidebar-text">Laporan</span>
                </a>
            
                <!-- SPK -->
                <a href="/spk"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl w-full
                    {{ request()->is('spk') ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white' : 'text-gray-400 hover:bg-gray-700' }}">
                    <i data-lucide="trophy"></i>
                    <span class="sidebar-text">SPK</span>
                </a>
            
            </nav>
        </div>

        <div>
            <p class="text-sm text-gray-400 sidebar-text">Sedang Aktif</p>
            <p class="font-semibold sidebar-text">{{ Auth::user()->name }}</p>
        
            <form method="POST" action="/logout" class="mt-4">
                @csrf
                <button class="flex items-center gap-2 w-full px-4 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span class="sidebar-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <main id="main-content" class="ml-64 flex-1 p-10 transition-all duration-300">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8">
            <div class="relative">

                <!-- BUTTON -->
                <button onclick="toggleDropdown()" class="flex items-center gap-3 bg-[#1e293b] px-3 py-2 rounded-xl hover:bg-gray-800 transition">
            
                    <!-- AVATAR -->
                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
            
                    <!-- NAME -->
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">
                            {{ Auth::user()->role ?? 'User' }}
                        </p>
                    </div>
            
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                </button>
            
                <!-- DROPDOWN -->
                <div id="dropdownMenu" class="hidden absolute right-0 mt-3 w-48 bg-[#1e293b] border border-gray-700 rounded-xl shadow-xl overflow-hidden">
            
                    <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-800 text-sm">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Profile
                    </a>
            
                    <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-800 text-sm">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        Settings
                    </a>
            
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="w-full flex items-center gap-2 px-4 py-2 hover:bg-red-500/20 text-sm text-left">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
            
                </div>
            
            </div>
            <div>
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-gray-400 text-sm text-center">Pantau Insight Bisnis Anda</p>
            </div>

            <div class="text-sm text-gray-400">
                {{ Auth::user()->email ?? 'admin@gmail.com' }}
            </div>
        </div>

        @yield('content')

    </main>

</div>

<!-- STYLE -->
<style>

#sidebar {
    transition: width 0.3s ease;
}

.sidebar-text {
    transition: opacity 0.2s ease;
}

.sidebar-collapsed {
    width: 80px !important;
}

.sidebar-collapsed .sidebar-text {
    opacity: 0;
    pointer-events: none;
}
.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 10px;
    color: #9ca3af;
    transition: all 0.2s;
}
.menu-item:hover {
    background: #1f2937;
    color: white;
}
.menu-item.active {
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
    color: white;
}

/* COLLAPSE MODE */
.collapsed {
    width: 80px !important;
}
.collapsed .sidebar-text {
    display: none;
}
</style>

<!-- SCRIPT -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('main-content');
    const texts = document.querySelectorAll('.sidebar-text');

    if (sidebar.classList.contains('w-64')) {
        // 🔽 COLLAPSE
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');

        content.classList.remove('ml-64');
        content.classList.add('ml-20');

        texts.forEach(el => el.classList.add('hidden'));

        localStorage.setItem('sidebar', 'collapsed');
    } else {
        // 🔼 EXPAND
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-64');

        content.classList.remove('ml-20');
        content.classList.add('ml-64');

        texts.forEach(el => el.classList.remove('hidden'));

        localStorage.setItem('sidebar', 'expanded');
    }

    // cukup 1x aja
    document.querySelectorAll('.sidebar-text').forEach(el => {
        el.classList.toggle('hidden');
    });

    // toggle text
    document.querySelectorAll('.sidebar-text').forEach(el => {
        el.classList.toggle('hidden');
    });
}

window.onload = function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('main-content');
    const texts = document.querySelectorAll('.sidebar-text');

    let state = localStorage.getItem('sidebar');

    if (state === 'collapsed') {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');

        content.classList.remove('ml-64');
        content.classList.add('ml-20');

        texts.forEach(el => el.classList.add('hidden'));
    } else {
        texts.forEach(el => el.classList.remove('hidden'));
    }
}

// 🔥 icon lucide
lucide.createIcons();
    function toggleDropdown() {
    document.getElementById('dropdownMenu').classList.toggle('hidden');
}

// klik luar = close
window.addEventListener('click', function(e) {
    const button = document.querySelector('[onclick="toggleDropdown()"]');
    const menu = document.getElementById('dropdownMenu');

    if (!button.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
    }
});

document.addEventListener("DOMContentLoaded", function () {

const data = @json($productDonut ?? []);
// const text = "Rp " + totalRevenue.toLocaleString('id-ID');
const labels = data.map(i => i.product.name);
const values = data.map(i => i.total);
const revenues = data.map(i => i.revenue);


const totalRevenue = revenues.reduce((a, b) => a + Number(b), 0);

// 🔥 PLUGIN TEXT TENGAH
const centerText = {
    id: 'centerText',
    beforeDraw(chart) {
        const { width, height } = chart;
        const ctx = chart.ctx;

        ctx.save();

        // label kecil
        ctx.font = "13px sans-serif";
        ctx.fillStyle = "#9ca3af";
        ctx.textAlign = "center";
        ctx.fillText("Total", width / 2, height / 2 - 10);

        // angka utama
        ctx.font = "bold 20px sans-serif";
        ctx.fillStyle = "#ffffff";

        const totalRevenue = revenues.reduce((a, b) => a + Number(b), 0);
        const text = "Rp " + totalRevenue.toLocaleString('id-ID');

        ctx.fillText(text, width / 2, height / 2 + 15);

        ctx.restore();
    }
};

new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            data: values,
            backgroundColor: [
                '#10b981',
                '#f59e0b',
                '#06b6d4',
                '#ef4444',
                '#8b5cf6'
            ],
            borderWidth: 0
        }]
    },
    options: {
        cutout: '75%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.raw;
                        const total = values.reduce((a, b) => a + b, 0);
                        const percent = ((value / total) * 100).toFixed(1);
                        return context.label + ' - ' + percent + '%';
                    }
                }
            }
        }
    },
    plugins: [centerText]
});

});

const centerText = {
    id: 'centerText',
    beforeDraw(chart) {
        const { width, height } = chart;
        const ctx = chart.ctx;

        ctx.save();

        // TEXT ATAS
        ctx.font = "14px sans-serif";
        ctx.fillStyle = "#9ca3af";
        ctx.textAlign = "center";
        ctx.fillText("Total", width / 2, height / 2 - 10);

        // TEXT BAWAH (ANGKA)
        ctx.font = "bold 18px sans-serif";
        ctx.fillStyle = "#ffffff";

        const text = "Rp " + totalRevenue.toLocaleString('id-ID');

        ctx.fillText(text, width / 2, height / 2 + 15);

        ctx.restore();
    }
};
</script>

</body>
</html>