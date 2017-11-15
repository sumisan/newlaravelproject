<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //

    protected $fillable = [
        'user_id',
        'category_id',
        'photo_id',
        'title',
        'body'
    ];


    //user posts one to many relation
    public function user(){
        return $this->belongsTo('App\User');
    }

    //photo posts one to one relationship
    public function photo(){
        return $this->belongsTo('App\Photo');
    }

    //category post one to many relationship
    public function category(){
        return $this->belongsTo('App\Category');
    }

}
