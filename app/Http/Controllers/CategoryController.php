<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;
use Response;

class CategoryController extends Controller
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
        $category = Category::get();
        return view('category.list',compact('category'));
    }

    /**
     * Create Category
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service  = \App\Models\Service::pluck('service_name','service_id')->toArray();
        $categoryList = \App\Models\Category::pluck('category_name','category_id')->toArray();
     
        return view('category.form',compact(['service','categoryList']));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'category_name'    => 'required',
            'category_name_ar' => 'required',
            'service_id'       => 'required',
            'image'            => 'required'
        ]);

        Category::add($request->all());

        return redirect()->route('category_index')->with('success','Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $service      = \App\Models\Service::pluck('service_name','service_id')->toArray();
        $categoryList = \App\Models\Category::whereNotIn('category_id',[$id])->pluck('category_name','category_id')->toArray();
        $category     = [];
        
        if ((int)$id > 0) {
            $category  = Category::find($id);
        }
        
        return view('category.form',compact(['category','service','categoryList']));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'category_name'    => 'required',
            'category_name_ar' => 'required',
            'service_id'       => 'required',
        ]);

        Category::updateRecords($id,$request->all());

        return redirect()->route('category_index')->with('success','Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {
            
            $category = Category::where('category_id',$id)->delete();
            return Response::json(["code" => 200,
                "response_status" => "success",
                "message"         => "Record deleted successfully",
                "data"            => []
            ]);

        }
        
        return Response::json(["code" => 500, 
            "response_status" => "error",
            "message"         => "Something went wrong"
        ]);
    }

}