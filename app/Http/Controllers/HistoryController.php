<?php

namespace App\Http\Controllers;

use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    // Menampilkan halaman index
    public function index()
    {
        return view('history.index');
    }

    // Mengembalikan data untuk DataTables
    public function getHistories(Request $request)
    {
        // Mulai query untuk mengambil data
        $histories = History::select('id', 'username', 'company_name', 'distance', 'duration', 'start_time');

        // Filter data berdasarkan tanggal jika ada parameter 'date'
        if ($request->has('date') && !empty($request->date)) {
            $histories->whereDate('start_time', $request->date);
        }

        // Return data ke DataTables
        return datatables()->of($histories)
            ->editColumn('start_time', function ($history) {
                // Format tanggal menjadi lebih user-friendly
                return Carbon::parse($history->start_time)->translatedFormat('l, d F Y H:i:s');
            })
            ->addColumn('actions', function ($history) {
                // Tambahkan tombol untuk setiap baris
                return '
                    <a href="/admin/history/' . $history->id . '" class="btn btn-info btn-sm">Lihat Polyline</a>
                    <form action="/admin/history/' . $history->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>
                    </form>
                ';
            })
            ->rawColumns(['actions']) // Kolom actions berisi HTML, jangan di-escape
            ->make(true);
    }

    // Menyimpan data baru
    public function saveHistory(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'polyline' => 'required|array',
            'duration' => 'required|integer',
            'distance' => 'required|numeric',
            'start_time' => 'required|date'
        ]);

        // Cek duplikasi data
        $existingTracking = History::where('username', $request->username)
            ->where('start_time', $request->start_time)
            ->first();

        if ($existingTracking) {
            return response()->json(['message' => 'Data tracking sudah ada'], 400);
        }

        // Simpan data
        $trackingHistory = History::create([
            'username' => $request->username,
            'company_name' => $request->company_name,
            'polyline' => json_encode($request->polyline),
            'duration' => $request->duration,
            'distance' => $request->distance,
            'start_time' => Carbon::parse($request->start_time)->format('Y-m-d H:i:s'),
        ]);

        return response()->json(['message' => 'History berhasil disimpan', 'data' => $trackingHistory], 201);
    }

    // Menampilkan detail history
    public function show($id)
    {
        $history = History::findOrFail($id);
        $history->polyline = json_decode($history->polyline);

        return view('history.show', compact('history'));
    }

    // Menghapus data history
    public function destroy($id)
    {
        $history = History::findOrFail($id);
        $history->delete();

        return redirect()->route('history.index')->with('success', 'History berhasil dihapus');
    }
}
