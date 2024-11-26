<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function Userindex()
    {
        return view('data-users.index');
    }
    
    public function Getuserindex(Request $request)
    {
        // Ambil parameter untuk pagination dan sorting dari DataTables request
        $columns = ['id', 'name','company_name', 'total_distance', 'total_duration', 'total_steps', 'total_calori'];

        // Query untuk mengambil data pengguna
        $usersQuery = User::with('histories')
            ->select('users.*') // Pilih semua kolom dari users
            ->withSum('histories as total_distance', 'distance') // Menghitung total jarak
            ->withSum('histories as total_steps', 'steps') // Menghitung total langkah
            ->withSum('histories as total_calori', 'calori')
            ->with(['histories' => function ($query) {
                $query->selectRaw('user_id, SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as total_duration')
                    ->groupBy('user_id');
            }]);

        // Sorting berdasarkan permintaan dari DataTables
        if ($request->has('order')) {
            $orderBy = $columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir') == 'desc' ? 'desc' : 'asc';
            $usersQuery->orderBy($orderBy, $orderDir);
        }

        // Filter pencarian global dari DataTables
        if ($request->input('search.value')) {
            $searchValue = $request->input('search.value');
            $usersQuery->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', "%$searchValue%")
                    ->orWhereHas('histories', function ($q) use ($searchValue) {
                        $q->where('distance', 'like', "%$searchValue%")
                          ->orWhere('duration', 'like', "%$searchValue%"); // Menambahkan pencarian di durasi
                    });
            });
        }

        // Pagination untuk DataTables
        $users = $usersQuery->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'company_name' => $user->company_name,
                    'total_distance' => $user->total_distance,
                    'total_duration' => $user->histories->first() ? $user->histories->first()->total_duration : '00:00:00',
                    'total_steps' => $user->total_steps,
                    'total_calori' => $user->total_calori,
                    'actions' =>
                                '<button class="edit-btn bg-green-500 text-white px-3 py-1 rounded" data-id="' . $user->id . '">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-btn bg-red-500 text-white px-3 py-1 rounded" data-id="' . $user->id . '">
                                    <i class="fas fa-trash-alt"></i>
                                </button>',
                ];
            });

        // Menghitung total records tanpa filter
        $totalRecords = User::count();
        // Menghitung total records setelah filter pencarian
        $filteredRecords = $usersQuery->count();

        // Mengembalikan data sesuai format DataTables
        return response()->json([
            'draw' => intval($request->input('draw', 1)), // draw untuk tracking request DataTables
            'recordsTotal' => $totalRecords, // total data tanpa filter
            'recordsFiltered' => $filteredRecords, // total data dengan filter
            'data' => $users, // data yang ditampilkan di tabel
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // Cari user berdasarkan ID
        return view('data-users.edit', compact('user')); // Tampilkan view edit
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'company_name']));

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    
    public function destroy($id)
    {

        $user = User::findOrFail($id);
        $user->delete(); // Data tidak akan terhapus secara permanen

        
        return response()->json(['success' => 'Data pengguna berhasil dihapus.']);
    }



}
