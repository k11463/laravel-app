<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'class', 'content'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'uid');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment', 'post_id', 'pid');
    }

    public function scores() {
        return $this->hasMany('App\Models\PostScore', 'post_id', 'pid');
    }
}
