<?php

namespace App\Http\Controllers;

use App\Models\EntranceFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EntranceFeeController extends Controller
{
    public function index()
    {
        // Get the current entrance fee (there should only be one active)
        $entranceFee = EntranceFee::where('isActive', true)->first();
        
        return view('adminFolder.pricing.pricing', compact('entranceFee'));
    }

    public function updateOrCreate(Request $request)
    {
        // Validate that only manager can access this
        if (!Auth::check() || Auth::user()->role !== 'manager') {
            return redirect()->route('admin.pricing')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'feeName' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // Deactivate any existing active entrance fees
            EntranceFee::where('isActive', true)->update(['isActive' => false]);
            
            // Create new active entrance fee
            EntranceFee::create([
                'feeName' => $request->feeName,
                'amount' => $request->amount,
                'isActive' => true,
            ]);
        });

        return redirect()->route('admin.pricing')->with('success', 'Entrance fee updated successfully.');
    }

    public function deactivate(Request $request)
    {
        // Validate that only manager can access this
        if (!Auth::check() || Auth::user()->role !== 'manager') {
            return redirect()->route('admin.pricing')->with('error', 'Unauthorized access.');
        }

        DB::transaction(function () {
            // Deactivate any existing active entrance fees
            EntranceFee::where('isActive', true)->update(['isActive' => false]);
        });

        return redirect()->route('admin.pricing')->with('success', 'Entrance fee deactivated successfully.');
    }
}