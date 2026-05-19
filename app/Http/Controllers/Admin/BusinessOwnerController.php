<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class BusinessOwnerController extends Controller
{
    public function index()
    {
        $owners = User::where('role', User::ROLE_BUSINESS_OWNER)
            ->with('businesses')
            ->latest()
            ->paginate(15);

        $totalOwners = User::where('role', User::ROLE_BUSINESS_OWNER)->count();

        return view('admin.business-owners.index', compact('owners', 'totalOwners'));
    }

    public function create()
    {
        return view('admin.business-owners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:'.User::class.',username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $owner = User::create([
            'name' => $validated['owner_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_BUSINESS_OWNER,
        ]);

        $businessData = [
            'user_id' => $owner->id,
            'name' => $validated['business_name'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'email' => $validated['email'],
            'description' => $validated['business_name'].' — '.$validated['city'].', '.$validated['state'],
            'address' => $validated['city'].', '.$validated['state'],
            'phone' => 'N/A',
            'is_active' => true,
        ];

        if ($request->hasFile('logo')) {
            $businessData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Business::create($businessData);

        return redirect()->route('admin.business-owners.index')
            ->with('success', 'Business owner created successfully.');
    }

    public function edit(User $owner)
    {
        if ($owner->role !== User::ROLE_BUSINESS_OWNER) {
            abort(404);
        }

        $owner->load('businesses');

        return view('admin.business-owners.edit', compact('owner'));
    }

    public function update(Request $request, User $owner)
    {
        if ($owner->role !== User::ROLE_BUSINESS_OWNER) {
            abort(404);
        }

        $validated = $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:'.User::class.',username,'.$owner->id],
            'email' => ['required', 'email', 'max:255', 'unique:'.User::class.',email,'.$owner->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'business_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $owner->update([
            'name' => $validated['owner_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $owner->update(['password' => Hash::make($validated['password'])]);
        }

        $business = $owner->businesses()->first();

        if ($business) {
            $businessData = [
                'name' => $validated['business_name'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'email' => $validated['email'],
            ];

            if ($request->hasFile('logo')) {
                if ($business->logo) {
                    Storage::disk('public')->delete($business->logo);
                }
                $businessData['logo'] = $request->file('logo')->store('logos', 'public');
            }

            $business->update($businessData);
        }

        return redirect()->route('admin.business-owners.index')
            ->with('success', 'Business owner updated successfully.');
    }

    public function destroy(User $owner)
    {
        if ($owner->role !== User::ROLE_BUSINESS_OWNER) {
            abort(403);
        }

        foreach ($owner->businesses as $business) {
            if ($business->logo) {
                Storage::disk('public')->delete($business->logo);
            }
            $business->products()->each(function ($product) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
            });
            $business->products()->delete();
            $business->orders()->delete();
            $business->delete();
        }

        $owner->delete();

        return redirect()->route('admin.business-owners.index')
            ->with('success', 'Business owner removed successfully.');
    }
}
