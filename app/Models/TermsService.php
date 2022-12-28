<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsService extends Model
{
    protected $table = 'terms_services';
    protected $fillable = [
        'description'
    ];

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {
            $term = TermsService::findOrFail($id);
            if ($term) {
                $term->description = $params['description'];
                $term->save();
                return $term;
            }
        }
    }
}
