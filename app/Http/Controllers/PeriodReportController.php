<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporting;
use App\Models\CaseType;
use DateTime;
use Barryvdh\DomPDF\Facade\Pdf;

class PeriodReportController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Laporan';

        $year = $request->input('year');
        $month = $request->input('month');

        // Semua tahun yang valid
        $validYear = Reporting::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Validasi tahun dan bulan
        $isValidYear = $year && $validYear->contains((int) $year);
        $isValidMonth = $month && is_numeric($month) && $month > 0 && $month <= 12;

        if (($year || $month) && !($isValidYear && $isValidMonth)) {
            abort(404);
        }

        // Total pengaduan per jenis kasus
        $caseTypes = CaseType::withCount(['reportings' => function ($query) use ($year, $month) {
            if ($year) {
                $query->whereYear('created_at', $year);
            }

            if ($month) {
                $query->whereMonth('created_at', $month);
            }
        }])->get();

        return view('period-report.index', compact('title', 'caseTypes', 'year', 'month', 'validYear'));
    }

    public function download(Request $request)
    {
        $title = 'Unduh Laporan';

        $year = $request->input('year');
        $month = $request->input('month');

        // Validasi tahun dan bulan
        $isValidYear = $year && is_numeric($year) && (int) $year <= now()->year; // Pastikan tahun valid dan tidak lebih dari tahun saat ini
        $isValidMonth = $month && is_numeric($month) && $month >= 1 && $month <= 12; // Pastikan bulan valid (1-12)

        if (($year || $month) && !($isValidYear && $isValidMonth)) {
            abort(404);
        }

        if ($year && $month) {
            $period = DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year;
        } elseif ($year) {
            $period = 'Tahun ' . $year;
        } else {
            $period = 'Semua Tahun';
        }

        // Total pengaduan per jenis kasus
        $caseTypes = CaseType::withCount(['reportings' => function ($query) use ($year, $month) {
            if ($year) {
                $query->whereYear('created_at', $year);
            }
            if ($month) {
                $query->whereMonth('created_at', $month);
            }
        }])->get();

        // Generate PDF
        $pdf = Pdf::loadView('period-report.download', compact('title', 'period', 'caseTypes'));

        return $pdf->stream();
    }
}
