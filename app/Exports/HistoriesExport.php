<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HistoriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Mengambil data untuk laporan.
     */
    public function collection()
    {
        return History::select('username', 'company_name', 'distance', 'duration', 'start_time')->get();
    }

    /**
     * Header untuk laporan Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Username',
            'Company Name',
            'Distance (km)',
            'Duration',
            'Start Time',
        ];
    }

    /**
     * Mapping data ke format yang diinginkan.
     */
    public function map($history): array
    {
        static $rowNumber = 1;


        return [
            $rowNumber++,
            $history->username,
            $history->company_name,
            number_format($history->distance, 2), // Format angka desimal
            $history->duration,
            \Carbon\Carbon::parse($history->start_time)->format('d-m-Y H:i:s'), // Format tanggal
        ];
    }

    /**
     * Styling untuk laporan, termasuk header.
     */
    public function styles(Worksheet $sheet)
    {
        // Menyesuaikan ukuran kolom secara manual
        $sheet->getColumnDimension('A')->setWidth(5);  // Kolom "No"
        $sheet->getColumnDimension('B')->setWidth(20); // Kolom "Username"
        $sheet->getColumnDimension('C')->setWidth(25); // Kolom "Company Name"
        $sheet->getColumnDimension('D')->setWidth(15); // Kolom "Distance"
        $sheet->getColumnDimension('E')->setWidth(15); // Kolom "Duration"
        $sheet->getColumnDimension('F')->setWidth(20); // Kolom "Start Time"

        // Styling header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'], // White font color
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '0070C0'], // Dark blue background
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Add borders to all cells
        $sheet->getStyle('A1:F' . (count($this->collection()) + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Black border
                ],
            ],
        ]);

        // Center align all data
        $sheet->getStyle('A2:F' . (count($this->collection()) + 1))->applyFromArray([
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);
    }
}