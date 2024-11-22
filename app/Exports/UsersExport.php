<?php


namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Mengambil data untuk laporan.
     */
    public function collection()
    {
        // Menggunakan eager loading untuk menghitung total_distance dan total_duration
        return User::with('histories')
            ->withSum('histories as total_distance', 'distance') // Total distance
            ->with(['histories' => function ($query) {
                // Menghitung total_duration dengan SUM dan mengubahnya ke format waktu
                $query->selectRaw('user_id, SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as total_duration')
                    ->groupBy('user_id');
            }])
            ->get();
    }

    /**
     * Header untuk laporan Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'ID',
            'Name',
            'Company Name',
            'Total Distance (km)',
            'Total Duration',
        ];
    }

    /**
     * Mapping data ke format yang diinginkan.
     */
    public function map($user): array
    {
        static $rowNumber = 1; // Inisialisasi nomor urut

        // Mengambil total_duration yang dihitung
        $totalDuration = $user->histories->first() ? $user->histories->first()->total_duration : '00:00:00';

        return [
            $rowNumber++,   // Nomor urut
            $user->id,
            $user->name,
            $user->company_name,
            number_format($user->total_distance, 2), // Format total distance to 2 decimal places
            $totalDuration,
        ];
    }

    /**
     * Styling untuk laporan, termasuk header.
     */
    public function styles(Worksheet $sheet)
    {
        // Menyesuaikan ukuran kolom secara manual
        $sheet->getColumnDimension('A')->setWidth(5);  // Kolom "No"
        $sheet->getColumnDimension('B')->setWidth(10); // Kolom "ID"
        $sheet->getColumnDimension('C')->setWidth(25); // Kolom "Name"
        $sheet->getColumnDimension('D')->setWidth(30); // Kolom "Company Name"
        $sheet->getColumnDimension('E')->setWidth(25); // Kolom "Total Distance"
        $sheet->getColumnDimension('F')->setWidth(25); // Kolom "Total Duration"

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
