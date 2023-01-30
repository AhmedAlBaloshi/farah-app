<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();
        return view('about-us.form', compact('about'));
    }

    public function update($id, Request $request)
    {
        $about = AboutUs::updateRecords($id, $request->all());
        return redirect()->route('about.index')->with('success', 'About Us updated successfully.');
    }
}
