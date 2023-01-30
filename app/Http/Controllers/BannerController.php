<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\SubService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::with(['product', 'subService'])->get();
        return view('banner.list', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subService = SubService::pluck('sub_service_name', 'sub_service_id')->toArray();
        $product = Product::pluck('product_name', 'product_id')->toArray();
        return view('banner.form', compact('subService', 'product'));
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
            'image' => 'required',
        ]);
        if (!$request->sub_service_id && !$request->product_id) {
            $request->validate([
                'product_id' => 'required',
                'sub_service_id' => 'required',
            ]);
        }
        $banner = Banner::add($request->all());
        return redirect()->route('banners.index')->with('success', 'Banner created successfully.');
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
        $banner = Banner::findOrFail($id);
        $subService = SubService::pluck('sub_service_name', 'sub_service_id')->toArray();
        $product = Product::pluck('product_name', 'product_id')->toArray();
        return view('banner.form', compact('banner', 'subService', 'product'));
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
        if (!$request->sub_service_id && !$request->product_id) {
            $request->validate([
                'product_id' => 'required',
                'sub_service_id' => 'required',
            ]);
        }
        $banner = Banner::updateRecords($id, $request->all());
        return redirect()->route('banners.index')->with('success', 'Banner updated successfully.');
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

            Banner::where('product_id', $id)->delete();

            return response()->json([
                "code" => 200,
                "response_status" => "success",
                "message"         => "Record deleted successfully",
                "data"            => []
            ]);
        }

        return response()->json([
            "code" => 500,
            "response_status" => "error",
            "message"         => "Something went wrong"
        ]);
    }
}
