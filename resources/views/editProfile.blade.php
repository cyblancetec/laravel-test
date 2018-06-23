@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <h3>Edit Profile</h3>
                    </div>
                </div>

                <div class="card-body">

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('update-profile',$user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        
                        <div class="form-group">
                            <strong>{{ __('Name') }}</strong>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                        </div>
                
                        <div class="form-group">
                            <strong>{{ __('Email') }}</strong>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <strong>{{ __('Phone') }}</strong>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <strong>{{ __('Photo') }}</strong>
                            <input type="file" name="photo" value="" class="form-control">
                            <br>
                            <img src="{{ url('public/uploads/avtars/'.$user->photo) }}" width="50" alt="">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection