<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display general settings page.
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update general settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg|max:1024',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        try {
            // Update text settings
            Setting::set('site_name', $request->site_name);
            Setting::set('site_description', $request->site_description ?? '');
            Setting::set('contact_email', $request->contact_email ?? '');
            Setting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0', 'boolean');

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                $logoPath = $request->file('site_logo')->store('uploads/settings', 'public');
                
                // Delete old logo if exists
                $oldLogo = Setting::get('site_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                
                Setting::set('site_logo', 'storage/' . $logoPath);
            }

            // Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                $faviconPath = $request->file('site_favicon')->store('uploads/settings', 'public');
                
                // Delete old favicon if exists
                $oldFavicon = Setting::get('site_favicon');
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
                
                Setting::set('site_favicon', 'storage/' . $faviconPath);
            }

            // Clear cache
            Cache::forget('settings');

            return redirect()->back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
}
