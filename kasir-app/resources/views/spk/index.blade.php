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

    <!-- STEP 1 -->
<div class="bg-gray-800 rounded overflow-hidden">
    <div class="p-4 border-b border-gray-700">
        <h2 class="font-semibold text-lg">
            Step 1 - Matriks Keputusan
        </h2>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-700">
            <tr>
                <th class="p-3">Kode</th>
                <th class="p-3">Menu</th>
                <th class="p-3">C1</th>
                <th class="p-3">C2</th>
            </tr>
        </thead>

        <tbody>
            @foreach($stepData as $d)
            <tr class="border-t border-gray-700">
                <td class="p-3">{{ $d['kode'] }}</td>
                <td class="p-3">{{ $d['name'] }}</td>
                <td class="p-3 text-center">{{ $d['c1'] }}</td>
                <td class="p-3 text-center">
                    Rp {{ number_format($d['c2'],0,',','.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- INTERVAL PENILAIAN -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <!-- C1 -->
    <div class="bg-gray-800 rounded overflow-hidden">

        <div class="p-4 border-b border-gray-700">
            <h2 class="font-semibold text-lg">
                Interval Penilaian C1 (Jumlah Terjual)
            </h2>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-3">Interval</th>
                    <th class="p-3 text-center">Nilai Likert</th>
                </tr>
            </thead>

            <tbody>

                @if($eventId == 2)
                    <!-- CFD -->
                    <tr class="border-t border-gray-700">
                        <td class="p-3">≤ 315</td>
                        <td class="p-3 text-center">1</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">316 - 390</td>
                        <td class="p-3 text-center">2</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">391 - 465</td>
                        <td class="p-3 text-center">3</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">466 - 540</td>
                        <td class="p-3 text-center">4</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">> 540</td>
                        <td class="p-3 text-center">5</td>
                    </tr>

                @elseif($eventId == 3)
                    <!-- TAPOS -->
                    <tr class="border-t border-gray-700">
                        <td class="p-3">≤ 471</td>
                        <td class="p-3 text-center">1</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">472 - 550</td>
                        <td class="p-3 text-center">2</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">551 - 629</td>
                        <td class="p-3 text-center">3</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">630 - 708</td>
                        <td class="p-3 text-center">4</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">> 708</td>
                        <td class="p-3 text-center">5</td>
                    </tr>

                @else
                    <!-- ALUN-ALUN -->
                    <tr class="border-t border-gray-700">
                        <td class="p-3">≤ 440</td>
                        <td class="p-3 text-center">1</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">441 - 490</td>
                        <td class="p-3 text-center">2</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">491 - 540</td>
                        <td class="p-3 text-center">3</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">541 - 590</td>
                        <td class="p-3 text-center">4</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">> 590</td>
                        <td class="p-3 text-center">5</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    <!-- C2 -->
    <div class="bg-gray-800 rounded overflow-hidden">

        <div class="p-4 border-b border-gray-700">
            <h2 class="font-semibold text-lg">
                Interval Penilaian C2 (Revenue)
            </h2>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-3">Interval</th>
                    <th class="p-3 text-center">Nilai Likert</th>
                </tr>
            </thead>

            <tbody>

                @if($eventId == 2)
                    <!-- CFD -->
                    <tr class="border-t border-gray-700">
                        <td class="p-3">≤ 12.000.000</td>
                        <td class="p-3 text-center">1</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">13jt - 16jt</td>
                        <td class="p-3 text-center">2</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">17jt - 20jt</td>
                        <td class="p-3 text-center">3</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">21jt - 24jt</td>
                        <td class="p-3 text-center">4</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">> 24jt</td>
                        <td class="p-3 text-center">5</td>
                    </tr>

                @elseif($eventId == 3)
                    <!-- TAPOS -->
                    <tr class="border-t border-gray-700">
                        <td class="p-3">≤ 16.500.000</td>
                        <td class="p-3 text-center">1</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">17jt - 20jt</td>
                        <td class="p-3 text-center">2</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">21jt - 23jt</td>
                        <td class="p-3 text-center">3</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">24jt - 27jt</td>
                        <td class="p-3 text-center">4</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">> 27jt</td>
                        <td class="p-3 text-center">5</td>
                    </tr>

                @else
                    <!-- ALUN-ALUN -->
                    <tr class="border-t border-gray-700">
                        <td class="p-3">≤ 14.000.000</td>
                        <td class="p-3 text-center">1</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">15jt - 18jt</td>
                        <td class="p-3 text-center">2</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">19jt - 20jt</td>
                        <td class="p-3 text-center">3</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">21jt - 22jt</td>
                        <td class="p-3 text-center">4</td>
                    </tr>

                    <tr class="border-t border-gray-700">
                        <td class="p-3">> 22jt</td>
                        <td class="p-3 text-center">5</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

</div>

<div class="bg-yellow-500/10 border border-yellow-500/30 p-4 rounded-xl text-sm">
    <p class="font-semibold mb-2">
        Konversi Skala Likert
    </p>

    <ul class="space-y-1 text-gray-300">
        <li>1 = Sangat Rendah</li>
        <li>2 = Rendah</li>
        <li>3 = Cukup</li>
        <li>4 = Tinggi</li>
        <li>5 = Sangat Tinggi</li>
    </ul>
</div>

<!-- STEP 2 -->
<div class="bg-gray-800 rounded overflow-hidden">
    <div class="p-4 border-b border-gray-700">
        <h2 class="font-semibold text-lg">
            Step 2 - Rating Kecocokan
        </h2>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-700">
            <tr>
                <th class="p-3">Kode</th>
                <th class="p-3">Menu</th>
                <th class="p-3">Score C1</th>
                <th class="p-3">Score C2</th>
            </tr>
        </thead>

        <tbody>
            @foreach($stepData as $d)
            <tr class="border-t border-gray-700">
                <td class="p-3">{{ $d['kode'] }}</td>
                <td class="p-3">{{ $d['name'] }}</td>
                <td class="p-3 text-center">{{ $d['score_c1'] }}</td>
                <td class="p-3 text-center">{{ $d['score_c2'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- STEP 3 -->
<div class="bg-gray-800 rounded overflow-hidden">
    <div class="p-4 border-b border-gray-700">
        <h2 class="font-semibold text-lg">
            Step 3 - Normalisasi
        </h2>
        <div class="bg-blue-500/10 border border-blue-500/20 
        px-3 py-2 rounded-lg mt-2 text-sm text-blue-300 inline-block">
        Rij = Xij / Max(Xij)
        </div>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-700">
            <tr>
                <th class="p-3">Kode</th>
                <th class="p-3">N1</th>
                <th class="p-3">N2</th>
            </tr>
        </thead>

        <tbody>
            @foreach($stepData as $d)
            <tr class="border-t border-gray-700">
                <td class="p-3">{{ $d['kode'] }}</td>
                <td class="p-3 text-center">
                    {{ number_format($d['n1'],3) }}
                </td>
                <td class="p-3 text-center">
                    {{ number_format($d['n2'],3) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- STEP 4 -->
<div class="bg-gray-800 rounded overflow-hidden">

    <div class="p-4 border-b border-gray-700">
        <h2 class="font-semibold text-lg">
            Step 4 - Perhitungan WSM
        </h2>
        <div class="bg-blue-500/10 border border-blue-500/20 
            px-3 py-2 rounded-lg mt-2 text-sm text-blue-300 inline-block">
            WSM = Σ(Xij × Wj)
        </div>
    </div>

    <table class="w-full text-sm">

        <thead class="bg-gray-700">
            <tr>
                <th class="p-3">Kode</th>
                <th class="p-3">Menu</th>
                <th class="p-3 text-center">WSM</th>
            </tr>
        </thead>

        <tbody>
            @foreach($stepData as $d)
            <tr class="border-t border-gray-700">

                <td class="p-3">{{ $d['kode'] }}</td>

                <td class="p-3">{{ $d['name'] }}</td>

                <td class="p-3 text-center">
                    {{ number_format($d['wsm'],3) }}
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<!-- STEP 5 -->
<div class="bg-gray-800 rounded overflow-hidden">

    <div class="p-4 border-b border-gray-700">
        <h2 class="font-semibold text-lg">
            Step 5 - Perhitungan WPM
        </h2>
        <div class="bg-blue-500/10 border border-blue-500/20 
        px-3 py-2 rounded-lg mt-2 text-sm text-blue-300 inline-block">
        WPM = Π(Xij ^ Wj)
        </div>

    </div>

    <table class="w-full text-sm">

        <thead class="bg-gray-700">
            <tr>
                <th class="p-3">Kode</th>
                <th class="p-3">Menu</th>
                <th class="p-3 text-center">WPM</th>
            </tr>
        </thead>

        <tbody>
            @foreach($stepData as $d)
            <tr class="border-t border-gray-700">

                <td class="p-3">{{ $d['kode'] }}</td>

                <td class="p-3">{{ $d['name'] }}</td>

                <td class="p-3 text-center">
                    {{ number_format($d['wpm'],3) }}
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<!-- STEP 6 -->
<div class="bg-gray-800 rounded overflow-hidden">

    <div class="p-4 border-b border-gray-700">
        <h2 class="font-semibold text-lg">
            Step 6 - Perhitungan Qi
        </h2>
        <div class="bg-blue-500/10 border border-blue-500/20 
        px-3 py-2 rounded-lg mt-2 text-sm text-blue-300 inline-block">
            Qi = 0.5(WSM) + 0.5(WPM)
        </div>
    </div>

    <table class="w-full text-sm">

        <thead class="bg-gray-700">
            <tr>
                <th class="p-3">Kode</th>
                <th class="p-3">Menu</th>
                <th class="p-3 text-center">Qi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($stepData as $d)
            <tr class="border-t border-gray-700">

                <td class="p-3">{{ $d['kode'] }}</td>

                <td class="p-3">{{ $d['name'] }}</td>

                <td class="p-3 text-center font-bold text-green-400">
                    {{ number_format($d['q'],3) }}
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
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