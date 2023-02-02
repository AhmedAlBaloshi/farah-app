<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTimeSlot;
use Illuminate\Http\Request;
use App\Models\SubService;
use Illuminate\Support\Facades\DB;
use Response;

class SubServiceController extends Controller
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
     * Show Service List
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subService = SubService::select(DB::raw('sub_service.*, product.product_id, product.discount,product.address, product.address_ar, product.rate as amount, AVG(product_rating.rating) as rating'))
            ->with('banner')
            ->join('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
            ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id')->latest('sub_service.created_at')
            ->groupBy('sub_service.sub_service_id')
            ->get();

        return view('sub-service.list', compact('subService'));
    }

    /**
     * Create Service
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $products = Product::select('product_name', 'product_id')->get();
        $service = \App\Models\ServiceList::pluck('service_name', 'service_list_id')->toArray();
        return view('sub-service.form', compact('service', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'sub_service_name'    => 'required',
            'sub_service_name_ar' => 'required',
            'service_list_id'     => 'required',
            'address' => 'required',
            'address_ar' => 'required',
            'amount' => 'required|integer',
            'detail'     => 'required',
            'sub_service_image.*.image' => 'required',
        ], [
            'sub_service_image.*.image.required' => 'Image field is required',
        ]);

        SubService::add($request->all());

        return redirect()->route('sub_service_index')->with('success', 'Sub Service List created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceList  $serviceList
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = \App\Models\ServiceList::pluck('service_name', 'service_list_id')->toArray();
        $subService = [];
        if ((int)$id > 0) {
            $subService  = SubService::select('sub_service.*', 'product.product_id', 'product.rate as amount', 'product.product_image as image', 'product.address', 'product.address_ar')
                ->join('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')->groupBy('sub_service.sub_service_id')->where('sub_service.sub_service_id', $id)->first();

            $serviceImage = ProductImage::where('sub_service_id', $id)->get();
            $subService['images'] = $serviceImage;
        }
        return view('sub-service.form', compact(['service', 'subService']));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'sub_service_name'    => 'required',
            'sub_service_name_ar' => 'required',
            'service_list_id'     => 'required',
            'address' => 'required',
            'address_ar' => 'required',
            'amount' => 'required|integer',
            'detail'     => 'required',
           ]);

        SubService::updateRecords($id, $request->all());

        return redirect()->route('sub_service_index')->with('success', 'Service List updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceList  $serviceList
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {

            ProductTimeSlot::where('sub_service_id', $id)
                ->delete();
            Product::where('sub_service_id', $id)->delete();
            SubService::where('sub_service_id', $id)->delete();
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
