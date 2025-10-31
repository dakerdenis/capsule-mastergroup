<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthActivityLog extends Model
{
    public $timestamps = false; // есть только created_at
    protected $fillable = ['user_id','guard','event','email','ip','user_agent','created_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
