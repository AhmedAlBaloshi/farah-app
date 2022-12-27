<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'about_us';
    protected $fillable = [
        'description'
    ];

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {
            $about = AboutUs::findOrFail($id);
            if ($about) {
                $about->description = $params['description'];
                $about->save();
                return $about;
            }
        }
    }
}
