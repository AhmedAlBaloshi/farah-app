<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback = Feedback::with('user')->latest()->paginate(10);

        if ($feedback) {
            return response()->json([
                'success' => 1,
                'feedbacks' => $feedback
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load feedbacks from database'
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
            'description' => 'required',
            'user_id' => 'required',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $feedback = Feedback::add($request->all());

        if ($feedback) {
            return response()->json([
                'success' => 1,
                'message' => 'Feedback added successfully',
                "feedback_id" => $feedback->id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add feedback'
        ], 404);
    }
    public function rating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
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
// dd($request->all());
        $rating = ProductRating::add($request->all());

        if ($rating) {
            return response()->json([
                'success' => 1,
                'message' => 'Rating added successfully',
                "rating_id" => $rating->id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add Rating'
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
