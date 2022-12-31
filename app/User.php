<?php

namespace App;

use App\Models\Feedback;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'email',
        'password',
        'firstname',
        'email',
        'lastname',
        'mobile_no',
        'profile_image',
        'role_id',
        'address',
        'comission',
        'region',
        'language',
        'notification',
        'location',
        'comission'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public static function add($params = [])
    {
        if (!empty($params)) {

            $image = '';
            if (!empty($params['profile_image'])) {
                $file = $params['profile_image'];
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                //Move Uploaded File
                $destinationPath = 'api/profile-image';
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }
            $params['profile_image'] = $image;
            $params['password'] = bcrypt($params['password']);
            return self::create($params);
        }
    }

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {
            if (!empty($params)) {

                $image = '';
                if (!empty($params['profile_image'])) {
                    $file = $params['profile_image'];
                    $fileName = uniqid() . '-' . $file->getClientOriginalName();

                    //Move Uploaded File
                    $destinationPath = 'api/profile-image';
                    $file->move($destinationPath, $fileName);
                    $image = $fileName;
                }
                $params['profile_image'] = $image;
                $user = User::findOrFail($id);
                $user->firstname = isset($params['firstname']) ? $params['firstname'] : $user->firstname;
                $user->lastname = isset($params['lastname']) ? $params['lastname'] : null;
                $user->mobile_no = isset($params['mobile_no']) ? $params['mobile_no'] : null;
                $user->profile_image = $image;
                $user->role_id = isset($params['role_id']) ? $params['role_id'] : 2;
                $user->address = isset($params['address']) ? $params['address'] : null;
                $user->comission = isset($params['comission']) ? $params['comission'] : null;
                $user->region = isset($params['region']) ? $params['region'] : null;
                $user->language = isset($params['language']) ? $params['language'] : null;
                $user->notification = isset($params['notification']) ? $params['notification'] : 0;
                $user->location = isset($params['location']) ? $params['location'] : null;
                $user->update();
                return $user;
            }
        }
    }
}
