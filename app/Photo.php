<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //mass assignment
    protected $fillable = ['path'];

    //photo-user one to one relationship
    public function user(){
        return $this->hasOne('App\User');
    }
}
