<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{// Pastikan namespace Auth sudah diimport

    public function index()
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Jika pengguna tidak login, arahkan ke halaman login atau berikan pesan
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data pengguna yang sedang login beserta histori
        $user = User::with('histories')
            ->where('id', $user->id) // Filter untuk user yang sedang login
            ->select('users.*') // Pilih semua kolom dari users
            ->withSum('histories as total_distance', 'distance') // Menghitung total jarak
            ->withSum('histories as total_steps', 'steps') // Menghitung total langkah
            ->withSum('histories as total_calori', 'calori') // Menghitung total kalori
            ->with(['histories' => function ($query) {
                $query->selectRaw('user_id, SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as total_duration')
                    ->groupBy('user_id');
            }])
            ->first(); // Ambil data pengguna login (hanya satu)

            if ($user->total_distance < 1) {
                $user->total_distance = $user->total_distance * 1000 . ' m'; // Ubah ke meter
            } else {
                $user->total_distance = $user->total_distance . ' km'; // Tetap dalam kilometer
            }

        // Kirimkan data ke view
        return view('welcome', compact('user'));
    }




    
    public function showHistory(Request $request)
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
    
        // Memulai query untuk history berdasarkan user_id yang sedang login
        $query = History::query();

        
    
        // Filter berdasarkan user_id (menggunakan ID pengguna yang sedang login)
        if ($user) {
            $query->where('user_id', $user->id);  // Menampilkan hanya history dari pengguna yang sedang login
        }
    
        // Urutkan berdasarkan start_time terbaru (menurun)
        $query->orderBy('start_time', 'desc'); // 'desc' untuk urutan terbaru
    
        // Total data tanpa filter
        $totalRecords = History::where('user_id', $user->id)->count();  // Total data untuk pengguna yang login

        return DataTables::of($query)
        ->editColumn('start_time', function ($history) {
            // Format tanggal menggunakan Carbon dan format lokal
            return Carbon::parse($history->start_time)->translatedFormat('l, d F Y H:i:s');
        })
        ->toJson();
    
        // Total data setelah filter
        $totalFiltered = $query->count();
    
        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->skip($start)->take($length)->get();
    
    
        
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getPolyline($id)
    {
        $history = History::findOrFail($id);
        $history->polyline = json_decode($history->polyline);

        // Return polyline data as JSON
        return view('show-polyline', compact('history'));
    }

    



    

   
}

