<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::with('service', 'serviceList', 'subServiceList', 'banner');
        if ($request->service_id) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->service_list_id) {
            $query->where('service_list_id', $request->service_list_id);
        }
        if ($request->sub_service_id) {
            $query->where('sub_service_id', $request->sub_service_id);
        }
        $products = $query->latest()->paginate(10);
        if ($products) {
            return response()->json([
                'success' => 1,
                'products' => $products
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load products from database'
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
            'product_name'      => 'required',
            'product_name_ar'   => 'required',
            'product_image'   => 'required',
            'address'           => 'required',
            'address_ar'        => 'required',
            'latitude'          => 'required',
            'longitude'         => 'required',
            'description'       => 'required',
            'description_ar'    => 'required',
            'rate'              => 'required',
            'service_id'        => 'required',
            'service_list_id'   => 'required',
            'items.*.date'      => 'required',
            'items.*.time'      => 'required'
        ], [
            'items.*.date.required' => 'date field is required',
            'items.*.time.required' => 'time field is required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $product = Product::add($request->all());
        if ($product) {
            return response()->json([
                'success' => 1,
                'message' => 'Product added successfully',
                "product_id" => $product->product_id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add Product'
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
        $product = Product::with('productAvailability', 'banner')->where('product_id', $id)->first();

        if ($product) {
            return response()->json([
                'success' => 1,
                'product' => $product
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Product not found'
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
            'product_name'      => 'required',
            'product_name_ar'   => 'required',
            'address'           => 'required',
            'address_ar'        => 'required',
            'latitude'          => 'required',
            'longitude'         => 'required',
            'description'       => 'required',
            'description_ar'    => 'required',
            'rate'              => 'required',
            'service_list_id'   => 'required',
            'service_id'        => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $product = Product::updateRecords($id, $request->all());

        if (!$product) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Product not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Product updated successfully',
            "product_id" => $product->product_id
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
            $product = Product::where('product_id', $id)->delete();

            return response()->json([
                'success' => 1,
                'message' => 'Product deleted successfully',
            ], 200);
        }
    }
}
