@extends('layout')

@section('content')
    <div class="max-w-3xl mx-auto px-6 pt-28 pb-20">
        <h1 class="font-display font-bold text-5xl text-cowboy-100 mb-8">{{ $title }}</h1>
        <div class="prose prose-invert font-body">
            {!! $content !!}
        </div>
    </div>
@endsection
