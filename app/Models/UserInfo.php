<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'tel',
        'about'
    ];
    
    public function user() {

        return $this->belongsTo(UserInfo::class, 'user_id', 'id');
    }
}
