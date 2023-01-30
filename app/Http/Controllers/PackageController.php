<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::get();
        return view('package.list', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::select('product_name', 'product_id')->get();
        return view('package.form', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|min:3',
            'amount'      => 'required',
            'detail'      => 'required',
            'start_date'      => 'required|date',
            'start_time'      => 'required',
            'end_date'      => 'required|date',
            'end_time'      => 'required',
            'items.*.product_id'      => 'required',
        ], [
            'items.*.product_id.required' => 'Product field is required',
        ]);
        $package = Package::add($request->all());
        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::select('product_name', 'product_id')->get();
        $package = Package::findOrFail($id);
        $pkgSer = PackageService::select('product_id')->where('package_id', $id)->get();
        $package['items']  = $pkgSer;
        // echo json_encode($pkgSer);exit;
        return view('package.form', compact('package', 'products'));
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
        // echo json_encode($request->all());exit;
        $request->validate([
            'title'      => 'required|string|min:3',
            'amount'      => 'required',
            'detail'      => 'required',
            'start_date'      => 'required|date',
            'start_time'      => 'required',
            'end_date'      => 'required|date',
            'end_time'      => 'required',
            'items.*.product_id'      => 'required',
        ], [
            'items.*.product_id.required' => 'Product field is required',
        ]);
        $package = Package::updateRecords($id, $request->all());
        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
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
            PackageService::where('package_id', $id)->delete();
            Package::findOrFail($id)->delete();
            return Response::json([
                "code" => 200,
                "response_status" => "success",
                "message"         => "Record deleted successfully",
                "data"            => []
            ]);
        }

        return Response::json([
            "code" => 500,
            "response_status" => "error",
            "message"         => "Something went wrong"
        ]);
    }
}
