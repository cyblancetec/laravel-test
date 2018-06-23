@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row ">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="post-image">
                        <img class="img-fluid" src="{{ url('public/uploads/posts/'.$post->image) }}" alt="">
                    </div>
                    <h1 class="mt-4">{{ $post->title }}</h1>
                    <p class="lead">by {{ $post->user->name }} </p>
                    <hr>
                    <p>Posted on {{ $post->created_at->diffForHumans() }}</p>
                    <hr>
                    <p>{{ $post->description }}</p>
                </div>
            </div>
            <div class="float-left mt-3">
                <a class="btn btn-primary" href="{{ route('posts.index') }}"> Back</a>
            </div>
        </div>
    </div>
</div>

@endsection