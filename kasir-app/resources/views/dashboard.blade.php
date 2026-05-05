@extends('layouts.dashboard')

@section('content')

<div class="space-y-6">

    <!-- 🔥 TOP SECTION -->
    <div class="space-y-6">

        <!-- 🔥 HELLO FULL WIDTH -->
        <div class="bg-[#1e293b] p-6 rounded-2xl border border-gray-700 shadow-lg">
    
            <h2 class="text-2xl font-semibold">
                👋 Hello {{ Auth::user()->name ?? 'Admin' }},
            </h2>
    
            <p class="mt-2 text-gray-400 text-sm">
                Selamat datang kembali. Pantau penjualan dan performa Anda hari ini.
            </p>
    
            <button class="mt-4 bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-lg text-sm">
                Mulai
            </button>
    
        </div>
    </div>

    <div class="grid grid-cols-4 gap-6">

        <!-- ORDERS -->
        <div class="bg-[#1e293b] p-6 rounded-2xl shadow-lg flex justify-between items-center">
            <div>
                <p class="text-gray-400 text-sm">Pesanan</p>
                <h2 class="text-2xl font-bold mt-2">{{ $totalTransaksi }}</h2>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-xl">
                <i data-lucide="shopping-cart" class="w-5 h-5 text-orange-400"></i>
            </div>
        </div>
    
        <!-- REVENUE -->
        <div class="bg-[#1e293b] p-6 rounded-2xl shadow-lg flex justify-between items-center">
            <div>
                <p class="text-gray-400 text-sm">Pendapatan</p>
                <h2 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPenjualan) }}</h2>
            </div>
            <div class="bg-green-500/10 p-3 rounded-xl">
                <i data-lucide="dollar-sign" class="w-5 h-5 text-green-400"></i>
            </div>
        </div>
    
        <!-- STOCK -->
        <div class="bg-[#1e293b] p-6 rounded-2xl shadow-lg flex justify-between items-center">
            <div>
                <p class="text-gray-400 text-sm">Stok</p>
                <h2 class="text-2xl font-bold mt-2">{{ $totalStock }}</h2>
            </div>
            <div class="bg-blue-500/10 p-3 rounded-xl">
                <i data-lucide="package" class="w-5 h-5 text-blue-400"></i>
            </div>
        </div>
    
        <!-- TOP PRODUCT -->
        <div class="bg-[#1e293b] p-6 rounded-2xl shadow-lg flex justify-between items-center">
            <div>
                <p class="text-gray-400 text-sm">Produk Terlaris    </p>
                <h2 class="text-lg font-bold mt-2">
                    {{ Str::limit($topProduct->product->name ?? '-', 15) }}
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    {{ number_format($topProduct->total ?? 0) }} terjual
                </p>
            </div>
            <div class="bg-purple-500/10 p-3 rounded-xl">
                <i data-lucide="trophy" class="w-5 h-5 text-purple-400"></i>
            </div>
        </div>
        <div class="bg-[#1e293b] p-8 rounded-2xl col-span-2 ">

            <h3 class="mb-4 font-semibold">Penjualan Produk</h3>
        
            <div class="h-[250px] flex justify-center">
                <canvas id="donutChart"></canvas>
            </div>
        
            <!-- LIST -->
            @php
$colors = ['#10b981','#f59e0b','#06b6d4','#ef4444','#8b5cf6'];
@endphp

@foreach ($productDonut as $i => $item)
<div class="flex justify-between text-sm">
    <span class="flex items-center gap-2">
        <span class="text-gray-500 text-xs">{{ $i + 1 }}.</span>
        {{ $item->product->name }}
    </span>
    <span class="text-gray-400">
        Rp {{ number_format($item->revenue) }}
    </span>
</div>
@endforeach
        </div>
        <div class="bg-[#1e293b] p-5 rounded-2xl mb-4">

            <h3 class="text-sm font-semibold mb-4">Ringkasan Hari Ini</h3>
        
            <div class="space-y-3 text-sm">
        
                <div class="flex justify-between">
                    <span class="text-gray-400">Jumlah Transaksi</span>
                    <span class="font-semibold">{{ $todayCount }}</span>
                </div>
        
                <div class="flex justify-between">
                    <span class="text-gray-400">Pendapatan</span>
                    <span class="font-semibold">
                        Rp {{ number_format($todayRevenue) }}
                    </span>
                </div>
        
            </div>
        
        </div>
        <div class="bg-[#1e293b] p-6 rounded-2xl shadow-xl">

            <h3 class="text-sm font-semibold mb-5">Aktivitas Hari Ini</h3>
        
            <div class="space-y-5">
        
                @foreach ($todayActivities as $act)
                <div class="flex items-start gap-3">
        
                    <!-- DOT -->
                    <div class="w-2 h-2 mt-2 bg-green-400 rounded-full"></div>
        
                    <!-- TEXT -->
                    <div>
                        <p class="text-sm font-semibold">
                            {{ $act['title'] }}
                        </p>
        
                        <p class="text-xs text-gray-400">
                            {{ $act['subtitle'] }}
                        </p>
        
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $act['time'] }}
                        </p>
                    </div>        
                </div>
                @endforeach
            </div>
        <!-- 🔥 TARUH DI SINI -->
    <a href="/transactions" class="text-xs text-gray-500 mt-4 text-right cursor-pointer hover:text-white">
        View all →</a> 
    
        </div>
    </div>
    
</div>

<style>
    body {
    background: #0f172a;
}

    </style>

@endsection