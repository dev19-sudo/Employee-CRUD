<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\Student;

class ReportController extends Controller
{
    public function download()
    {
        // 1) Fetch data directly (no HTTP call)
        $students = Student::select('rollno', 'name', 'marks', 'dob')->get();

        // 2) Convert to rows for PDF view
        $rows = $students->map(function ($s) {
            return [
                'rollno'     => $s->rollno,
                'name'   => $s->name,
                'marks' => $s->marks,
                'dob' => $s->dob,
            ];
        })->toArray();

        // 3) Create PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Report');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->AddPage();

        // 4) Render Blade HTML
        $html = view('pdf.report', [
            'date' => now()->format('Y-m-d'),
            'rows' => $rows,
        ])->render();

        $pdf->writeHTML($html, true, false, true, false, '');

        // 5) Download
        $filename = 'report_' . now()->format('Ymd_His') . '.pdf';
        return response($pdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
