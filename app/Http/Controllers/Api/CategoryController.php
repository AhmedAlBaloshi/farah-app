<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::with('service', 'category','products');

        if ($request->service_id) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->parent_category) {
            $query->where('parent_category', $request->parent_category);
        }
        $categories = $query->latest()->paginate(10);
        // ->map(function ($category) {
        //     $category->products = $category->products->take(1);
        //     return $category;
        // });

        foreach ($categories as  $category) {
            $category->setRelation('products', $category->products->take(8));
        }

        if ($categories) {
            return response()->json([
                'success' => 1,
                'categories' => $categories
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load category from database'
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
            'category_name'    => 'required',
            'category_name_ar' => 'required',
            'service_id'       => 'required',
            'image'            => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $category = Category::add($request->all());
        if ($category) {
            return response()->json([
                'success' => 1,
                'message' => 'Category added successfully',
                "category_id" => $category->category_id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add Category'
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
        $category  = Category::with('products')->where('category_id', $id)->first();
        if ($category) {
            return response()->json([
                'success' => 1,
                'category' => $category
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Category not found'
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

    public function getServiceList()
    {
        $categoryList = Category::pluck('category_name', 'category_id')->toArray();
        return response()->json([
            'success' => 1,
            'categories' => $categoryList
        ], 200);
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
            'category_name'    => 'required',
            'category_name_ar' => 'required',
            'service_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $category =  Category::updateRecords($id, $request->all());
        if (!$category) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Category not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Category updated successfully',
            "category_id" => $category->category_id
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
            $category = Category::where('category_id', $id)->delete();
            return response()->json([
                'success' => 1,
                'message' => 'Category deleted successfully',
            ], 200);
        }
    }
}
