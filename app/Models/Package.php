<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';
    protected $fillable = [
        'title',
        'amount',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'package_services');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'package_services');
    }

    public static function add($params = [])
    {
        if (!empty($params)) {
            $package =  self::create([
                'title' => $params['title'],
                'amount' => $params['amount'],
                'start_date' => $params['start_date'],
                'start_time' => $params['start_time'],
                'end_date' => $params['end_date'],
                'end_time' => $params['end_time']
            ]);

            // add records in package service
            if (!empty($params['items'])) {
                $packageServiceParams = [];
                foreach ($params['items'] as $key => $item) {
                    $packageServiceParams[] = [
                        'package_id' => $package->id,
                        'product_id' => isset($item['product_id']) ? $item['product_id'] : null,
                        'service_id' => isset($item['service_id']) ? $item['service_id'] : null,
                    ];
                }
                PackageService::add($packageServiceParams);
            }

            return $package;
        }
    }

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {

            $package = Package::findOrFail($id);
            if ($package) {
                $package->title = $params['title'];
                $package->amount   = $params['amount'];
                $package->start_date = $params['start_date'];
                $package->start_time = $params['start_time'];
                $package->end_date = $params['end_date'];
                $package->end_time = $params['end_time'];
                $package->update();
            }

            // add records in product availability
            if (!empty($params['items'])) {
                $packageServiceParams = [];
                foreach ($params['items'] as $key => $item) {
                    $packageServiceParams[] = [
                        'package_id' => $package->id,
                        'product_id' => isset($item['product_id']) ? $item['product_id'] : null,
                        'service_id' => isset($item['service_id']) ? $item['service_id'] : null,
                    ];
                }
                $pkgService = PackageService::updateRecords($packageServiceParams);
            }
            return $pkgService;
        }
    }
}
