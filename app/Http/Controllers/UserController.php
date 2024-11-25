<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
    
        // Total data setelah filter
        $totalFiltered = $query->count();
    
        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->skip($start)->take($length)->get();
    
        // Format the 'start_time' field
        $data->each(function ($item) {
            // Menformat 'start_time' menggunakan Carbon
            if ($item->start_time) {
                // Format tanggal dengan format yang diinginkan
                $item->start_time = Carbon::parse($item->start_time)->format('d-m-Y H:i:s'); // Format: '25-11-2024 14:30:00'
            }
        });
    
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
    



    

   
}

