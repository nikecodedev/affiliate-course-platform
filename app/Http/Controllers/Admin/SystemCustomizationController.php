<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemCustomization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SystemCustomizationController extends Controller
{
    /**
     * Display the customization settings page
     */
    public function index()
    {
        $customizations = SystemCustomization::all()->keyBy('key');
        
        return view('admin.customization.index', compact('customizations'));
    }

    /**
     * Update customization settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:500',
            'primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'background' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update text-based customizations
            $textFields = [
                'company_name', 'company_email', 'company_phone', 
                'company_address', 'primary_color', 'secondary_color'
            ];

            foreach ($textFields as $field) {
                if ($request->has($field) && $request->filled($field)) {
                    SystemCustomization::setValue($field, $request->input($field), 'text');
                }
            }

            $responseData = [
                'success' => true, 
                'message' => 'Customization settings updated successfully!',
                'company_name' => $request->input('company_name'),
                'company_email' => $request->input('company_email'),
                'company_phone' => $request->input('company_phone'),
                'company_address' => $request->input('company_address'),
                'primary_color' => $request->input('primary_color'),
                'secondary_color' => $request->input('secondary_color')
            ];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $this->handleImageUpload($request->file('logo'), 'logo', 'customization');
                $responseData['logo_url'] = Storage::url($logoPath);
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                $faviconPath = $this->handleImageUpload($request->file('favicon'), 'favicon', 'customization', 32, 32);
                $responseData['favicon_url'] = Storage::url($faviconPath);
            }

            // Handle background upload
            if ($request->hasFile('background')) {
                $backgroundPath = $this->handleImageUpload($request->file('background'), 'background', 'customization');
                $responseData['background_url'] = Storage::url($backgroundPath);
            }

            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json($responseData);
            }
            
            return redirect()->route('admin.customization.index')
                ->with('success', 'Customization settings updated successfully!');

        } catch (\Exception $e) {
            $errorMessage = 'Failed to update settings: ' . $e->getMessage();
            
            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }

            return redirect()->back()
                ->withErrors(['error' => $errorMessage])
                ->withInput();
        }
    }

    /**
     * Handle image upload with optimization
     */
    private function handleImageUpload($file, string $type, string $folder, int $width = null, int $height = null)
    {
        $filename = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');

        // Delete old file if exists
        $oldPath = SystemCustomization::getValue($type . '_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Optimize image if dimensions are specified and Intervention Image is available
        if ($width && $height && class_exists('\Intervention\Image\Facades\Image')) {
            try {
                $image = Image::make(storage_path('app/public/' . $path));
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save(storage_path('app/public/' . $path));
            } catch (\Exception $e) {
                // Log error but continue without optimization
                \Log::warning('Image optimization failed: ' . $e->getMessage());
            }
        }

        // Save the new path
        SystemCustomization::setValue($type . '_path', $path, 'image');
        
        return $path;
    }

    /**
     * Reset to default settings
     */
    public function reset()
    {
        try {
            // Delete uploaded files
            $files = ['logo_path', 'favicon_path', 'background_path'];
            foreach ($files as $file) {
                $path = SystemCustomization::getValue($file);
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Delete customizations
            SystemCustomization::truncate();

            return redirect()->route('admin.customization.index')
                ->with('success', 'Customization settings reset to defaults!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to reset settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Preview customization changes
     */
    public function preview(Request $request)
    {
        $customizations = [
            'company_name' => $request->input('company_name', SystemCustomization::getCompanyName()),
            'company_email' => $request->input('company_email', SystemCustomization::getCompanyEmail()),
            'company_phone' => $request->input('company_phone', SystemCustomization::getCompanyPhone()),
            'company_address' => $request->input('company_address', SystemCustomization::getCompanyAddress()),
            'primary_color' => $request->input('primary_color', SystemCustomization::getPrimaryColor()),
            'secondary_color' => $request->input('secondary_color', SystemCustomization::getSecondaryColor()),
        ];

        return response()->json([
            'success' => true,
            'customizations' => $customizations
        ]);
    }
}
