<?php

namespace App\Models;

use App\ModelObjects\Store as StoreObject;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodeine\Acl\Traits\HasRole;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, Pagination, SoftDeletes, HasRole, HasApiTokens;

    private static $developer_email = [
        'jerome.guban@hmrphils.com',
        'devteam@hmrphils.com',
        'devteam2@hmrphils.com',
        'johnpaul.sim@hmrphils.com',
    ];

    private static $notification_channels = [
        'store-inquiries'
    ];

    protected $appends = [
        'fullname',
    ];

    protected $searchable_fields = [
        'first_name',
        'last_name',
        'email',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'reset_password_date',
        'reset_by',
        'created_by',
        'modified_by',
        'deleted_by',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function resetPassword()
    {
        $new_password = '123456';

        return $this->update([
            'password' => bcrypt($new_password),
            'reset_by' => auth()->id(),
            'reset_password_date' => now(),
        ]);
    }

    public static function isDeveloper()
    {
        return in_array(auth()->user()->email, self::$developer_email) ? true : null;
    }

    public function validateIfDeveloper()
    {
        return in_array(auth()->user()->email, self::$developer_email);
    }

    // Model Objects

    public function store()
    {
        return new StoreObject($this);
    }

    // Relations
    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function resetBy()
    {
        return $this->belongsTo(User::class, 'reset_by');
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function userStores()
    {
        return $this->hasMany(UserStore::class);
    }

    public function stores()
    {
        return $this->hasManyThrough(Store::class, UserStore::class, 'user_id', 'id', 'id', 'store_id');
    }

    public function getActiveNotificationChannels()
    {
        if($this->validateIfDeveloper())
            return self::$notification_channels;
            
        if(!isset($this->getPermissions()['system-notification']) || (isset($this->getPermissions()['system-notification']) && empty($this->getPermissions()['system-notification'])))
            return [];

        return collect($this->getPermissions()['system-notification'])->filter(function($notification, $key){
            return $notification;
        })->keys()->toArray();
    }
}
