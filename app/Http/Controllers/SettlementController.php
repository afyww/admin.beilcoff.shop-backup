<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettlementController extends Controller
{
    public function index()
    {
        $settlements = Settlement::with(['user'])->get();

        if (request()->is('api/*')) {
            return response()->json($settlements);
        }

        return view('settlement', compact('settlements'));
    }

    public function vstartamount()
    {
        return view('addstartamount');
    }

    public function poststart(Request $request)
    {
        $data = $request->validate([
            'start_amount' => 'nullable|numeric',
        ]);
    
        $user = auth()->user();
        $data['start_time'] = Carbon::now()->toDateTimeString();
    
        $settlement = $user->settlements()->create($data);
    
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'New settlement created successfully!',
                'settlement' => $settlement
            ]);
        }
    
        return redirect(route('settlement'))->with('success', 'New settlement created successfully!');
    }
    
    public function vtotalamount()
    {
        return view('addtotalamount');
    }

    public function posttotal(Request $request)
    {
        $data = $request->validate([
            'total_amount' => 'nullable|numeric',
        ]);
    
        $user = auth()->user();
        $latestSettlement = $user->settlements()->latest()->first();
    
        if (!$latestSettlement) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active shift found to end.',
                ]);
            }
    
            return redirect(route('settlement'))->with('error', 'No active shift found to end.');
        }
    
        $data['end_time'] = Carbon::now()->toDateTimeString();
        $latestSettlement->update($data);
    
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shift ended successfully!',
                'settlement' => $latestSettlement,
            ]);
        }
    
        return redirect(route('settlement'))->with('success', 'Shift ended successfully!');
    }
        
    public function show($id)
    {
        $settlement = Settlement::with('histoys')->find($id);

        if (request()->is('api/*')) {
            return response()->json($settlement);
        }

        return view('showsettlement', compact('settlement'));
    }

    public function destroy($id)
    {
        Settlement::destroy($id);

        return redirect(route('settlement'))->with('success', 'Settlement Berhasil Dihapus !');
    }
}
