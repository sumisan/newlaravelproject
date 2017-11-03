<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFormRequest;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Photo;
use Illuminate\Support\Facades\Session;

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

        //=========================================================
        //check if password field is empty
        /*if (trim($request->password) == ''){
            //add all other input fields except the password field
            $input = $request->except('password');

            //with only you list fields to be included
            $input = $request->only('password','name');
        }else{

            //include all the input fields
            $input = $request->all();
        }*/
        //===========================================================

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


        //trim password. remove any white spaces it might have
        $input['password'] = trim($input['password']);

        //encrypt password
        $input['password'] = bcrypt($request->passowrd);

        //persist data in the database
        $user = new User;
        $user->create($input);

        //create a flash message
        $request->session()->flash('user_registered', 'User has been registered successfully');

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
        $user = new User;
        $user = $user->findOrFail($id);

        $roles = Role::get()->pluck('name','id')->toArray();

        return view('admin.users.edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersFormRequest $request, $id)
    {
        //======================================================
        //check if password field is empty
        /*if (trim($request->password) == ''){
            //add all other input fields except the password field
            $input = $request->except('password');

            //with only you list fields to be included
            $input = $request->only('password','name');
        }else{

            //include all the input fields
            $input = $request->all();
        }*/
        //=======================================================

       // dd($request->all());
        //get user
        $user = new User;

        $user = $user->findOrFail($id);

        //get all input data
        $input = $request->all();

        //check if file has been uploaded
        if($file = $request->file('photo_id')){
            //get photo name
            $name = time() . $file->getClientOriginalName();

            //store photo in images folder
            $file->move('images', $name);

            //update image path in photos table
            //check if user has photo
            if($user->photo) {
                $photo = new Photo;
                $photo = $photo->update(['path' => $name]);
            }else{
                $photo = new Photo;
                $photo = $photo->create(['path' => $name]);
            }

            //get photo_id
            $input['photo_id'] = $photo->id;
        }


        //trim password. remove any white spaces it might have
        $input['password'] = trim($input['password']);
        //encrypt password
        $input['password'] = bcrypt($request->password);

        $user->update($input);

        //create a flash message
        $request->session()->flash('user_edited', 'User\'s profile has been updated successfully');

        //redirect to users index page
       return redirect('admin/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = new User;

        //find user
        $user = $user->findOrFail($id);

        //delete user's photo from the local directory
        unlink(public_path() . $user->photo->path);

        //delete user from the database
        $user->delete();

        //create a flash message
        $request->session()->flash('user_deleted', 'User has been deleted successfully');

        return redirect("/admin/users");
    }
}
