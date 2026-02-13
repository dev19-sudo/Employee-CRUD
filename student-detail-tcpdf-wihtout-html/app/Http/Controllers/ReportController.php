<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\Student;

/**
 * Custom TCPDF class (Header + Colored Table, no HTML)
 */
class MYPDF extends TCPDF
{
    public string $logoPath = '';
    public array $headerLines = [];

    // Custom Header
    public function Header()
    {
        $margins = $this->getMargins();

        // Header top position
        $y = 8;
        $x = $margins['left'];

        // --- Logo (left) ---
        $logoW = 18; // mm
        if (!empty($this->logoPath) && file_exists($this->logoPath)) {
            // Put logo at top-left
            $this->Image($this->logoPath, $x, $y, $logoW, 0, 'PNG');
        }

        // --- Header text (beside logo) ---
        $textX = $x + $logoW + 6; // spacing after logo
        $textY = $y;

        $this->SetXY($textX, $textY);

        // Line 1 (bold)
        $this->SetFont('dejavusans', 'B', 12);
        $this->Cell(0, 6, $this->headerLines[0] ?? '', 0, 1, 'L', false, '', 0, false, 'T', 'M');

        // Line 2
        $this->SetX($textX);
        $this->SetFont('dejavusans', '', 10);
        $this->Cell(0, 5, $this->headerLines[1] ?? '', 0, 1, 'L');

        // Line 3
        $this->SetX($textX);
        $this->SetFont('dejavusans', '', 9);
        $this->Cell(0, 5, $this->headerLines[2] ?? '', 0, 1, 'L');

        // --- Header bottom line (across the page) ---
        $lineY = 26; // adjust if you want line higher/lower
        $this->SetDrawColor(120, 120, 120);
        $this->Line($margins['left'], $lineY, $this->getPageWidth() - $margins['right'], $lineY);
    }

    // Load table data from DB
    public function LoadDataFromDb(): array
    {
        $students = Student::select('rollno', 'name', 'marks', 'dob')
            ->orderBy('rollno')
            ->get();

        $data = [];
        foreach ($students as $s) {
            $data[] = [
                (string) $s->rollno,
                (string) $s->name,
                (string) $s->marks,
                (string) $s->dob,
            ];
        }
        return $data;
    }

    // Colored table (example_011 style)
    public function ColoredTable(array $header, array $data): void
    {
        // Header style (table header)
        $this->SetFillColor(220, 20, 60);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('dejavusans', 'B', 11);

        // Column widths based on usable width (15%,45%,20%,20%)
        $margins = $this->getMargins();
        $usableWidth = $this->getPageWidth() - $margins['left'] - $margins['right'];

        $w = [
            $usableWidth * 0.15, // Roll No.
            $usableWidth * 0.45, // Name
            $usableWidth * 0.20, // Marks
            $usableWidth * 0.20, // DOB
        ];

        // Print header row
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();

        // Restore styles for data rows
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('dejavusans', '', 10);

        $fill = 0;

        foreach ($data as $row) {

            // If close to bottom, add page and repeat table header
            if ($this->GetY() > ($this->getPageHeight() - 20)) {
                $this->AddPage();

                // Re-print table header on new page
                $this->SetFillColor(220, 20, 60);
                $this->SetTextColor(255);
                $this->SetDrawColor(128, 0, 0);
                $this->SetLineWidth(0.3);
                $this->SetFont('dejavusans', 'B', 11);

                for ($i = 0; $i < count($header); $i++) {
                    $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', 1);
                }
                $this->Ln();

                $this->SetFillColor(224, 235, 255);
                $this->SetTextColor(0);
                $this->SetFont('dejavusans', '', 10);
            }

            // Row cells 
            $this->Cell($w[0], 7, $row[0], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 7, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 7, $row[2], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 7, $row[3], 'LR', 0, 'C', $fill);
            $this->Ln();

            $fill = !$fill;
        }

        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

class ReportController extends Controller
{
    public function download()
    {
        // Create PDF
        $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // ---- Document info (BEFORE AddPage) ----
        $pdf->SetTitle('TCPDF Student Report');
        $pdf->SetSubject('DevHarsh Pvt. Llt.');
        $pdf->SetKeywords('Student, PDF, DevHarsh');

        // ---- Header content ----
        $pdf->logoPath = public_path('storage/logo.png'); // C:\...\public\storage\logo.png
        $pdf->headerLines = [
            'TCPDF Student Report',
            'DevHarsh Pvt. Llt.',
            'Student, PDF, DevHarsh',
        ];

        // Margins (top margin should be bigger because header uses space)
        $pdf->SetMargins(10, 32, 10);     // body starts below header
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetFont('dejavusans', '', 10);

        // Add page (Header() will run automatically)
        $pdf->AddPage();

        // Small space below header line
        $pdf->Ln(3);

        // (Optional) Report title + date below header
        $pdf->SetFont('dejavusans', 'B', 25);
        $pdf->SetTextColor(34, 87, 112); 
        $pdf->Cell(0, 8, 'Student Report', 0, 1, 'C');

        $pdf->SetFont('dejavusans', '', 11);
        $pdf->SetTextColor(0, 0, 0); 
        $pdf->Cell(0, 6, 'Date: ' . now()->format('Y-m-d'), 0, 1, 'L');
        $pdf->Ln(2);

        // Table header + data
        $header = ['Roll No.', 'Name', 'Marks', 'DOB'];
        $data = $pdf->LoadDataFromDb();

        // Print table
        $pdf->ColoredTable($header, $data);

        // Force download
        $filename = 'student_report_' . now()->format('Ymd_His') . '.pdf';

        return response($pdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
