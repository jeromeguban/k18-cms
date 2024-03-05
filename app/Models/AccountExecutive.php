<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AccountExecutive extends Model
{
    use SoftDeletes;

    protected $searchable_fields = [
        'users.first_name', 'users.last_name',
    ];

    public function scopeLeftJoinUser($query)
    {
        $query->addSelect([
            'users.email',
        ]);

        $query->join('users', 'users.id', '=', 'account_executives.user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
