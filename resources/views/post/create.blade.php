@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <h3>Add New Post</h3>
                    </div>
                    <div class="float-right">
                        <a class="btn btn-primary" href="{{ route('posts.index') }}"> Back</a>
                    </div>
                </div>

                <div class="card-body">

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

                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <strong>Image:</strong>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <strong>Title:</strong>
                            <input type="text" name="title" class="form-control">
                        </div>
                    
                        <div class="form-group">
                            <strong>Description:</strong>
                            <textarea class="form-control" style="height:150px" name="description"></textarea>
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