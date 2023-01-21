<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::with(['product', 'subService' => function ($q) {
            return $q->select(DB::raw('sub_service.*, product.product_image,  product.discount,product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating'))
                ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id')
                ->leftJoin('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
                ->groupBy('sub_service.sub_service_id');
        }])->latest()->paginate(10);

        if ($banners) {
            return response()->json([
                'success' => 1,
                'banners' => $banners
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load banners from database'
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
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $banner = Banner::add($request->all());

        if ($banner) {
            return response()->json([
                'success' => 1,
                'message' => 'Banner added successfully',
                "banner_id" => $banner->id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add banner'
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
        $banner  = Banner::with(['product', 'subService' => function ($q) {
            return $q->select(DB::raw('sub_service.*, product.product_image,  product.discount,product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating'))
                ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id')
                ->leftJoin('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
                ->groupBy('sub_service.sub_service_id');
        }])->findOrFail($id);

        if ($banner) {
            return response()->json([
                'success' => 1,
                'banner' => $banner
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Banner not found'
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
        $validator = Validator::make($request->all(), [
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $banner = Banner::updateRecords($id, $request->all());
        if (!$banner) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Banner not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Banner updated successfully',
            "banner_id" => $banner->id
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

            $banner = Banner::findOrFail($id)->delete();

            return response()->json([
                'success' => 1,
                'message' => 'Banner deleted successfully',
            ], 200);
        }
    }
}
