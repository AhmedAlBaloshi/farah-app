<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBanner extends Model
{
    protected $table = 'sub_banner';
    protected $fillable = [
        'image'
    ];

    public static function add($params = [])
    {
        if (!empty($params)) {

            $image = '';
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                //Move Uploaded File
                $destinationPath = 'api/sub-banner-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            return self::create([
                'image'            => $image
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
                $destinationPath = 'api/sub-banner-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            $banner = SubBanner::findOrFail($id);
            if ($banner) {
                $banner->image = $image;
                $banner->save();
                return $banner;
            }
        }
    }
}
