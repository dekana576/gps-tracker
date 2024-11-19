<?php

namespace App\Http\Controllers;

use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $histories = History::all();
        return view('history.index', compact('histories'));
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

    public function store(Request $request)
    {
        // Validasi data dari request
        $validated = $request->validate([
            'polyline' => 'required|array', // Pastikan polyline adalah array
            'duration' => 'required|numeric',
            'distance' => 'required|numeric',
        ]);

        // Simpan history pelacakan ke database
        $history = new History();
        $history->polyline = json_encode($validated['polyline']); // Simpan polyline sebagai JSON
        $history->duration = $validated['duration'];
        $history->distance = $validated['distance'];
        $history->start_time = Carbon::now('Asia/Makassar');
        $history->save();

        return response()->json(['success' => 'History berhasil disimpan']);
    }
}
