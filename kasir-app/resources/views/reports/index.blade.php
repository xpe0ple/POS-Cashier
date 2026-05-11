@extends('layouts.dashboard')

@section('content')

<div class="space-y-6">

    <!-- HEADER + FILTER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">Laporan Per Event</h1>
            <p class="text-gray-400 text-sm">Insight penjualan berdasarkan event</p>
        </div>

        <form method="GET" class="flex items-center gap-2">
            <select name="event_id"
                class="bg-gray-800 text-white px-4 py-2 rounded-xl border border-gray-600 focus:ring-2 focus:ring-blue-500">
                
                <option value="">Semua Event</option>

                @foreach($events as $event)
                    <option value="{{ $event->event_id }}"
                        {{ $eventId == $event->event_id ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                @endforeach

            </select>

            <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-xl text-white">
                Filter
            </button>
        </form>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- TOTAL PENDAPATAN -->
        <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700 shadow flex justify-between items-center">
            <div>
                <p class="text-gray-400 text-sm">Total Pendapatan</p>
                <h2 class="text-xl font-bold mt-1">
                    Rp {{ number_format($totalPenjualan,0,',','.') }}
                </h2>
            </div>
    
            <div class="bg-green-500/20 p-3 rounded-xl">
                <i data-lucide="dollar-sign" class="text-green-400 w-6 h-6"></i>
            </div>
        </div>
    
        <!-- JUMLAH TRANSAKSI -->
        <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700 shadow flex justify-between items-center">
            <div>
                <p class="text-gray-400 text-sm">Jumlah Transaksi</p>
                <h2 class="text-xl font-bold mt-1">
                    {{ $totalTransaksi }}
                </h2>
            </div>
    
            <div class="bg-blue-500/20 p-3 rounded-xl">
                <i data-lucide="shopping-cart" class="text-blue-400 w-6 h-6"></i>
            </div>
        </div>
    
        <!-- TOP PRODUK -->
        <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700 shadow flex justify-between items-center">
            <div>
                <p class="text-gray-400 text-sm">Top Produk</p>
                <h2 class="text-lg font-bold mt-2">
                    {{ Str::limit($topProduct->product->name ?? '-', 15) }}
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    {{ number_format($topProduct->total ?? 0) }} terjual
                </p>
            </div>
    
            <div class="bg-purple-500/20 p-3 rounded-xl">
                <i data-lucide="trophy" class="text-purple-400 w-6 h-6"></i>
            </div>
        </div>
    
    </div>
    <!-- CHART -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- TOP 5 PRODUK -->
        <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700 shadow">
            <h2 class="text-lg font-semibold mb-4">🏆 Top 5 Produk</h2>
    
            <div class="space-y-3">
    
                @forelse($top5Products as $i => $p)
    
                <div class="flex items-center justify-between">
    
                    <div class="flex items-center gap-3">
    
                        <span class="text-lg">
                            @if($i == 0) 🥇
                            @elseif($i == 1) 🥈
                            @elseif($i == 2) 🥉
                            @else {{ $i+1 }}
                            @endif
                        </span>
    
                        <div>
                            <p class="font-semibold">
                                {{ $p->product->name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $p->total_sold }} terjual
                            </p>
                        </div>
    
                    </div>
    
                    <span class="text-sm text-gray-300">
                        Rp {{ number_format($p->revenue,0,',','.') }}
                    </span>
    
                </div>
    
                <!-- 🔥 progress per item -->
                <div class="w-full bg-gray-700 h-2 rounded">
                    <div class="bg-green-500 h-2 rounded"
                        style="width: {{ ($p->total_sold / $top5Products->first()->total_sold) * 100 }}%">
                    </div>
                </div>
    
                @empty
                <p class="text-gray-400 text-sm">Belum ada data</p>
                @endforelse
    
            </div>
        </div>
    
        <!-- GRAFIK -->
        <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700 shadow">
            <h2 class="text-lg font-semibold mb-4">📈 Grafik Penjualan</h2>
    
            <div class="h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    
    </div>
    <!-- GRID DONUT + RINGKASAN -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <!-- DONUT -->
    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow md:col-span-2">
        <h2 class="font-semibold mb-4">Penjualan Produk</h2>

        <div class="h-[260px] flex justify-center items-center relative">
            <canvas id="donutChart"></canvas>

            <!-- 🔥 TOTAL DI TENGAH -->
            <div class="absolute text-center">
                <p class="text-gray-400 text-sm">Total</p>
                <h2 class="font-bold text-lg">
                    Rp {{ number_format($totalPenjualan,0,',','.') }}
                </h2>
            </div>
        </div>

        <!-- LIST PRODUK -->
        <div class="mt-5 text-sm space-y-1">
            @foreach($productDonut as $i => $p)
            <div class="flex justify-between text-gray-300">
                <span>{{ $i+1 }}. {{ $p->product->name }}</span>
                <span>Rp {{ number_format($p->revenue,0,',','.') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow">
        <h2 class="text-lg font-semibold mb-4">Laporan Penjualan per Menu</h2>
    
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-white">
                <thead class="text-gray-400 border-b border-gray-700">
                    <tr>
                        <th class="text-left py-2">Menu</th>
                        <th class="text-center py-2">Terjual</th>
                        <th class="text-right py-2">Revenue</th>
                    </tr>
                </thead>
    
                <tbody>
                    @forelse($productSales as $p)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/30">
    
                        <td class="py-2">
                            {{ $p->product->name }}
                        </td>
    
                        <td class="text-center">
                            {{ $p->total_sold }}
                        </td>
    
                        <td class="text-right">
                            Rp {{ number_format($p->revenue,0,',','.') }}
                        </td>
    
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-400">
                            Belum ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow mt-3">
            <h2 class="font-semibold mb-4">Ringkasan Hari Ini</h2>
    
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Jumlah Transaksi</span>
                    <span class="font-semibold">{{ $todayCount ?? 0 }}</span>
                </div>
    
                <div class="flex justify-between">
                    <span class="text-gray-400">Pendapatan</span>
                    <span class="font-semibold">
                        Rp {{ number_format($todayRevenue ?? 0,0,',','.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>


    

    <!-- RINGKASAN -->
    {{-- <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow">
        <h2 class="font-semibold mb-4">Ringkasan Hari Ini</h2>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Jumlah Transaksi</span>
                <span class="font-semibold">{{ $todayCount ?? 0 }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Pendapatan</span>
                <span class="font-semibold">
                    Rp {{ number_format($todayRevenue ?? 0,0,',','.') }}
                </span>
            </div>
        </div>
    </div> --}}

</div>

    <!-- INSIGHT BOX -->
    <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700 shadow">
        <h2 class="text-lg font-semibold mb-2">Insight</h2>

        <ul class="text-sm text-gray-300 space-y-1">
            <li>• Event dengan penjualan tertinggi dapat dilihat dari total revenue</li>
            <li>• Produk terlaris membantu menentukan stok berikutnya</li>
            <li>• Grafik menunjukkan tren penjualan harian</li>
        </ul>
    </div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const data = @json($salesChart);

const ctx = document.getElementById('salesChart').getContext('2d');

// 🔥 gradient area
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: data.map(d => d.date),
        datasets: [{
            label: 'Penjualan',
            data: data.map(d => d.total),

            borderColor: '#3b82f6',
            backgroundColor: gradient,

            fill: true,
            tension: 0.5,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#3b82f6'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#111827',
                titleColor: '#fff',
                bodyColor: '#9ca3af',
                borderColor: '#374151',
                borderWidth: 1
            }
        },

        scales: {
            x: {
                ticks: {
                    color: '#9ca3af'
                },
                grid: {
                    color: 'rgba(255,255,255,0.05)'
                }
            },
            y: {
                ticks: {
                    color: '#9ca3af'
                },
                grid: {
                    color: 'rgba(255,255,255,0.05)'
                }
            }
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {

    const donutData = @json($productDonut);

    if (!donutData || donutData.length === 0) {
        console.log("Data kosong");
        return;
    }

    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: donutData.map(d => d.product?.name ?? 'Unknown'),
            datasets: [{
                data: donutData.map(d => d.revenue),
                backgroundColor: [
                    '#10b981',
                    '#f59e0b',
                    '#06b6d4',
                    '#ef4444',
                    '#8b5cf6'
                ],
                borderWidth: 2
            }]
        },
        options: {
            cutout: '70%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

});
// lucide.createIcons();
</script>

@endsection