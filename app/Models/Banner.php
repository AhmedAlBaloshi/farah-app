<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';
    protected $fillable = [
        'image',
        'product_id'
    ];

    public function product()
    {
        return $this->hasOne("App\Models\Product", "product_id", "product_id");
    }

    public static function add($params = [])
    {
        if (!empty($params)) {

            $image = '';
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                //Move Uploaded File
                $destinationPath = 'banner-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            return self::create([
                'image'            => $image,
                'product_id'       => $params['product_id']
            ]);
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
                $destinationPath = 'banner-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            $banner = Banner::findOrFail($id);
            if ($banner) {
                $banner->image = $image;
                $banner->product_id = $params['product_id'];
                $banner->save();
                return $banner;
            }
        }
    }
}
