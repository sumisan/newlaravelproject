<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostsCreateRequest;
use App\Photo;
use App\Post;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all the categories
        $categories = new Category;
        $categories = $categories->get()->pluck('name','id')->toArray();

        return view('admin.posts.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostsCreateRequest $request)
    {
        //get logged in user
        $user = Auth::user();

        //for photos purposes
        $input = $request->all();

        //check if photo has been uploaded
        if($file = $request->file('photo_id')){
            //get photo name
            $name = time() . $file->getClientOriginalName();

            $file->move('images', $name);

            $photo = new Photo;
            $photo = $photo->create(['path'=>$name]);

            $input['photo_id'] = $photo->id;

        }

        //persist data in the database
        $user->posts()->create($input);

        return redirect('/admin/posts');
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
        $post = new Post;
        $post = $post->findOrFail($id);

        //get category name
        $categories = new Category;
        $categories = $categories->get()->pluck('name', 'id')->toArray();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostsCreateRequest $request, $id)
    {
        //get logged in user
        $user = Auth::user();

        //get post to be updated
        $post = new Post();
        $post = $post->findOrFail($id);

        //get all input data
        $input = $request->all();

        //check if photo is uploaded
        if($file = $request->file('photo_id')){
            //get photo name
            $name = time() . $file->getClientOriginalName();

            //store image in folder
            $file->move('images', $name);

            //get photo id to be update
            $photoId = $post->photo_id;

            //store image path in photo table
            $post->photo()->whereId($photoId)->update(['path'=>$name]);

            //get photo id
            $input['photo_id'] = $photoId;
        }

        $user->posts()->whereId($id)->first()->update($input);

        //make updates in posts table
       // $post->update($input);

        return redirect('/admin/posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = new Post;
        $post = $post->findOrFail($id);

        //delete image in directory
        unlink(public_path() . $post->photo->path);

        $post->delete();

        return redirect('/admin/posts');
    }
}
