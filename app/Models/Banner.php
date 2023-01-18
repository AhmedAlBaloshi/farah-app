<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';
    protected $fillable = [
        'image',
        'product_id',
        'sub_service_id'
    ];

    public function product()
    {
        return $this->hasOne("App\Models\Product", "product_id", "product_id");
    }
    public function subService()
    {
        return $this->hasOne("App\Models\SubService", "sub_service_id", "sub_service_id");
    }

    public static function add($params = [])
    {
        if (!empty($params)) {

            $image = '';
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                //Move Uploaded File
                $destinationPath = 'api/banner-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }
            $params['image'] = $image;
            return self::create($params);
        }
    }

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {

            $image = '';
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                //Move Uploaded File
                $destinationPath = 'api/banner-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }
            $params['image'] = $image;
            $banner = Banner::findOrFail($id);
            if ($banner) {
                $banner->update($params);
                return $banner;
            }
        }
    }
}
