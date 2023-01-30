<?php

namespace App\Http\Controllers;

use App\Models\TermsService;
use Illuminate\Http\Request;

class TermServiceController extends Controller
{
    public function index()
    {
        $term = TermsService::first();
        return view('terms-of-services.form', compact('term'));
    }

    public function update($id, Request $request)
    {
        $service = TermsService::updateRecords($id, $request->all());
        return redirect()->route('term.index')->with('success', 'Term of service updated successfully.');
    }
}
