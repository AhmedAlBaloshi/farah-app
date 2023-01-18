<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query  = Offer::with(['product', 'service' => function ($q) {
            return $q->select(DB::raw('sub_service.sub_service_id as sub_service_id, sub_service.service_list_id as service_list_id, sub_service.sub_service_name as sub_service_name,
            sub_service.sub_service_name_ar as sub_service_name_ar,product.product_image, product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating, sub_service.created_at as created_at,sub_service.updated_at as updated_at'))
                ->leftJoin('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
                ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id');
        }])->latest();
        if ($request->search) {
            $query = $query->where('id', $request->search)
                ->orWhere('title', 'like', "%$request->search%")
                ->orWhere('product_id', $request->search)
                ->orWhere('service_id', $request->search);
        }
        $offers = $query->paginate(10);

        if ($offers) {
            return response()->json([
                'success' => 1,
                'offers' => $offers
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load offers from database'
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
            'title' => 'required|string|min:3',
            'percentage' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
        ]);

        if (!$request->service_id && !$request->product_id) {
            return response()->json([
                'success' => 0,
                'message' => 'product_id or service_id will require.'
            ], 400);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $offer = Offer::add($request->all());

        if ($offer) {
            return response()->json([
                'success' => 1,
                'message' => 'Offer added successfully',
                "offer_id" => $offer->id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add offer'
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
        $offer  = Offer::with(['product', 'service' => function ($q) {
            return $q->select(DB::raw('sub_service.*, product.product_image, product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating'))
                ->leftJoin('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
                ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id');
        }])->findOrFail($id);

        if ($offer) {
            return response()->json([
                'success' => 1,
                'offer' => $offer
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Offer not found'
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
            'title' => 'required|string|min:3',
            'percentage' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
        ]);

        if (!$request->service_id && !$request->product_id) {
            return response()->json([
                'success' => 0,
                'message' => 'product_id or service_id will require.'
            ], 400);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }
        $offer = Offer::updateRecords($id, $request->all());
        if (!$offer) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Offer not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Offer updated successfully',
            "offer_id" => $offer->id
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
            $offer = Offer::findOrFail($id)->delete();
            return response()->json([
                'success' => 1,
                'message' => 'Offer deleted successfully',
            ], 200);
        }
    }
}
