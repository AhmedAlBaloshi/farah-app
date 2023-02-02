<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table      = 'product_image';
    protected $primaryKey = 'id';
    public $timestamps    = true;

    protected $fillable = [
        'sub_service_id',
        'image',
        'created_at',
        'updated_at'
    ];

    public function subService()
    {
        return $this->belongsTo("App\Models\SubService", "sub_service_id", "sub_service_id");
    }

    public static function add($params = [])
    {
        // return $params;
        if (!empty($params)) {
            foreach ($params as $key => $param) {
                $image = '';
                if (!empty($params[$key]['image'])) {
                    $file = $params[$key]['image'];
                    $fileName = uniqid() . '-' . $file->getClientOriginalName();

                    //Move Uploaded File
                    $destinationPath = 'api/sub-service-image';
                    $file->move($destinationPath, $fileName);
                    $image = $fileName;
                }
                self::create([
                    'sub_service_id' => $param['sub_service_id'],
                    'image'       => $image,
                ]);
            }
        }
    }

    public static function updateRecords($params = [], $sub_service_id)
    {
        if (!empty($params)) {
            $images = ProductImage::where('sub_service_id', $sub_service_id)->get();

            if (count($params) < count($images)) {
                foreach ($images as $key => $img) {
                    if (!empty($param[$key]['image']) && empty($params[$key]['id'])) {
                        $image = '';
                        $file = $params[$key]['image'];
                        $fileName = uniqid() . '-' . $file->getClientOriginalName();

                        //Move Uploaded File
                        $destinationPath = 'api/sub-service-image';
                        $file->move($destinationPath, $fileName);
                        $image = $fileName;
                        self::create([
                            'sub_service_id' => $sub_service_id,
                            'image'       => $image,
                        ]);
                    } else if (!empty($params[$key]['id'])) {
                    } else if (!empty($params[$key]['id']) && !empty($params[$key]['image'])) {
                        $image = '';
                        $file = $params[$key]['image'];
                        $fileName = uniqid() . '-' . $file->getClientOriginalName();

                        //Move Uploaded File
                        $destinationPath = 'api/sub-service-image';
                        $file->move($destinationPath, $fileName);
                        $image = $fileName;
                        $prodImg = ProductImage::where('id', $params[$key]['id'])->first();
                        $prodImg->image = $image;
                        $prodImg->update();
                    } else {
                        $img->delete();
                    }
                    // dd($params[$key]);
                }
            } else {
                foreach ($params as $param) {
                    if (!empty($param['image'])) {
                        $image = '';
                        $file = $param['image'];
                        $fileName = uniqid() . '-' . $file->getClientOriginalName();

                        //Move Uploaded File
                        $destinationPath = 'api/sub-service-image';
                        $file->move($destinationPath, $fileName);
                        $image = $fileName;
                        if (!empty($param['id'])) {
                            $prodImg = ProductImage::where('id', $param['id'])->first();
                            $prodImg->image = $image;
                            $prodImg->update();
                        } else {
                            self::create([
                                'sub_service_id' => $sub_service_id,
                                'image'       => $image,
                            ]);
                        }
                    } else if (count($images->where('id', $param['id'])->toArray()) < 1) {
                        $images->find($param['id'])->delete();
                    }
                    // dd(count($images->where('id', $param['id'])->toArray()));
                }
            }
        }
    }
}
