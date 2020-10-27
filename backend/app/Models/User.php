<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'phone'
    ];

    protected $hidden = [
        'password',
    ];

    public function tokens() {
        return $this->hasMany('App\Models\Token', 'user_id', 'uid');
    }

    public function posts() {
        return $this->hasMany('App\Models\Post', 'user_id', 'uid');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment', 'user_id', 'uid');
    }

    public function post_scores() {
        return $this->hasMany('App\Models\PostScore', 'user_id', 'uid');
    }
}
