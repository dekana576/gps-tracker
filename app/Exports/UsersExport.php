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
        // Mengambil total_duration yang dihitung
        $totalDuration = $user->histories->first() ? $user->histories->first()->total_duration : '00:00:00';

        return [
            $user->id,
            $user->name,
            $user->company_name,
            $user->total_distance,
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
        $sheet->getColumnDimension('B')->setWidth(20); // Kolom "Name"
        $sheet->getColumnDimension('C')->setWidth(25); // Kolom "Company Name"
        $sheet->getColumnDimension('D')->setWidth(20); // Kolom "Total Distance"
        $sheet->getColumnDimension('E')->setWidth(20); // Kolom "Total Duration"

        // Styling header
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => 'solid',
                    'color' => ['rgb' => 'ADD8E6'],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
        ];
    }
}
