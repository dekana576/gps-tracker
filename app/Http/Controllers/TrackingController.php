<?php

namespace App\Http\Controllers;

use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function saveHistory(Request $request)
    {
        $history = new History();
        $history->user_id = $request -> user_id;
        $history->username = $request->username;
        $history->company_name = $request->company;
        $history->distance = $request->distance;
        $history->steps = $request->steps;
        $history->calori = $request->calori;
        $history->duration = gmdate("H:i:s", $request->duration);
        $history->start_time = Carbon::parse($request->startTime)->timezone('Asia/Makassar');
        $history->polyline = json_encode($request->polyline);
        $history->save();

        return response()->json(['message' => 'History saved successfully']);
    }

    

}
