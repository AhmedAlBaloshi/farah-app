<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;
use App\Models\Product;
use Response;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show Product List
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with(['offers' => function ($q) {
            return $q->where('end_date', '>=', date('Y-m-d'));
        }])->where('sub_service_id',null)->get();
        return view('product.list', compact('product'));
    }

    /**
     * Create Product
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service  = \App\Models\Service::pluck('service_name', 'service_id')->toArray();
        $subService  = \App\Models\SubService::pluck('sub_service_name', 'sub_service_id')->toArray();
        $serviceList  = \App\Models\ServiceList::pluck('service_name', 'service_list_id')->toArray();
        $categoryList = \App\Models\Category::pluck('category_name', 'category_id')->toArray();

        return view('product.form', compact(['service', 'categoryList', 'subService', 'serviceList']));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name'      => 'required',
            'product_name_ar'   => 'required',
            'category_id'   => 'required',
            // 'address'           => 'required',
            // 'address_ar'        => 'required',
            // 'latitude'          => 'required',
            // 'longitude'         => 'required',
            'description'       => 'required',
            'image'       => 'required',
            'description_ar'    => 'required',
            'rate'              => 'required|integer',
            // 'service_id'        => 'required',
            // 'service_list_id'   => 'required',
            // 'sub_service_id'   => 'unique:product',
        ]);

        Product::add($request->all());

        return redirect()->route('product_index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $service = $serviceList = [];

        if ((int)$id > 0) {
            $product         = Product::find($id);
            $service  = \App\Models\Service::pluck('service_name', 'service_id')->toArray();
            $subService  = \App\Models\SubService::pluck('sub_service_name', 'sub_service_id')->toArray();
            $serviceList  = \App\Models\ServiceList::pluck('service_name', 'service_list_id')->toArray();
            $categoryList = \App\Models\Category::pluck('category_name', 'category_id')->toArray();
        }

        return view('product.form', compact(['product', 'service', 'categoryList', 'subService', 'serviceList']));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_name'      => 'required',
            'product_name_ar'   => 'required',
            'category_id'   => 'required',
            'description'       => 'required',
            'description_ar'    => 'required',
            'rate'              => 'required|integer',
        ]);

        Product::updateRecords($id, $request->all());

        return redirect()->route('product_index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {

            // ProductAvailability::where('product_id',$id)->delete();
            Product::where('product_id', $id)->delete();

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
