<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawalSettingController extends Controller
{
    public function index()
    {
        $settings = WithdrawalSetting::first();
        return view('admin.financial.withdrawal-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'available_days' => 'nullable|array',
            'available_times' => 'nullable|array',
            'fee_type' => 'required|in:fixed,percent',
            'fee_value' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = WithdrawalSetting::firstOrNew([]);
        $settings->available_days = $request->available_days ?? [];
        $settings->available_times = $request->available_times ?? [];
        $settings->fee_type = $request->fee_type;
        $settings->fee_value = $request->fee_value;
        $settings->save();

        return redirect()->back()->with('success', 'Withdrawal settings updated successfully.');
    }
}


