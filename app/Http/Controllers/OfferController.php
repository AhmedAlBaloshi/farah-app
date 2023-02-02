<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use App\Models\SubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers  = Offer::with(['product', 'service' => function ($q) {
            return $q->select(DB::raw('sub_service.sub_service_id as sub_service_id, sub_service.service_list_id as service_list_id, sub_service.sub_service_name as sub_service_name,
            sub_service.sub_service_name_ar as sub_service_name_ar,product.product_image, product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating, sub_service.created_at as created_at,sub_service.updated_at as updated_at'))
                ->leftJoin('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
                ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id');
        }])->get();

        return view('offer.list', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = SubService::select('sub_service_name', 'sub_service_id')->where('is_active', 1)->get();
        $products = Product::select('product_name', 'product_id')->where('sub_service_id', null)->where('is_active', 1)->get();
        // dd(count($products));
        return view('offer.form', compact('products', 'services'));
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
            'title' => 'required|string|min:3',
            'percentage' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
        ]);

        if (!$request->service_id && !$request->product_id) {
            $request->validate([
                'product_id' => 'required',
                'service_id' => 'required',
            ]);
        }

        $offer = Offer::add($request->all());
        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
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
        $services = SubService::select('sub_service_name', 'sub_service_id')->where('is_active', 1)->get();
        $products = Product::select('product_name', 'product_id')->where('sub_service_id', null)->where('is_active', 1)->get();
        $offer = Offer::findOrFail($id);
        return view('offer.form', compact('offer', 'products', 'services'));
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
        $request->validate([
            'title' => 'required|string|min:3',
            'percentage' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
        ]);

        if (!$request->service_id && !$request->product_id) {
            $request->validate([
                'product_id' => 'required',
                'service_id' => 'required',
            ]);
        }

        $offer = Offer::updateRecords($id, $request->all());
        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
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

            Offer::findOrFail($id)->delete();
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
