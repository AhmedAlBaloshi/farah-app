<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'farah';
    protected $table      = 'categories';
    protected $primaryKey = 'category_id';
    public $timestamps    = true;
    
    protected $fillable = [
        'category_id',
        'category_name',
        'category_name_ar',
        'parent_category',
        'image',
        'created_at',
        'updated_at',
        'service_id'
    ];

    public function service()
    {
        return $this->hasOne("App\Models\Service", "service_id", "service_id");
    }

    public function category()
    {
        return $this->belongsTo("App\Models\Category", "parent_category", "category_id");
    }

    public static function add($params=[])
    {
        if(!empty($params)) {
            
            $image = $params['image'];
            // if (!empty($params['image'])) {
            //     $file = $params['image'];
            //     $fileName = uniqid().'-'.$file->getClientOriginalName();
                
            //     //Move Uploaded File
            //     $destinationPath = 'category-image';
            //     $file->move($destinationPath,$fileName);
            //     $image = $fileName;
            // }

            return self::create([
                'category_name'     => $params['category_name'],
                'category_name_ar'  => $params['category_name_ar'],
                'parent_category'   => !empty($params['parent_category']) ? $params['parent_category'] : 0,
                'service_id'        => $params['service_id'],
                'image'             => $image
            ]);
        }
    }

    public static function updateRecords($id, $params=[])
    {
        if(!empty($params) && (int)$id > 0) {
            
            $image =$params['image'];
            // if (!empty($params['image'])) {
            //     $file = $params['image'];
            //     $fileName = uniqid().'-'.$file->getClientOriginalName();
                
            //     //Move Uploaded File
            //     $destinationPath = 'category-image';
            //     $file->move($destinationPath,$fileName);
            //     $image = $fileName;
            // }

            $category = Category::where('category_id',$id)->first();
            if($category) {
                $category->category_name     = $params['category_name'];
                $category->category_name_ar  = $params['category_name_ar'];
                $category->parent_category       = $params['parent_category'];
                $category->service_id       = $params['service_id'];
                if(!empty($image)) {
                    $category->image = $image;
                }
                $category->save();

                return $category;
            }
        }
    }
    
}