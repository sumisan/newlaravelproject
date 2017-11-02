<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFormRequest;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Photo;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //find all users
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //all() brings a collection that we do not want. We want an array.
        //pluck replaced lists()
        // $roles = Role::pluck("name","id")->all();

        //alternative
        $roles = Role::get()->pluck('name','id')->toArray();

        // $roles[" "] = "Choose role";

        //return $roles;
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersFormRequest $request)
    {
        //Method 1
        //register user
        //$user = new User;
        //$user->create($request->all());

        //Method 2
       //$user = User::create($request->all());

        //persist all data into the database
        $input = $request->all();

        //check if file has been uploaded
        if ($file = $request->file('photo_id')){
            //get photo name
            $name = time() . $file->getClientOriginalName();

            //move image to images folder
            $file->move('images', $name);

            //save photo name in  the photos table
            $photo = new Photo;
            $photo = $photo->create(['path'=>$name]);
            $photo_id = $photo->id;

            //use the photo id in the users table
            $input['photo_id'] = $photo_id;

        }

        //encrypt password
        $input['password'] = bcrypt($request->passowrd);
        
        //persist data in the database
        $user = new User;
        $user->create($input);

        return redirect('/admin/users');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
