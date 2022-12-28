<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $fillable = [
        'description',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function add($params = [])
    {
        if (!empty($params)) {
            return self::create([
                'description' => $params['description'],
                'user_id'       => $params['user_id']
            ]);
        }
    }
}
