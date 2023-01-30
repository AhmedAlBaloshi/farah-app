<?php

namespace App\Models;

use App\Models\ProductAvailability;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';
    protected $connection = 'farah';
    protected $table      = 'product';
    public $timestamps    = true;

    protected $fillable = [
        'product_name',
        'product_name_ar',
        'address',
        'address_ar',
        'latitude',
        'product_image',
        'longitude',
        'description',
        'category_id',
        'description_ar',
        'rate',
        'is_active',
        'created_at',
        'updated_at',
        'service_id',
        'service_list_id',
        'sub_service_id'
    ];

    public function service()
    {
        return $this->hasOne("App\Models\Service", "service_id", "service_id");
    }

    public function offers()
    {
        return $this->hasMany(Offer::class,'product_id','product_id');
    }
    public function banner()
    {
        return $this->hasOne("App\Models\Banner", 'product_id');
    }

    public function category()
    {
        return $this->belongsTo("App\Models\Category", "category_id");
    }

    public function serviceList()
    {
        return $this->belongsTo("App\Models\ServiceList", "service_list_id", "service_list_id");
    }

    public function subServiceList()
    {
        return $this->hasOne("App\Models\SubService", "sub_service_id",'sub_service_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_services');
    }

    public static function add($params = [])
    {
        if (!empty($params)) {
            $image = '';
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                //Move Uploaded File
                $destinationPath = 'api/product-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            return self::create([
                'product_name'      => $params['product_name'],
                'product_name_ar'   => $params['product_name_ar'],
                'address'           => $params['address'],
                'address_ar'        => $params['address_ar'],
                'latitude'          => $params['latitude'],
                'longitude'         => $params['longitude'],
                'product_image'     => $image,
                'description'       => $params['description'],
                'category_id'       => $params['category_id'],
                'description_ar'    => $params['description_ar'],
                'rate'              => $params['rate'],
                'is_active'         => !empty($params['is_active']) ? 1 : 0,
                'service_list_id'   => $params['service_list_id'],
                'sub_service_id'    => $params['sub_service_id'],
                'service_id'        => $params['service_id']
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
                $destinationPath = 'api/product-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            $product = Product::where('product_id', $id)->first();
            if ($product) {
                $product->product_name      = $params['product_name'];
                $product->product_name_ar   = $params['product_name_ar'];
                $product->address           = $params['address'];
                $product->address_ar        = $params['address_ar'];
                $product->latitude          = $params['latitude'];
                $product->longitude         = $params['longitude'];
                $product->description       = $params['description'];
                $product->category_id       = $params['category_id'];
                if (!empty($image)) {
                    $product->product_image = $image;
                }
                $product->description_ar    = $params['description_ar'];
                $product->rate              = $params['rate'];
                $product->is_active         = !empty($params['is_active']) ? 1 : 0;
                $product->service_list_id   = $params['service_list_id'];
                $product->sub_service_id    = $params['sub_service_id'];
                $product->service_id        = $params['service_id'];
                $product->update();
            }

            return $product;
        }
    }
}
