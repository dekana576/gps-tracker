<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class UpdateUserTotals extends Command
{
    protected $signature = 'users:update-totals';
    protected $description = 'Update total distance and duration for each user';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $totals = FacadesDB::table('histories')
                ->where('user_id', $user->id)
                ->selectRaw('SUM(distance) as total_distance, SUM(duration) as total_duration')
                ->first();

            $user->update([
                'total_distance' => $totals->total_distance ?? 0,
                'total_duration' => $totals->total_duration ?? 0,
            ]);
        }

        $this->info('User totals have been updated successfully!');
    }
}
