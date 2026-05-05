@extends('layouts.dashboard')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-semibold">
            SPK Menu Terlaris (WASPAS)
        </h1>

        <form method="GET" action="/spk" class="flex items-center gap-2 transition-all duration-200 ease-in-out">

            <div class="relative">
                
                <!-- ICON -->
                <span class="absolute left-3 top-2.5 text-gray-400">
                    📍
                </span>
        
                <!-- SELECT -->
                <select name="event_id"
                    class="appearance-none bg-gray-800 text-white border border-gray-600 
                           pl-9 pr-10 py-2 rounded-xl 
                           focus:ring-2 focus:ring-blue-500 focus:outline-none
                           hover:border-blue-400 transition-all duration-200 ease-in-out">
        
                    <option value="">Pilih Event</option>
        
                    @foreach($events as $event)
                    <option value="{{ $event->event_id }}"
                        {{ $eventId == $event->event_id ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                    @endforeach
        
                </select>
        
                <!-- ARROW -->
                <span class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                    ▼
                </span>
        
            </div>
        
            <!-- BUTTON -->
            <button 
                class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-xl text-white 
                       transition-all duration-200 ease-in-out shadow hover:shadow-lg">
                Hitung
            </button>
        
        </form>
    </div>



    <!-- SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-gray-800 p-4 rounded">
            <p class="text-sm text-gray-400">🏆 Top Menu</p>
            <h2 class="font-bold text-lg">
                {{ $top['name'] ?? '-' }}
            </h2>
        </div>

        <div class="bg-gray-800 p-4 rounded">
            <p class="text-sm text-gray-400">💰 Revenue</p>
            <h2 class="font-bold text-lg">
                Rp {{ number_format(collect($data)->sum('c2'),0,',','.') }}
            </h2>
        </div>

        <div class="bg-gray-800 p-4 rounded">
            <p class="text-sm text-gray-400">📦 Terjual</p>
            <h2 class="font-bold text-lg">
                {{ collect($data)->sum('c1') }}
            </h2>
        </div>

        <div class="bg-gray-800 p-4 rounded">
            <p class="text-sm text-gray-400">🔄 Transaksi</p>
            <h2 class="font-bold text-lg">
                -
            </h2>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-gray-800 rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="p-3 text-left">Rank</th>
                    <th class="p-3 text-left">Menu</th>
                    <th class="p-3 text-center">Score</th>
                    <th class="p-3 text-center">Terjual</th>
                    <th class="p-3 text-center">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr class="border-t border-gray-700 hover:bg-gray-700/40">
                
                    <td class="p-3">
                        @if($loop->iteration == 1) 🥇
                        @elseif($loop->iteration == 2) 🥈
                        @elseif($loop->iteration == 3) 🥉
                        @else {{ $loop->iteration }}
                        @endif
                    </td>
                
                    <td class="p-3">{{ $d['name'] }}</td>
                
                    <td class="text-center">
                        <span class="bg-blue-600 px-2 py-1 rounded text-xs">
                            {{ number_format($d['q'],3) }}
                        </span>
                    </td>
                
                    <td class="text-center">{{ $d['c1'] }}</td>
                
                    <td class="text-center">
                        Rp {{ number_format($d['c2'],0,',','.') }}
                    </td>
                
                </tr>
                @endforeach
                </tbody>
        </table>
    </div>

</div>

@endsection