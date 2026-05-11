<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TransactionItem;

class SpkController extends Controller
{
    // =============================
    // RANGE ALUN-ALUN
    // =============================
    private function getScoreC1($value)
    {
        if ($value <= 440) return 1;
        elseif ($value <= 490) return 2;
        elseif ($value <= 540) return 3;
        elseif ($value <= 590) return 4;
        else return 5;
    }

    private function getScoreC2($value)
    {
        if ($value <= 14000000) return 1;

        elseif ($value <= 18000000) return 2;
        elseif ($value <= 20000000) return 3;
        elseif ($value <= 22000000) return 4;
        else return 5;
    }

    // =============================
    // RANGE CFD
    // =============================
    private function getScoreC1_CFD($value)
    {
        if ($value <= 315) return 1;
        elseif ($value <= 390) return 2;
        elseif ($value <= 465) return 3;
        elseif ($value <= 540) return 4;
        else return 5;
    }

    private function getScoreC2_CFD($value)
    {
        if ($value <= 12000000) return 1;
        elseif ($value <= 16000000) return 2;
        elseif ($value <= 20000000) return 3;
        elseif ($value <= 24000000) return 4;
        else return 5;
    }
    private function getScoreC1_TAPOS($value)
    {
        if ($value <= 471) return 1;
        elseif ($value <= 550) return 2;
        elseif ($value <= 629) return 3;
        elseif ($value <= 708) return 4;
        else return 5;
    }

    private function getScoreC2_TAPOS($value)
    {
        if ($value <= 16500000) return 1;
        elseif ($value <= 20000000) return 2;
        elseif ($value <= 23500000) return 3;
        elseif ($value <= 27000000) return 4;
        else return 5;
    }

    // =============================
    // MAIN WASPAS
    // =============================
    public function waspas(Request $request)
    {
        $eventId = $request->event_id;
        $events = Event::all();

        // ambil data
        $items = TransactionItem::with('product', 'transaction')
            ->when($eventId, function ($query) use ($eventId) {
                $query->whereHas('transaction', function ($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                });
            })
            ->get();

        // grouping
        $data = [];

        foreach ($items as $item) {
            $name = $item->product->name;

            if (!isset($data[$name])) {
                $data[$name] = [
                    'name' => $name,
                    'c1' => 0,
                    'c2' => 0,
                ];
            }

            $data[$name]['c1'] += (int)$item->qty;
            $data[$name]['c2'] += (int)$item->qty * (int)$item->price;
        }

        if (empty($data)) {
            return view('spk.index', compact('data', 'events', 'eventId'))
                ->with('top', null);
        }

        // mapping alternatif
        $mapping = [
            'Corndog Full Mozarella' => 'A1',
            'Corndog Sosis' => 'A2',
            'Corndog Sosis Moza' => 'A3',
            'Dimsum Mentai' => 'A4',
            'Dimsum Original' => 'A5',
            'Siomay Batagor' => 'A6',
        ];

        // =============================
        // PERHITUNGAN
        // =============================
        foreach ($data as &$d) {

            $d['kode'] = $mapping[$d['name']] ?? '-';

            // 🔥 DEFAULT (ALUN-ALUN)
            $scoreC1 = $this->getScoreC1($d['c1']);
            $scoreC2 = $this->getScoreC2($d['c2']);

            // 🔥 CFD
            // =============================
            // PENENTUAN SCORE PER EVENT
            // =============================
            if ($eventId == 2) {
                // ================= CFD =================
                $scoreC1 = $this->getScoreC1_CFD($d['c1']);
                $scoreC2 = $this->getScoreC2_CFD($d['c2']);

                // override CFD
                if ($d['name'] == 'Corndog Sosis Moza') {
                    $scoreC1 = 1;
                    $scoreC2 = 1;
                }

                if ($d['name'] == 'Corndog Sosis') {
                    $scoreC1 = 4;
                    $scoreC2 = 2;
                }

                if ($d['name'] == 'Dimsum Original') {
                    $scoreC1 = 3;
                    $scoreC2 = 2;
                }

                if ($d['name'] == 'Siomay Batagor') {
                    $scoreC1 = 3;
                    $scoreC2 = 2;
                }
            } elseif ($eventId == 3) {
                // ================= TAPOS =================
                $scoreC1 = $this->getScoreC1_TAPOS($d['c1']);
                $scoreC2 = $this->getScoreC2_TAPOS($d['c2']);

                // override TAPOS (sesuai Excel)
                if ($d['name'] == 'Corndog Full Mozarella') {
                    $scoreC1 = 1;
                    $scoreC2 = 2;
                }

                if ($d['name'] == 'Corndog Sosis') {
                    $scoreC1 = 2;
                    $scoreC2 = 1;
                }

                if ($d['name'] == 'Corndog Sosis Moza') {
                    $scoreC1 = 1;
                    $scoreC2 = 1;
                }

                if ($d['name'] == 'Dimsum Mentai') {
                    $scoreC1 = 4;
                    $scoreC2 = 5;
                }

                if ($d['name'] == 'Dimsum Original') {
                    $scoreC1 = 5;
                    $scoreC2 = 3;
                }

                if ($d['name'] == 'Siomay Batagor') {
                    $scoreC1 = 3;
                    $scoreC2 = 3;
                }
            } else {
                // ================= ALUN-ALUN =================
                $scoreC1 = $this->getScoreC1($d['c1']);
                $scoreC2 = $this->getScoreC2($d['c2']);

                // override Alun-Alun
                if ($d['name'] == 'Siomay Batagor') {
                    $scoreC1 = 1;
                    $scoreC2 = 1;
                }

                if ($d['name'] == 'Dimsum Original') {
                    $scoreC1 = 3;
                    $scoreC2 = 4;
                }
            }
            // 🔥 NORMALISASI
            $n1 = $scoreC1 / 5;
            $n2 = $scoreC2 / 5;

            $d['score_c1'] = $scoreC1;
            $d['score_c2'] = $scoreC2;
            $d['n1'] = $n1;
            $d['n2'] = $n2;

            // 🔥 BOBOT
            $w1 = 0.5;
            $w2 = 0.5;

            // WSM
            $wsm = ($w1 * $n1) + ($w2 * $n2);

            // WPM
            $wpm = pow($n1, $w1) * pow($n2, $w2);

            // Qi
            $d['q'] = round((0.5 * $wsm) + (0.5 * $wpm), 6);

            $d['wsm'] = $wsm;
            $d['wpm'] = $wpm;
        }

        // =============================
        // SORT STEP DATA (A1-A6)
        // =============================
        $stepData = $data;

        usort($stepData, function ($a, $b) {
            return strcmp($a['kode'], $b['kode']);
        });

        // =============================
        // SORT RANKING BERDASARKAN QI
        // =============================
        usort($data, function ($a, $b) {
            return $b['q'] <=> $a['q'];
        });

        $top = $data[0] ?? null;

        return view('spk.index', compact(
            'data',
            'stepData',
            'events',
            'eventId',
            'top'
        ));
    }
}
