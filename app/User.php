<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active','role_id','photo_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //if user is deleting his/her post is also deleted
    protected static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
            $user->posts()->delete();
            // do the rest of the cleanup...
        });
    }

    //one to many relation
    public function role(){
        return $this->belongsTo('App\Role');
    }

    //user photo one to one relationship
    public function photo(){
        return $this->belongsTo('App\Photo');
    }

    //check if user is Admin and is active
    public function isAdmin(){

        if($this->role->name == "administrator" && $this->is_active == 1){

            return true;
        }

            return false;

    }//close public function isAdmin(){

    //user post one to many relationship
    public function posts(){
        return $this->hasMany('App\Post');
    }

}
