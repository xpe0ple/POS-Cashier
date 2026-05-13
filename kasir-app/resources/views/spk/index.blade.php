@extends('layouts.dashboard')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

    .spk-root {
        font-family: 'Syne', sans-serif;
        color: #e2e8f0;
        --accent: #38bdf8;
        --accent-2: #a78bfa;
        --green: #4ade80;
        --gold: #fbbf24;
        --bg-card: rgba(15, 23, 42, 0.85);
        --border: rgba(56, 189, 248, 0.12);
        --border-strong: rgba(56, 189, 248, 0.28);
        --mono: 'JetBrains Mono', monospace;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* ── HEADER ── */
    .spk-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--border-strong);
        flex-wrap: wrap;
        gap: 1rem;
    }
    .spk-title {
        font-size: 1.6rem; font-weight: 800;
        letter-spacing: -0.02em;
        background: linear-gradient(120deg, #e2e8f0 0%, var(--accent) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .spk-title span {
        display: inline-block;
        background: linear-gradient(120deg, var(--accent) 0%, var(--accent-2) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .spk-form { display: flex; align-items: center; gap: 0.75rem; }
    .select-wrap { position: relative; }
    .select-icon {
        position: absolute; left: 0.75rem; top: 50%;
        transform: translateY(-50%); font-size: 0.9rem; pointer-events: none;
    }
    .spk-select {
        appearance: none;
        background: rgba(15,23,42,0.95); color: #e2e8f0;
        border: 1px solid var(--border-strong);
        padding: 0.55rem 2.4rem 0.55rem 2.1rem;
        border-radius: 10px;
        font-family: 'Syne', sans-serif; font-size: 0.875rem;
        outline: none; cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .spk-select:focus, .spk-select:hover {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(56,189,248,0.12);
    }
    .select-arrow {
        position: absolute; right: 0.75rem; top: 50%;
        transform: translateY(-50%); font-size: 0.6rem;
        color: #64748b; pointer-events: none;
    }
    .spk-btn {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
        color: #0f172a; font-family: 'Syne', sans-serif;
        font-weight: 700; font-size: 0.875rem;
        padding: 0.55rem 1.25rem; border-radius: 10px;
        border: none; cursor: pointer; letter-spacing: 0.03em;
        transition: transform 0.15s, box-shadow 0.15s;
        box-shadow: 0 4px 15px rgba(56,189,248,0.25);
    }
    .spk-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(56,189,248,0.35); }

    /* ── SUMMARY ── */
    .summary-grid {
        display: grid; grid-template-columns: repeat(4,1fr);
        gap: 1rem; margin-bottom: 2rem;
    }
    @media(max-width:900px){.summary-grid{grid-template-columns:repeat(2,1fr);}}
    @media(max-width:540px){.summary-grid{grid-template-columns:1fr;}}
    .summary-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 14px; padding: 1.25rem 1.4rem;
        position: relative; overflow: hidden;
        backdrop-filter: blur(8px);
        transition: border-color 0.2s, transform 0.2s;
    }
    .summary-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        opacity: 0; transition: opacity 0.2s;
    }
    .summary-card:hover::before { opacity: 1; }
    .summary-card:hover { border-color: var(--border-strong); transform: translateY(-2px); }
    .summary-label {
        font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em;
        text-transform: uppercase; color: #64748b; margin-bottom: 0.5rem;
    }
    .summary-value {
        font-size: 1.2rem; font-weight: 800; color: #f1f5f9;
        font-family: var(--mono);
    }

    /* ── STEPPER NAV ── */
    .stepper-nav {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    align-items: center;
    gap: 0;
    margin-bottom: 1.75rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 0.5rem;

    overflow: hidden; /* ⬅️ ubah ini */
    flex-wrap: wrap; /* ⬅️ biar turun kalau sempit */
}
    .step-tab {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.55rem 1rem; border-radius: 10px;
        font-size: 0.78rem; font-weight: 700; letter-spacing: 0.04em;
        color: #475569; cursor: pointer; white-space: nowrap;
        border: none; background: transparent;
        font-family: 'Syne', sans-serif;
        transition: background 0.2s, color 0.2s;
        position: relative;
        flex-shrink: 0;
    }
    .step-tab:hover { color: #94a3b8; background: rgba(255,255,255,0.03); }
    .step-tab.active {
        background: rgba(56,189,248,0.1);
        color: var(--accent);
        border: 1px solid rgba(56,189,248,0.25);
    }
    .step-tab .tab-num {
        width: 1.4rem; height: 1.4rem;
        border-radius: 50%;
        background: rgba(100,116,139,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem; font-family: var(--mono); font-weight: 700;
        color: #64748b; flex-shrink: 0;
        transition: background 0.2s, color 0.2s;
    }
    .step-tab.active .tab-num {
        background: var(--accent); color: #0f172a;
    }
    .step-tab.done .tab-num {
        background: rgba(74,222,128,0.15); color: var(--green);
    }
    .step-tab.done { color: #4ade80; }
    .step-divider {
        flex: 1;
    min-width: 10px;
    height: 1px;
    background: var(--border);
    }

    /* ── SLIDE PANELS ── */
    .slide-panel { display: none; }
    .slide-panel.active {
        display: block;
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── SHARED CARD ── */
    .spk-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        backdrop-filter: blur(8px);
    }
    .spk-card-header {
        padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
    }
    .spk-card-title { font-size: 1rem; font-weight: 700; color: #f1f5f9; }
    .step-badge {
        display: inline-flex; align-items: center;
        background: rgba(56,189,248,0.1);
        border: 1px solid rgba(56,189,248,0.25);
        color: var(--accent); font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.08em; padding: 0.2rem 0.65rem;
        border-radius: 999px; text-transform: uppercase;
    }
    .formula-chip {
        display: inline-block;
        background: rgba(56,189,248,0.07);
        border: 1px solid rgba(56,189,248,0.2);
        color: #7dd3fc; font-family: var(--mono);
        font-size: 0.78rem; padding: 0.25rem 0.75rem; border-radius: 8px;
    }

    /* ── TABLE ── */
    .spk-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .spk-table thead tr { background: rgba(15,23,42,0.7); }
    .spk-table th {
        padding: 0.75rem 1.1rem; text-align: left;
        font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em;
        text-transform: uppercase; color: #64748b; white-space: nowrap;
    }
    .spk-table th.center, .spk-table td.center { text-align: center; }
    .spk-table tbody tr { border-top: 1px solid var(--border); transition: background 0.15s; }
    .spk-table tbody tr:hover { background: rgba(56,189,248,0.04); }
    .spk-table td { padding: 0.75rem 1.1rem; color: #cbd5e1; }
    .mono-val { font-family: var(--mono); font-size: 0.82rem; }
    .qi-val { font-family: var(--mono); font-size: 0.85rem; font-weight: 700; color: var(--green); }
    .score-pill {
        display: inline-block;
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: #bfdbfe; font-family: var(--mono);
        font-size: 0.75rem; padding: 0.2rem 0.65rem; border-radius: 6px;
    }
    .rank-medal { font-size: 1.1rem; }
    .rank-num {
        display: inline-flex; width: 1.6rem; height: 1.6rem;
        align-items: center; justify-content: center;
        background: rgba(100,116,139,0.15); border-radius: 50%;
        font-size: 0.75rem; color: #64748b; font-weight: 700;
        font-family: var(--mono);
    }

    /* ── INTERVAL GRID ── */
    .interval-grid {
        display: grid; grid-template-columns: repeat(2,1fr); gap: 1rem;
    }
    @media(max-width:700px){.interval-grid{grid-template-columns:1fr;}}
    .interval-val {
        display: inline-block;
        background: rgba(167,139,250,0.1);
        border: 1px solid rgba(167,139,250,0.2);
        color: #c4b5fd; font-family: var(--mono);
        font-size: 0.75rem; padding: 0.15rem 0.55rem; border-radius: 6px;
    }

    /* ── LIKERT BOX ── */
    .likert-grid { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem; }
    .likert-item {
        display: flex; align-items: center; gap: 0.5rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 8px; padding: 0.35rem 0.85rem;
        font-size: 0.8rem; color: #94a3b8;
    }
    .likert-num { font-family: var(--mono); font-weight: 700; color: var(--gold); }

    /* ── NAV BUTTONS ── */
    .slide-footer {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 1.5rem;
    }
    .nav-btn {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.6rem 1.25rem; border-radius: 10px;
        font-family: 'Syne', sans-serif; font-size: 0.85rem; font-weight: 700;
        cursor: pointer; border: 1px solid var(--border-strong);
        background: transparent; color: #94a3b8;
        transition: all 0.2s;
    }
    .nav-btn:hover { background: rgba(56,189,248,0.07); color: var(--accent); border-color: var(--accent); }
    .nav-btn:disabled { opacity: 0.3; cursor: not-allowed; }
    .nav-btn.primary {
        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        color: #0f172a; border-color: transparent;
        box-shadow: 0 4px 15px rgba(56,189,248,0.25);
    }
    .nav-btn.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(56,189,248,0.35); }
    .slide-counter {
        font-family: var(--mono); font-size: 0.78rem; color: #475569;
    }
    .slide-counter span { color: var(--accent); font-weight: 700; }
</style>

<div class="spk-root">

    <!-- HEADER -->
    <div class="spk-header">
        <h1 class="spk-title">SPK Menu Terlaris &nbsp;<span>WASPAS</span></h1>
        <form method="GET" action="/spk" class="spk-form">
            <div class="select-wrap">
                <span class="select-icon">📍</span>
                <select name="event_id" class="spk-select">
                    <option value="">Pilih Event</option>
                    @foreach($events as $event)
                    <option value="{{ $event->event_id }}" {{ $eventId == $event->event_id ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                    @endforeach
                </select>
                <span class="select-arrow">▼</span>
            </div>
            <button class="spk-btn">Hitung</button>
        </form>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">🏆 Top Menu</div>
            <div class="summary-value">{{ $top['name'] ?? '-' }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">💰 Revenue</div>
            <div class="summary-value">Rp {{ number_format(collect($data)->sum('c2'),0,',','.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">📦 Terjual</div>
            <div class="summary-value">{{ collect($data)->sum('c1') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">🔄 Transaksi</div>
            <div class="summary-value">—</div>
        </div>
    </div>

    <!-- STEPPER NAV -->
    <div class="stepper-nav" id="stepperNav">
        <button class="step-tab active" onclick="goTo(0)" data-step="0">
            <span class="tab-num">1</span> Matriks
        </button>
        {{-- <div class="step-divider"></div> --}}
        <button class="step-tab" onclick="goTo(1)" data-step="1">
            <span class="tab-num">2</span> Interval
        </button>
        {{-- <div class="step-divider"></div> --}}
        <button class="step-tab" onclick="goTo(2)" data-step="2">
            <span class="tab-num">3</span> Rating
        </button>
        {{-- <div class="step-divider"></div> --}}
        <button class="step-tab" onclick="goTo(3)" data-step="3">
            <span class="tab-num">4</span> Normalisasi
        </button>
        {{-- <div class="step-divider"></div> --}}
        <button class="step-tab" onclick="goTo(4)" data-step="4">
            <span class="tab-num">5</span> WSM
        </button>
        {{-- <div class="step-divider"></div> --}}
        <button class="step-tab" onclick="goTo(5)" data-step="5">
            <span class="tab-num">6</span> WPM
        </button>
        {{-- <div class="step-divider"></div> --}}
        <button class="step-tab" onclick="goTo(6)" data-step="6">
            <span class="tab-num">7</span> Qi
        </button>
        {{-- <div class="step-divider"></div> --}}
        <button class="step-tab" onclick="goTo(7)" data-step="7">
            <span class="tab-num">🏅</span> Ranking
        </button>
    </div>

    <!-- ── SLIDE 0: Matriks Keputusan ── -->
    <div class="slide-panel active" id="slide-0">
        <div class="spk-card">
            <div class="spk-card-header">
                <span class="step-badge">Step 1</span>
                <span class="spk-card-title">Matriks Keputusan</span>
            </div>
            <table class="spk-table">
                <thead>
                    <tr>
                        <th>Kode</th><th>Menu</th>
                        <th class="center">C1 (Terjual)</th>
                        <th class="center">C2 (Revenue)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stepData as $d)
                    <tr>
                        <td><span class="mono-val">{{ $d['kode'] }}</span></td>
                        <td>{{ $d['name'] }}</td>
                        <td class="center mono-val">{{ $d['c1'] }}</td>
                        <td class="center mono-val">Rp {{ number_format($d['c2'],0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SLIDE 1: Interval + Likert ── -->
    <div class="slide-panel" id="slide-1">
        <div class="interval-grid" style="margin-bottom:1.25rem;">
            <div class="spk-card">
                <div class="spk-card-header">
                    <span class="spk-card-title">Interval C1 — Jumlah Terjual</span>
                </div>
                <table class="spk-table">
                    <thead><tr><th>Interval</th><th class="center">Likert</th></tr></thead>
                    <tbody>
                        @if($eventId == 2)
                            <tr><td><span class="interval-val">≤ 315</span></td><td class="center mono-val">1</td></tr>
                            <tr><td><span class="interval-val">316 – 390</span></td><td class="center mono-val">2</td></tr>
                            <tr><td><span class="interval-val">391 – 465</span></td><td class="center mono-val">3</td></tr>
                            <tr><td><span class="interval-val">466 – 540</span></td><td class="center mono-val">4</td></tr>
                            <tr><td><span class="interval-val">&gt; 540</span></td><td class="center mono-val">5</td></tr>
                        @elseif($eventId == 3)
                            <tr><td><span class="interval-val">≤ 471</span></td><td class="center mono-val">1</td></tr>
                            <tr><td><span class="interval-val">472 – 550</span></td><td class="center mono-val">2</td></tr>
                            <tr><td><span class="interval-val">551 – 629</span></td><td class="center mono-val">3</td></tr>
                            <tr><td><span class="interval-val">630 – 708</span></td><td class="center mono-val">4</td></tr>
                            <tr><td><span class="interval-val">&gt; 708</span></td><td class="center mono-val">5</td></tr>
                        @else
                            <tr><td><span class="interval-val">≤ 440</span></td><td class="center mono-val">1</td></tr>
                            <tr><td><span class="interval-val">441 – 490</span></td><td class="center mono-val">2</td></tr>
                            <tr><td><span class="interval-val">491 – 540</span></td><td class="center mono-val">3</td></tr>
                            <tr><td><span class="interval-val">541 – 590</span></td><td class="center mono-val">4</td></tr>
                            <tr><td><span class="interval-val">&gt; 590</span></td><td class="center mono-val">5</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="spk-card">
                <div class="spk-card-header">
                    <span class="spk-card-title">Interval C2 — Revenue</span>
                </div>
                <table class="spk-table">
                    <thead><tr><th>Interval</th><th class="center">Likert</th></tr></thead>
                    <tbody>
                        @if($eventId == 2)
                            <tr><td><span class="interval-val">≤ 12.000.000</span></td><td class="center mono-val">1</td></tr>
                            <tr><td><span class="interval-val">13jt – 16jt</span></td><td class="center mono-val">2</td></tr>
                            <tr><td><span class="interval-val">17jt – 20jt</span></td><td class="center mono-val">3</td></tr>
                            <tr><td><span class="interval-val">21jt – 24jt</span></td><td class="center mono-val">4</td></tr>
                            <tr><td><span class="interval-val">&gt; 24jt</span></td><td class="center mono-val">5</td></tr>
                        @elseif($eventId == 3)
                            <tr><td><span class="interval-val">≤ 16.500.000</span></td><td class="center mono-val">1</td></tr>
                            <tr><td><span class="interval-val">17jt – 20jt</span></td><td class="center mono-val">2</td></tr>
                            <tr><td><span class="interval-val">21jt – 23jt</span></td><td class="center mono-val">3</td></tr>
                            <tr><td><span class="interval-val">24jt – 27jt</span></td><td class="center mono-val">4</td></tr>
                            <tr><td><span class="interval-val">&gt; 27jt</span></td><td class="center mono-val">5</td></tr>
                        @else
                            <tr><td><span class="interval-val">≤ 14.000.000</span></td><td class="center mono-val">1</td></tr>
                            <tr><td><span class="interval-val">15jt – 18jt</span></td><td class="center mono-val">2</td></tr>
                            <tr><td><span class="interval-val">19jt – 20jt</span></td><td class="center mono-val">3</td></tr>
                            <tr><td><span class="interval-val">21jt – 22jt</span></td><td class="center mono-val">4</td></tr>
                            <tr><td><span class="interval-val">&gt; 22jt</span></td><td class="center mono-val">5</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="spk-card" style="padding:1.25rem 1.5rem;">
            <div style="font-weight:700;font-size:0.875rem;color:var(--gold);margin-bottom:0.75rem;">⚖️ Konversi Skala Likert</div>
            <div class="likert-grid">
                <div class="likert-item"><span class="likert-num">1</span> Sangat Rendah</div>
                <div class="likert-item"><span class="likert-num">2</span> Rendah</div>
                <div class="likert-item"><span class="likert-num">3</span> Cukup</div>
                <div class="likert-item"><span class="likert-num">4</span> Tinggi</div>
                <div class="likert-item"><span class="likert-num">5</span> Sangat Tinggi</div>
            </div>
        </div>
    </div>

    <!-- ── SLIDE 2: Rating Kecocokan ── -->
    <div class="slide-panel" id="slide-2">
        <div class="spk-card">
            <div class="spk-card-header">
                <span class="step-badge">Step 2</span>
                <span class="spk-card-title">Rating Kecocokan</span>
            </div>
            <table class="spk-table">
                <thead>
                    <tr>
                        <th>Kode</th><th>Menu</th>
                        <th class="center">Score C1</th>
                        <th class="center">Score C2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stepData as $d)
                    <tr>
                        <td><span class="mono-val">{{ $d['kode'] }}</span></td>
                        <td>{{ $d['name'] }}</td>
                        <td class="center mono-val">{{ $d['score_c1'] }}</td>
                        <td class="center mono-val">{{ $d['score_c2'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SLIDE 3: Normalisasi ── -->
    <div class="slide-panel" id="slide-3">
        <div class="spk-card">
            <div class="spk-card-header">
                <span class="step-badge">Step 3</span>
                <span class="spk-card-title">Normalisasi</span>
                <span class="formula-chip">Rij = Xij / Max(Xij)</span>
            </div>
            <table class="spk-table">
                <thead>
                    <tr><th>Kode</th><th class="center">N1</th><th class="center">N2</th></tr>
                </thead>
                <tbody>
                    @foreach($stepData as $d)
                    <tr>
                        <td><span class="mono-val">{{ $d['kode'] }}</span></td>
                        <td class="center mono-val">{{ number_format($d['n1'],3) }}</td>
                        <td class="center mono-val">{{ number_format($d['n2'],3) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SLIDE 4: WSM ── -->
    <div class="slide-panel" id="slide-4">
        <div class="spk-card">
            <div class="spk-card-header">
                <span class="step-badge">Step 4</span>
                <span class="spk-card-title">Perhitungan WSM</span>
                <span class="formula-chip">WSM = Σ(Xij × Wj)</span>
            </div>
            <table class="spk-table">
                <thead>
                    <tr><th>Kode</th><th>Menu</th><th class="center">WSM</th></tr>
                </thead>
                <tbody>
                    @foreach($stepData as $d)
                    <tr>
                        <td><span class="mono-val">{{ $d['kode'] }}</span></td>
                        <td>{{ $d['name'] }}</td>
                        <td class="center mono-val">{{ number_format($d['wsm'],3) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SLIDE 5: WPM ── -->
    <div class="slide-panel" id="slide-5">
        <div class="spk-card">
            <div class="spk-card-header">
                <span class="step-badge">Step 5</span>
                <span class="spk-card-title">Perhitungan WPM</span>
                <span class="formula-chip">WPM = Π(Xij ^ Wj)</span>
            </div>
            <table class="spk-table">
                <thead>
                    <tr><th>Kode</th><th>Menu</th><th class="center">WPM</th></tr>
                </thead>
                <tbody>
                    @foreach($stepData as $d)
                    <tr>
                        <td><span class="mono-val">{{ $d['kode'] }}</span></td>
                        <td>{{ $d['name'] }}</td>
                        <td class="center mono-val">{{ number_format($d['wpm'],3) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SLIDE 6: Qi ── -->
    <div class="slide-panel" id="slide-6">
        <div class="spk-card">
            <div class="spk-card-header">
                <span class="step-badge">Step 6</span>
                <span class="spk-card-title">Perhitungan Qi</span>
                <span class="formula-chip">Qi = 0.5(WSM) + 0.5(WPM)</span>
            </div>
            <table class="spk-table">
                <thead>
                    <tr><th>Kode</th><th>Menu</th><th class="center">Qi</th></tr>
                </thead>
                <tbody>
                    @foreach($stepData as $d)
                    <tr>
                        <td><span class="mono-val">{{ $d['kode'] }}</span></td>
                        <td>{{ $d['name'] }}</td>
                        <td class="center"><span class="qi-val">{{ number_format($d['q'],3) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SLIDE 7: Ranking ── -->
    <div class="slide-panel" id="slide-7">
        <div class="spk-card">
            <div class="spk-card-header">
                <span class="spk-card-title">🏅 Hasil Peringkat Akhir</span>
            </div>
            <table class="spk-table">
                <thead>
                    <tr>
                        <th>Rank</th><th>Menu</th>
                        <th class="center">Score (Qi)</th>
                        <th class="center">Terjual</th>
                        <th class="center">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr>
                        <td>
                            @if($loop->iteration == 1) <span class="rank-medal">🥇</span>
                            @elseif($loop->iteration == 2) <span class="rank-medal">🥈</span>
                            @elseif($loop->iteration == 3) <span class="rank-medal">🥉</span>
                            @else <span class="rank-num">{{ $loop->iteration }}</span>
                            @endif
                        </td>
                        <td>{{ $d['name'] }}</td>
                        <td class="center"><span class="score-pill">{{ number_format($d['q'],3) }}</span></td>
                        <td class="center mono-val">{{ $d['c1'] }}</td>
                        <td class="center mono-val">Rp {{ number_format($d['c2'],0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- FOOTER NAV -->
    <div class="slide-footer">
        <button class="nav-btn" id="prevBtn" onclick="navigate(-1)" disabled>
            ← Sebelumnya
        </button>
        <span class="slide-counter">
            Langkah <span id="currentNum">1</span> / <span id="totalNum">8</span>
        </span>
        <button class="nav-btn primary" id="nextBtn" onclick="navigate(1)">
            Berikutnya →
        </button>
    </div>

</div>

<script>
    const TOTAL = 8;
    let current = 0;

    function goTo(index) {
        const panels = document.querySelectorAll('.slide-panel');
        const tabs   = document.querySelectorAll('.step-tab');

        panels.forEach((p, i) => p.classList.toggle('active', i === index));

        tabs.forEach((t, i) => {
            t.classList.remove('active', 'done');
            if (i === index) t.classList.add('active');
            else if (i < index) t.classList.add('done');
        });

        current = index;
        document.getElementById('currentNum').textContent = current + 1;
        document.getElementById('prevBtn').disabled = current === 0;
        document.getElementById('nextBtn').disabled = current === TOTAL - 1;

        if (current === TOTAL - 1) {
            document.getElementById('nextBtn').textContent = '✓ Selesai';
        } else {
            document.getElementById('nextBtn').textContent = 'Berikutnya →';
        }
    }

    function navigate(dir) {
        const next = current + dir;
        if (next >= 0 && next < TOTAL) goTo(next);
    }

    goTo(0);
</script>

@endsection