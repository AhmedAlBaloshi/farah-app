<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductTimeSlot;
use App\Models\SubService;
use App\OrderDetail;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = SubService::select(DB::raw('sub_service.*, product.product_image as image, product.discount,product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating'))
            ->with('banner')
            ->join('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
            ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id');
        if ($request->service_list_id) {
            $query->where('sub_service.service_list_id', $request->service_list_id);
        }
        $sub_services = $query->latest('sub_service.created_at')
            ->groupBy('sub_service.sub_service_id')
            ->where('sub_service.is_active', '1')
            ->paginate(10);
        if ($sub_services) {
            return response()->json([
                'success' => 1,
                'sub_services' => $sub_services
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load sub services list from database'
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
            'sub_service_name'    => 'required',
            'sub_service_name_ar' => 'required',
            'service_list_id'     => 'required',
            'detail'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $sub_services = SubService::add($request->all());

        if ($sub_services) {
            return response()->json([
                'success' => 1,
                'message' => 'Sub service list added successfully',
                "sub_service_id" => $sub_services->sub_service_id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add sub service list'
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
        $service  = SubService::select(DB::raw('sub_service.*, product.product_image as image,  product.discount,product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating'))
            ->with(['banner', 'images'])
            ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id')
            ->leftJoin('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
            ->where('sub_service.sub_service_id', $id)
            ->groupBy('sub_service.sub_service_id')
            ->first();

            return response()->json([
                'success' => 1,
                'sub_service_list' => $service
            ], 200);
        if ($service) {
            return response()->json([
                'success' => 1,
                'sub_service_list' => $service
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, sub Service list not found'
        ], 404);
    }

    public function getTimeSlots($id, $date)
    {
        $slots = ProductTimeSlot::where('sub_service_id', $id)->get();

        foreach ($slots as $key => $slot) {
            $booked = OrderDetail::where('booking_date', $date)
                ->where('booking_start_time', $slot->start_time)
                ->where('booking_end_time', $slot->end_time)
                ->get();
            if (count($booked) > 0) {
                $slot->is_booked = true;
            } else {
                $slot->is_booked = false;
            }
        }
        if ($slots) {
            return response()->json([
                'success' => 1,
                'time_slots' => $slots
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Time slots not found'
        ], 404);
    }

    public function getSubService()
    {
        $service = SubService::pluck('sub_service_name', 'sub_service_id')->where('is_active', 1)->toArray();
        return response()->json([
            'success' => 1,
            'services' => $service
        ], 200);
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
            'sub_service_name'       => 'required',
            'sub_service_name_ar'    => 'required',
            'service_list_id'         => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }


        $sub_service = SubService::updateRecords($id, $request->all());

        if (!$sub_service) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Sub service list not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Sub service list updated successfully',
            "sub_service_id" => $sub_service->sub_service_id
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

            $service = SubService::where('sub_service_id', $id)->delete();

            return response()->json([
                'success' => 1,
                'message' => 'Sub service list deleted successfully',
            ], 200);
        }
    }
}
