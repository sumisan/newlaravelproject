@extends('layouts.admin')

@section('content')

    <h1>Registered Users</h1>

    <!--display session flash messages if any exist-->
    @if(session('user_registered') != '')

        <p class="bg-success">{{session('user_registered')}}</p>

    @endif

    @if(session('user_edited') != '')

        <p class="bg-success">{{session('user_edited')}}</p>

    @endif

    @if(session('user_deleted') != '')

        <p class="bg-danger">{{session('user_deleted')}}</p>

    @endif

    <table class="table">
        <thead>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    Photo
                </th>
                <th>
                    Username
                </th>
                <th>
                    Email
                </th>
                <th>
                    Role
                </th>
                <th>
                    Status
                </th>
                <th>
                    Created
                </th>
                <th>
                    Updated
                </th>
            </tr>
        </thead>
        <tbody>
            <!--check if there are any registers users-->
            @if($users)
                @foreach($users as $user)
                    <tr>
                        <td>
                            {{$user->id}}
                        </td>
                        <td>
                            <img src="{{$user->photo ? $user->photo->path : '/images/placeholder.png'}}" height="50">
                        </td>
                        <td>
                            <a href="{{route('users.edit', $user->id)}}"> {{$user->name}}</a>
                        </td>
                        <td>
                            {{$user->email}}
                        </td>
                        <td>
                            {{$user->role->name}}
                        </td>
                        <td>
                            {{$user->is_active == 1 ? 'Active' : 'Not Active'}}
                        </td>
                        <td>
                            {{$user->created_at->diffForHumans()}}
                        </td>
                        <td>
                            {{$user->updated_at->diffForHumans()}}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    @endsection