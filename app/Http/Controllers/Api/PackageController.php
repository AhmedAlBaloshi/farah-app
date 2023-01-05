<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageService;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::with('products', 'services')->latest()->paginate();
        if ($packages) {
            return response()->json([
                'success' => 1,
                'packages' => $packages
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load packages from database'
        ], 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'      => 'required|string|min:3',
            'amount'      => 'required',
            'start_date'      => 'required|date',
            'start_time'      => 'required',
            'end_date'      => 'required|date',
            'end_time'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $package = Package::add($request->all());
        if ($package) {
            return response()->json([
                'success' => 1,
                'message' => 'Package added successfully',
                "package_id" => $package->id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add package'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Package::with('products', 'services')->findOrFail($id);

        if ($package) {
            return response()->json([
                'success' => 1,
                'package' => $package
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Package not found'
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $package = Package::updateRecords($id, $request->all());

        if (!$package) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Package not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Package updated successfully',
            "package_id" => $package
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {
            Package::findOrFail($id)->delete();
            PackageService::where('package_id',$id)->delete();
            return response()->json([
                'success' => 1,
                'message' => 'Product deleted successfully',
            ], 200);
        }
    }
}
