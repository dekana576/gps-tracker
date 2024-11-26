<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('welcome');
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
    



    

   
}

