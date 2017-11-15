@extends('layouts.admin')

@section('content');

    <h1>Posts</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Photo</th>
                <th>Owner</th>
                <th>Category</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created</th>
                <th>Updated</th>
            </tr>
        </thead>

        <tbody>

        <!--check if any posts have been returned-->
        @if($posts)
            <!--loop through each post-->
            @foreach($posts as $post)

                <tr>
                    <td>{{$post->id}}</td>
                    <td><img height="50" src="{{$post->photo ? $post->photo->path :'/images/placeholder.png' }}" ></td>
                    <td><a href="{{route('posts.edit', $post->id)}}">{{$post->user->name}}</a></td>
                    <td>{{$post->category->name}}</td>
                    <td>{{$post->title}}</td>
                    <!--laravel string helper functions-->
                    <td>{{str_limit($post->body, 12)}}</td>
                    <td>{{$post->created_at->diffForHumans()}}</td>
                    <td>{{$post->updated_at->diffForHumans()}}</td>
                </tr>

            @endforeach
        @endif

        </tbody>
    </table>
@stop