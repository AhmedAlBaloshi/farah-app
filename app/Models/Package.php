<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';
    protected $fillable = [
        'title',
        'image',
        'detail',
        'amount',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'package_services', 'package_id', 'product_id');
    }

    public function services()
    {
        return $this->belongsToMany(SubService::class, 'package_services', 'service_id', 'service_id');
    }

    public static function add($params = [])
    {
        $image = '';
        if (!empty($params['image'])) {
            $file = $params['image'];
            $fileName = uniqid() . '-' . $file->getClientOriginalName();

            //Move Uploaded File
            $destinationPath = 'api/package-image';
            $file->move($destinationPath, $fileName);
            $image = $fileName;
        }
        if (!empty($params)) {
            $package =  self::create([
                'title' => $params['title'],
                'detail' => $params['detail'],
                'image' => $image,
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

            $image = '';
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                //Move Uploaded File
                $destinationPath = 'api/package-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            if ($package) {
                $package->title = $params['title'];
                $package->detail   = $params['detail'];
                $package->image   = $image;
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
