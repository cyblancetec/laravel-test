@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach ($posts as $post)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="posts-image">
                        <a href="{{ route('post',$post) }}"><img class="card-img-top" src="{{ url('public/uploads/posts/'.$post->image) }}" alt=""></a>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">
                        <a href="{{ route('post',$post) }}">{{ $post->title }}</a>
                        </h4>
                        <p class="card-text">{!! \Illuminate\Support\Str::words($post->description, 15,'....')  !!}</p>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-lg-12 text-center">
            {!! $posts->links() !!}
        </div>
    </div>
</div>
@endsection
