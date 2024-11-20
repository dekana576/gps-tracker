<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HistoryController extends Controller
{
    // Method untuk menampilkan halaman index
    public function index()
    {
        return view('history.index');
    }

    // Method untuk mengembalikan data ke DataTables
    public function getHistories(Request $request)
    {
        // Start building the query
        $histories = History::select('id', 'username', 'company_name', 'distance', 'duration', 'start_time');

        // Filter data berdasarkan tanggal
        if ($request->has('date') && !empty($request->date)) {
            $histories->whereDate('start_time', $request->date);
        } else {
            // Secara default, filter data dengan tanggal hari ini
            $histories->whereDate('start_time', now()->toDateString());
        }

        return datatables()->of($histories)
            ->addColumn('actions', function ($history) {
                return '
                    <a href="/admin/history/' . $history->id . '" class="btn btn-info btn-sm">Lihat Polyline</a>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="' . $history->id . '">Hapus</button>
                ';
            })  
            ->rawColumns(['actions']) // Agar HTML di kolom "actions" tidak di-escape
            ->make(true);
    }

    public function show($id)
    {
        // Ambil history berdasarkan ID
        $history = History::findOrFail($id);

        // Pastikan polyline yang diambil dari database didekode dari JSON
        $history->polyline = json_decode($history->polyline);

        // Kirim data ke view
        return view('history.show', compact('history'));
    }

    public function destroy($id)
    {
        $history = History::findOrFail($id);
        $history->delete();
        return redirect()->route('history.index')->with('success', 'History deleted successfully');
    }
}
