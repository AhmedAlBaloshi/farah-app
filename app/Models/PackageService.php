<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageService extends Model
{
    protected $table = 'package_services';
    protected $fillable = [
        'product_id',
        'service_id',
        'package_id',
    ];

    public static function add($params = [])
    {
        if (!empty($params)) {
            foreach ($params as $key => $param) {
                self::create([
                    'package_id' => $param['package_id'],
                    'product_id' => $param['product_id'],
                    // 'service_id' => $param['service_id'],
                ]);
            }
        }
    }

    public static function updateRecords($params = [])
    {
        if (!empty($params)) {
            foreach ($params as $key => $param) {
                PackageService::where('package_id', $param['package_id'])->delete();
                self::create([
                    'package_id' => $param['package_id'],
                    'product_id' => $param['product_id'],
                    // 'service_id'       => $param['service_id'],
                ]);
            }
            return $params;
        }
    }
}
