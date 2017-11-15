<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $directory = "/images/";

    //mass assignment
    protected $fillable = ['path'];

    //photo-user one to one relationship
    public function user(){
        return $this->hasOne('App\User');
    }

    //Accessor method
    public function getPathAttribute($photo){

        return $this->directory . $photo;
    }

    //photo posts one to one relationship
    public function post(){
        return $this->hasOne('App\Post');
    }
}
