<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\HistoriesExport;

class ReportController extends Controller
{
    /**
     * Unduh laporan pengguna.
     */
    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users_report.xlsx');
    }

    /**
     * Unduh laporan riwayat.
     */
    public function exportHistories()
    {
        return Excel::download(new HistoriesExport, 'histories_report.xlsx');
    }
}
