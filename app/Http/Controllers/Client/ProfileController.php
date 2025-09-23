<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        
        return view('client.profile.index', compact('client'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $client->update($request->only([
            'name', 'phone', 'address', 'city', 'state', 
            'zip_code', 'birth_date', 'gender'
        ]));

        return redirect()->back()
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Update tracking tags
     */
    public function updateTrackingTags(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $validator = Validator::make($request->all(), [
            'facebook_pixel_id' => 'nullable|string|max:255',
            'google_tag_manager_id' => 'nullable|string|max:255',
            'google_analytics_id' => 'nullable|string|max:255',
            'custom_tracking_code' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update tracking tags
        $trackingTags = [];
        
        if ($request->facebook_pixel_id) {
            $trackingTags['facebook_pixel'] = $request->facebook_pixel_id;
        }
        
        if ($request->google_tag_manager_id) {
            $trackingTags['google_tag_manager'] = $request->google_tag_manager_id;
        }
        
        if ($request->google_analytics_id) {
            $trackingTags['google_analytics'] = $request->google_analytics_id;
        }

        $client->update([
            'tracking_tags' => $trackingTags,
            'facebook_pixel_id' => $request->facebook_pixel_id,
            'google_tag_manager_id' => $request->google_tag_manager_id,
            'google_analytics_id' => $request->google_analytics_id,
            'custom_tracking_code' => $request->custom_tracking_code,
        ]);

        return redirect()->back()
            ->with('success', 'Tags de rastreamento atualizadas com sucesso!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Check current password
        if (!Hash::check($request->current_password, $client->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        // Update password
        $client->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Show tracking tags page
     */
    public function trackingTags()
    {
        $client = Auth::guard('client')->user();
        
        return view('client.profile.tracking-tags', compact('client'));
    }

    /**
     * Show security settings
     */
    public function security()
    {
        $client = Auth::guard('client')->user();
        
        return view('client.profile.security', compact('client'));
    }

    /**
     * Generate new tracking code
     */
    public function generateTrackingCode()
    {
        $client = Auth::guard('client')->user();
        
        $trackingCode = $this->buildTrackingCode($client);
        
        return response()->json([
            'tracking_code' => $trackingCode,
            'success' => true
        ]);
    }

    /**
     * Build tracking code for client
     */
    private function buildTrackingCode($client)
    {
        $code = "<!-- Tracking Code for {$client->name} -->\n";
        
        // Facebook Pixel
        if ($client->facebook_pixel_id) {
            $code .= "<!-- Facebook Pixel -->\n";
            $code .= "<script>\n";
            $code .= "!function(f,b,e,v,n,t,s)\n";
            $code .= "{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
            $code .= "n.callMethod.apply(n,arguments):n.queue.push(arguments)};\n";
            $code .= "if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';\n";
            $code .= "n.queue=[];t=b.createElement(e);t.async=!0;\n";
            $code .= "t.src=v;s=b.getElementsByTagName(e)[0];\n";
            $code .= "s.parentNode.insertBefore(t,s)}(window, document,'script',\n";
            $code .= "'https://connect.facebook.net/en_US/fbevents.js');\n";
            $code .= "fbq('init', '{$client->facebook_pixel_id}');\n";
            $code .= "fbq('track', 'PageView');\n";
            $code .= "</script>\n";
            $code .= "<noscript><img height=\"1\" width=\"1\" style=\"display:none\"\n";
            $code .= "src=\"https://www.facebook.com/tr?id={$client->facebook_pixel_id}&ev=PageView&noscript=1\"\n";
            $code .= "/></noscript>\n\n";
        }
        
        // Google Tag Manager
        if ($client->google_tag_manager_id) {
            $code .= "<!-- Google Tag Manager -->\n";
            $code .= "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\n";
            $code .= "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\n";
            $code .= "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n";
            $code .= "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n";
            $code .= "})(window,document,'script','dataLayer','{$client->google_tag_manager_id}');</script>\n";
            $code .= "<!-- End Google Tag Manager -->\n\n";
        }
        
        // Google Analytics
        if ($client->google_analytics_id) {
            $code .= "<!-- Google Analytics -->\n";
            $code .= "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$client->google_analytics_id}\"></script>\n";
            $code .= "<script>\n";
            $code .= "window.dataLayer = window.dataLayer || [];\n";
            $code .= "function gtag(){dataLayer.push(arguments);}\n";
            $code .= "gtag('js', new Date());\n";
            $code .= "gtag('config', '{$client->google_analytics_id}');\n";
            $code .= "</script>\n";
            $code .= "<!-- End Google Analytics -->\n\n";
        }
        
        // Custom tracking code
        if ($client->custom_tracking_code) {
            $code .= "<!-- Custom Tracking Code -->\n";
            $code .= $client->custom_tracking_code . "\n\n";
        }
        
        return $code;
    }

    /**
     * Download tracking code as file
     */
    public function downloadTrackingCode()
    {
        $client = Auth::guard('client')->user();
        $trackingCode = $this->buildTrackingCode($client);
        
        $filename = "tracking-code-{$client->id}.html";
        
        return response($trackingCode)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get account statistics
     */
    public function getAccountStats()
    {
        $client = Auth::guard('client')->user();
        
        return response()->json([
            'total_leads' => $client->leads()->count(),
            'total_earnings' => $client->total_earnings,
            'account_age' => $client->created_at->diffInDays(now()),
            'last_login' => $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Nunca',
        ]);
    }
}
