<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SystemSettingsController extends Controller
{
    /**
     * Show system settings
     */
    public function index()
    {
        $groups = [
            'appearance' => SystemSetting::getAppearanceSettings(),
            'seo' => SystemSetting::getSEOSettings(),
            'tracking' => SystemSetting::getTrackingSettings(),
            'financial' => SystemSetting::getFinancialSettings(),
            'recaptcha' => SystemSetting::getByGroup('recaptcha'),
        ];

        return view('admin.settings.index', compact('groups'));
    }

    /**
     * Update system settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        foreach ($request->settings as $key => $value) {
            SystemSetting::set($key, $value);
        }

        return redirect()->back()
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Upload logo
     */
    public function uploadLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|in:logo,logoDark,favicon,background',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $type = $request->type;
        
        // Map type names
        $typeMapping = [
            'logo' => 'logo',
            'logoDark' => 'logo_dark',
            'favicon' => 'favicon',
            'background' => 'background'
        ];
        
        $settingKey = $typeMapping[$type] . '_path';
        $folder = $type === 'background' ? 'backgrounds' : 'logos';
        
        $filename = $typeMapping[$type] . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Delete old file if exists
        $oldFile = SystemSetting::get($settingKey);
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        $path = $file->storeAs($folder, $filename, 'public');

        SystemSetting::set($settingKey, $path);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Upload background image
     */
    public function uploadBackground(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'background' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('background');
        $filename = 'background_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Delete old file if exists
        $oldFile = SystemSetting::get('background_path');
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        $path = $file->storeAs('backgrounds', $filename, 'public');

        SystemSetting::set('background_path', $path);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Update tracking codes
     */
    public function updateTracking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_analytics_id' => 'nullable|string',
            'google_tag_manager_id' => 'nullable|string',
            'meta_pixel_id' => 'nullable|string',
            'google_analytics_code' => 'nullable|string',
            'google_tag_manager_code' => 'nullable|string',
            'meta_pixel_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SystemSetting::set('google_analytics_id', $request->google_analytics_id);
        SystemSetting::set('google_tag_manager_id', $request->google_tag_manager_id);
        SystemSetting::set('meta_pixel_id', $request->meta_pixel_id);
        SystemSetting::set('google_analytics_code', $request->google_analytics_code);
        SystemSetting::set('google_tag_manager_code', $request->google_tag_manager_code);
        SystemSetting::set('meta_pixel_code', $request->meta_pixel_code);

        return redirect()->back()
            ->with('success', 'Tracking codes updated successfully.');
    }

    /**
     * Update reCAPTCHA settings
     */
    public function updateRecaptcha(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recaptcha_site_key' => 'nullable|string',
            'recaptcha_secret_key' => 'nullable|string',
            'recaptcha_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SystemSetting::set('recaptcha_site_key', $request->recaptcha_site_key);
        SystemSetting::set('recaptcha_secret_key', $request->recaptcha_secret_key);
        SystemSetting::set('recaptcha_enabled', $request->has('recaptcha_enabled') ? '1' : '0');

        return redirect()->back()
            ->with('success', 'reCAPTCHA settings updated successfully.');
    }

    /**
     * Update financial settings
     */
    public function updateFinancial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdrawal_days' => 'nullable|string',
            'withdrawal_start_time' => 'nullable|string',
            'withdrawal_end_time' => 'nullable|string',
            'withdrawal_fee_type' => 'nullable|in:percentage,fixed',
            'withdrawal_fee_value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SystemSetting::set('withdrawal_days', $request->withdrawal_days);
        SystemSetting::set('withdrawal_start_time', $request->withdrawal_start_time);
        SystemSetting::set('withdrawal_end_time', $request->withdrawal_end_time);
        SystemSetting::set('withdrawal_fee_type', $request->withdrawal_fee_type);
        SystemSetting::set('withdrawal_fee_value', $request->withdrawal_fee_value);

        return redirect()->back()
            ->with('success', 'Financial settings updated successfully.');
    }
}
