<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class CitySearchController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->get('city', '');
        $state = $request->get('state', '');

        $query = Business::where('is_active', true);

        if ($city !== '') {
            $query->where('city', 'like', '%'.$city.'%');
        }

        if ($state !== '') {
            $query->where('state', 'like', '%'.$state.'%');
        }

        if ($city === '' && $state === '') {
            return redirect()->route('businesses.index')
                ->with('error', 'Enter a city or state to search.');
        }

        $businesses = $query->latest()->paginate(12)->withQueryString();

        return view('businesses.by-city', compact('businesses', 'city', 'state'));
    }
}
