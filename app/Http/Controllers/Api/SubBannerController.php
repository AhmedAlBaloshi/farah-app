<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubBanner;

class SubBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = SubBanner::latest()->paginate(10);

        if ($banners) {
            return response()->json([
                'success' => 1,
                'sub_banners' => $banners
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load sub banners from database'
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
        $this->validate($request, [
            'image' => 'required'
        ]);

        $banner = SubBanner::add($request->all());

        if ($banner) {
            return response()->json([
                'success' => 1,
                'message' => 'Sub Banner added successfully',
                "sub_banner_id" => $banner->id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add sub banner'
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
        $banner  = SubBanner::findOrFail($id);

        if ($banner) {
            return response()->json([
                'success' => 1,
                'sub_banner' => $banner
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Sub Banner not found'
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
        $this->validate($request, [
            'image' => 'required'
        ]);

        $banner = SubBanner::updateRecords($id, $request->all());
        if (!$banner) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Sub Banner not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Sub Banner updated successfully',
            "sub_banner_id" => $banner->id
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

            $banner = SubBanner::findOrFail($id)->delete();

            return response()->json([
                'success' => 1,
                'message' => 'Sub Banner deleted successfully',
            ], 200);
        }
    }
}
