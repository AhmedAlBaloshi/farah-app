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

    public static function updateRecords($params = [],$sub_service_id)
    {
        if (!empty($params)) {
            self::where('sub_service_id', $sub_service_id)->delete();
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
                    'sub_service_id' => $sub_service_id,
                    'image'       => $image,
                ]);
            }
        }
    }
}
